<?php

namespace Famindo\AnalyticalCRM\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TransactionETL
{
    /**
     * Build market-basket transactions from won quotes + quote items.
     *
     * Options (all optional):
     * - from: Y-m-d or Carbon (based on lead closed_at or quote created_at)
     * - to: Y-m-d or Carbon
     * - customer_ids: int[] (maps to quote person_id)
     * - organization_ids: int[] (maps to person.organization_id)
     * - min_items: int (remove transactions with fewer items)
     * - persist: bool (save snapshot to apriori_transactions)
     *
     * @return array<int, array<int, string>>
     */
    public function build(array $options = []): array
    {
        $from = $this->toCarbonOrNull(Arr::get($options, 'from'));
        $to = $this->toCarbonOrNull(Arr::get($options, 'to'));
        $customerIds = Arr::get($options, 'customer_ids');
        $organizationIds = Arr::get($options, 'organization_ids');
        $minItems = (int) (Arr::get($options, 'min_items', 1));
        $persist = (bool) Arr::get($options, 'persist', false);

        $query = DB::table('quote_items as qi')
            ->join('quotes as q', 'q.id', '=', 'qi.quote_id')
            ->join('lead_quotes as lq', 'lq.quote_id', '=', 'q.id')
            ->join('leads', 'leads.id', '=', 'lq.lead_id')
            ->join('lead_pipeline_stages as lps', 'lps.id', '=', 'leads.lead_pipeline_stage_id')
            ->leftJoin('persons as person', 'person.id', '=', 'q.person_id')
            ->selectRaw("
                qi.quote_id,
                leads.id as lead_id,
                COALESCE(NULLIF(TRIM(qi.sku), ''), CONCAT('product:', qi.product_id)) as item_code,
                COALESCE(leads.closed_at, q.created_at) as effective_date,
                q.person_id,
                person.organization_id
            ")
            ->where('lps.code', 'won')
            ->where(function ($q) {
                $q->whereNotNull('qi.sku')
                    ->whereRaw("TRIM(qi.sku) <> ''")
                    ->orWhereNotNull('qi.product_id');
            });

        if ($from) {
            $query->whereDate(DB::raw('COALESCE(leads.closed_at, q.created_at)'), '>=', $from->toDateString());
        }

        if ($to) {
            $query->whereDate(DB::raw('COALESCE(leads.closed_at, q.created_at)'), '<=', $to->toDateString());
        }

        if (is_array($customerIds) && ! empty($customerIds)) {
            $query->whereIn('q.person_id', $customerIds);
        }

        if (is_array($organizationIds) && ! empty($organizationIds)) {
            $query->whereIn('person.organization_id', $organizationIds);
        }

        $rows = $query
            ->orderBy('qi.quote_id')
            ->get();

        $byQuote = [];

        foreach ($rows as $row) {
            $quoteId = (int) $row->quote_id;
            $code = (string) $row->item_code;
            if ($code === '' || $code === 'product:') {
                continue;
            }

            if (! isset($byQuote[$quoteId])) {
                $byQuote[$quoteId] = [
                    'items'   => [],
                    'lead_id' => $row->lead_id ? (int) $row->lead_id : null,
                ];
            }

            $byQuote[$quoteId]['items'][$code] = true; // deduplicate per quote
        }

        $transactions = [];
        $quoteIds = [];
        $leadIds = [];

        foreach ($byQuote as $quoteId => $payload) {
            $items = array_keys($payload['items']);
            if (count($items) < max(1, $minItems)) {
                continue;
            }

            sort($items, SORT_STRING);
            $transactions[] = $items;
            $quoteIds[] = $quoteId;
            $leadIds[] = $payload['lead_id'] ?? null;
        }

        if ($persist && ! empty($transactions)) {
            $this->persistTransactions($quoteIds, $leadIds, $transactions);
        }

        return $transactions;
    }

    protected function persistTransactions(array $quoteIds, array $leadIds, array $transactions): void
    {
        $now = Carbon::now();
        $inserts = [];

        foreach ($transactions as $idx => $items) {
            $inserts[] = [
                'quote_id'   => $quoteIds[$idx] ?? null,
                'lead_id'    => $leadIds[$idx] ?? null,
                'items'      => json_encode(array_values($items), JSON_UNESCAPED_UNICODE),
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (! empty($inserts)) {
            DB::table('apriori_transactions')->insert($inserts);
        }
    }

    protected function toCarbonOrNull($value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        if ($value instanceof Carbon) {
            return $value;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
