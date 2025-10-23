<?php

namespace Famindo\AnalyticalCRM\DataGrids;

use Famindo\AnalyticalCRM\Models\AprioriRun;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class AprioriRulesDataGrid extends DataGrid
{
    protected $sortColumn = 'created_at';

    protected $sortOrder = 'desc';

    protected static array $skuCache = [];

    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('apriori_rules')->select(
            'apriori_rules.id',
            'apriori_rules.run_id',
            'apriori_rules.lhs',
            'apriori_rules.rhs',
            'apriori_rules.support',
            'apriori_rules.confidence',
            'apriori_rules.lift',
            'apriori_rules.period_start',
            'apriori_rules.period_end',
            'apriori_rules.created_at',
            'runs.name as run_name'
        );

        $queryBuilder->leftJoin('apriori_runs as runs', 'runs.id', '=', 'apriori_rules.run_id');

        $runId = request()->input('run_id');

        if (! $runId) {
            $runId = AprioriRun::where('is_active', true)->value('id');
        }

        if ($runId) {
            $queryBuilder->where('apriori_rules.run_id', $runId);
        }

        // Allow global search box to search within JSON columns (lhs/rhs) by substring (SKU).
        // This works because SKUs are stored as JSON strings, and a LIKE on the JSON text is sufficient.
        $this->addFilter('lhs', 'apriori_rules.lhs');
        $this->addFilter('rhs', 'apriori_rules.rhs');

        $this->addFilter('support', 'support');
        $this->addFilter('confidence', 'confidence');
        $this->addFilter('lift', 'lift');
        $this->addFilter('period_start', 'period_start');
        $this->addFilter('period_end', 'period_end');
        $this->addFilter('created_at', 'created_at');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'run_name',
            'label'      => 'Snapshot',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'lhs',
            'label'      => 'LHS',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => fn ($row) => $this->formatSkuLinks($row->lhs),
        ]);

        $this->addColumn([
            'index'      => 'rhs',
            'label'      => 'RHS',
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => false,
            'closure'    => fn ($row) => $this->formatSkuLinks($row->rhs),
        ]);

        $this->addColumn([
            'index'      => 'support',
            'label'      => 'Support',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => number_format((float) $row->support, 4),
        ]);

        $this->addColumn([
            'index'      => 'confidence',
            'label'      => 'Confidence',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => number_format((float) $row->confidence, 4),
        ]);

        $this->addColumn([
            'index'      => 'lift',
            'label'      => 'Lift',
            'type'       => 'string',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => fn ($row) => number_format((float) $row->lift, 4),
        ]);

        $this->addColumn([
            'index'      => 'period_start',
            'label'      => 'Period Start',
            'type'       => 'date',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'period_end',
            'label'      => 'Period End',
            'type'       => 'date',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => 'Created At',
            'type'       => 'date',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    private function formatSkuLinks(?string $json): string
    {
        if (! $json) {
            return '—';
        }

        $skus = array_values(array_filter((array) json_decode($json, true), function ($sku) {
            return $sku !== null && $sku !== '';
        }));

        if (empty($skus)) {
            return '—';
        }

        $skus = array_map('strval', $skus);

        $cache = &self::$skuCache;
        $missing = array_diff($skus, array_keys($cache));

        if (! empty($missing)) {
            $records = DB::table('products')
                ->select('id', 'sku', 'name', 'description', 'price')
                ->whereIn('sku', $missing)
                ->get()
                ->keyBy('sku');

            foreach ($missing as $sku) {
                $product = $records[$sku] ?? null;

                if (! $product) {
                    $cache[$sku] = null;

                    continue;
                }

                $cache[$sku] = [
                    'id'          => (int) $product->id,
                    'name'        => (string) $product->name,
                    'description' => (string) ($product->description ?? ''),
                    'price'       => (string) $product->price,
                ];
            }
        }

        $links = [];

        foreach ($skus as $sku) {
            $product = $cache[$sku] ?? null;

            if (! $product) {
                $links[] = e($sku);

                continue;
            }

            $url = route('admin.products.edit', $product['id']);

            $links[] = '<a href="'.$url.'" class="sku-tooltip text-blue-600 hover:underline"'
                .' data-sku="'.e($sku).'"'
                .' data-name="'.e($product['name']).'"'
                .' data-description="'.e($product['description']).'"'
                .' data-price="'.e($product['price']).'"'
                .'>'.e($sku).'</a>';
        }

        return implode(', ', $links);
    }
}
