<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Webkul\Lead\Repositories\LeadRepository;
use Webkul\Quote\Repositories\QuoteRepository;

class LeadQuoteDemoSeeder extends Seeder
{
    private const TAG = '[DEMO] AnalyticalCRM LeadQuote';

    public function run(): void
    {
        DB::transaction(function () {
            $quoteIds = DB::table('quotes')
                ->where('description', 'like', self::TAG.'%')
                ->pluck('id');

            if ($quoteIds->isNotEmpty()) {
                DB::table('lead_quotes')->whereIn('quote_id', $quoteIds)->delete();
                DB::table('quote_items')->whereIn('quote_id', $quoteIds)->delete();
                DB::table('quotes')->whereIn('id', $quoteIds)->delete();
            }

            $leadIds = DB::table('leads')
                ->where('description', 'like', self::TAG.'%')
                ->pluck('id');

            if ($leadIds->isNotEmpty()) {
                DB::table('lead_quotes')->whereIn('lead_id', $leadIds)->delete();
                DB::table('lead_products')->whereIn('lead_id', $leadIds)->delete();
                DB::table('lead_activities')->whereIn('lead_id', $leadIds)->delete();
                DB::table('leads')->whereIn('id', $leadIds)->delete();
            }
        });

        $adminId = $this->resolveDefaultOwner();
        $leadSourceIds = DB::table('lead_sources')->pluck('id')->all();
        $leadTypeIds = DB::table('lead_types')->pluck('id')->all();

        $pipelineId = DB::table('lead_pipelines')->where('is_default', 1)->value('id') ?? 1;
        $wonStageId = DB::table('lead_pipeline_stages')
            ->where('lead_pipeline_id', $pipelineId)
            ->where('code', 'won')
            ->value('id');

        if (! $wonStageId) {
            throw new \RuntimeException('Lead pipeline stage "won" not found.');
        }

        $products = DB::table('products')
            ->select('id', 'sku', 'name', 'price')
            ->get()
            ->keyBy('sku');

        $headSkus = [
            'FFL-DRY-CHAM',
            'FFL-DRY-HEAT',
            'FFL-PID-TC',
            'FFL-TC-K',
            'FFL-FR-SS304',
            'FFL-CNV-MOD',
            'FFL-CNV-DRV',
            'FFL-VFD-075',
            'FFL-SENS-PE',
            'ALV-TANK-PE',
            'ALV-PUMP-DIA',
            'ALV-PLC-STD',
            'ALV-PANEL-IP65',
            'TST-FRAME',
            'TST-ACT-LIN',
            'TST-PLC-IO',
            'BLK-FR-WELD',
            'BLK-PLC-IO',
            'BLK-ENC-IP54',
            'BLK-LCURT',
        ];

        $bundleConfigs = $this->buildBundleConfigs($products, $headSkus);

        $orgPlan = $this->buildOrganizationPlan();

        $organizations = DB::table('organizations')
            ->select('id', 'name', 'address')
            ->whereIn('name', array_column($orgPlan, 'name'))
            ->get()
            ->keyBy('name');

        $persons = DB::table('persons')
            ->select('id', 'organization_id')
            ->whereIn('organization_id', $organizations->pluck('id'))
            ->get()
            ->groupBy('organization_id')
            ->map(fn ($rows) => $rows->first()->id);

        $addressBook = $this->buildAddressBook($organizations);

        /** @var LeadRepository $leadRepository */
        $leadRepository = app(LeadRepository::class);

        /** @var QuoteRepository $quoteRepository */
        $quoteRepository = app(QuoteRepository::class);

        // Natural calendar: Jan 1, 2025 to Nov 30, 2025
        $start = Carbon::create(2025, 1, 1)->startOfDay();
        $end = Carbon::create(2025, 11, 30)->endOfDay();

        // Exactly 300 leads, and 1 quote per lead
        $targetLeads = 300;
        $leadIndex = 1;

        // Prepare day list and initial quotas from distribution
        $days = [];
        $d = $start->copy();
        while ($d->lte($end)) {
            $days[] = $d->copy();
            $d->addDay();
        }
        $quotas = [];
        foreach ($days as $i => $day) {
            $isWeekend = in_array($day->dayOfWeek, [Carbon::SATURDAY, Carbon::SUNDAY], true);
            $quotas[$i] = $this->sampleDailyLeadQuota($isWeekend); // mostly 0..2
        }
        $this->adjustQuotasToTarget($quotas, $days, $targetLeads);

        // Map organization name to its plan for quick lookup
        $planByName = [];
        foreach ($orgPlan as $p) {
            $planByName[$p['name']] = $p;
        }

        foreach ($days as $i => $day) {
            $leadQuota = $quotas[$i];

            for ($l = 0; $l < $leadQuota; $l++) {
                // Pick random organization from plan
                $plan = Arr::random($orgPlan);
                $orgName = $plan['name'];
                $organization = $organizations[$orgName] ?? null;
                if (! $organization) {
                    continue;
                }
                $personId = $persons[$organization->id] ?? null;
                if (! $personId) {
                    continue;
                }

                // Bundle selection: mostly primary, sometimes alternative
                $bundleKey = $this->maybeSwitchBundle($plan['bundle']);
                $bundle = $bundleConfigs[$bundleKey] ?? null;
                if (! $bundle) {
                    $bundleKey = $plan['bundle'];
                    $bundle = $bundleConfigs[$bundleKey] ?? reset($bundleConfigs);
                }

                // Compose initial SKUs for first quote (probabilistic anchors/base + mid + long-tail + tiny cross-bundle)
                $skuList = $this->composeSkuBasket($bundleKey, $bundleConfigs);

                // Build items array and totals
                $items = [];
                $subTotal = 0.0;
                foreach ($skuList as $sku) {
                    $product = $products[$sku] ?? null;
                    if (! $product) {
                        continue;
                    }
                    $price = round((float) $product->price, 4);
                    $items[] = [
                        'product_id'       => $product->id,
                        'sku'              => $product->sku,
                        'name'             => $product->name,
                        'quantity'         => 1,
                        'price'            => $price,
                        'total'            => $price,
                        'discount_percent' => 0,
                        'discount_amount'  => 0,
                        'tax_percent'      => 0,
                        'tax_amount'       => 0,
                    ];
                    $subTotal += $price;
                }

                if (empty($items)) {
                    continue;
                }

                // Times: lead created at day H, quote H+1..H+5, closed H+2..H+14 (clamped to Nov 30)
                $leadCreatedAt = (clone $day)->setTime(random_int(8, 16), random_int(0, 59));
                $quote1CreatedAt = (clone $leadCreatedAt)->addDays(random_int(1, 5))->setTime(random_int(9, 17), random_int(0, 59));

                // Ensure all within allowed period
                if ($quote1CreatedAt->gt($end)) {
                    $quote1CreatedAt = (clone $end)->setTime(16, 30, 0);
                }

                $quoteSubject = 'Penawaran '.$bundle['label'].' untuk '.$orgName.' (Rev.1)';
                $leadTitle = $bundle['label'].' untuk '.$orgName;

                $leadData = [
                    'entity_type'            => 'leads',
                    'title'                  => $leadTitle,
                    'description'            => self::TAG.' '.$bundleKey.' #'.str_pad((string) $leadIndex, 3, '0', STR_PAD_LEFT),
                    'lead_value'             => $this->computeLeadValue($items),
                    'status'                 => 1,
                    'expected_close_date'    => $quote1CreatedAt->copy()->addDays(random_int(5, 20))->toDateString(),
                    'closed_at'              => null, // set after last quote
                    'user_id'                => $adminId,
                    'person_id'              => $personId,
                    'lead_source_id'         => Arr::random($leadSourceIds),
                    'lead_type_id'           => Arr::random($leadTypeIds),
                    'lead_pipeline_id'       => $pipelineId,
                    'lead_pipeline_stage_id' => $wonStageId,
                ];

                $lead = $leadRepository->create($leadData);
                DB::table('leads')->where('id', $lead->id)->update([
                    'created_at' => $leadCreatedAt,
                ]);

                $address = $addressBook[$orgName] ?? $this->fallbackAddress($orgName);

                $quoteData = [
                    'entity_type'      => 'quotes',
                    'subject'          => $quoteSubject,
                    'description'      => self::TAG.' '.$bundleKey.' #'.str_pad((string) $leadIndex, 3, '0', STR_PAD_LEFT),
                    'billing_address'  => $address,
                    'shipping_address' => $address,
                    'discount_percent' => 0,
                    'discount_amount'  => 0,
                    'tax_amount'       => 0,
                    'adjustment_amount'=> 0,
                    'sub_total'        => round($subTotal, 4),
                    'grand_total'      => round($subTotal, 4),
                    'expired_at'       => min($quote1CreatedAt->copy()->addDays(random_int(15, 30)), $end),
                    'user_id'          => $adminId,
                    'person_id'        => $personId,
                    'items'            => $items,
                ];
                $quote = $quoteRepository->create($quoteData);
                DB::table('quotes')->where('id', $quote->id)->update([
                    'created_at' => $quote1CreatedAt,
                    'updated_at' => $quote1CreatedAt,
                ]);
                DB::table('lead_quotes')->insert([
                    'lead_id'  => $lead->id,
                    'quote_id' => $quote->id,
                ]);

                // Close the lead after the quote, 2–14 days after, clamped to Nov 30
                $closedAt = min($quote1CreatedAt->copy()->addDays(random_int(2, 14))->setTime(random_int(10, 18), random_int(0, 59)), $end);
                DB::table('leads')->where('id', $lead->id)->update([
                    'updated_at' => $closedAt,
                    'closed_at'  => $closedAt,
                ]);

                $leadIndex++;
            }
        }
    }

    // --- Natural generation helpers ---

    private function sampleDailyLeadQuota(bool $isWeekend): int
    {
        // Weekday: {0:0.25, 1:0.55, 2:0.18, 3:0.02}
        // Weekend: {0:0.75, 1:0.22, 2:0.03}
        $r = mt_rand() / mt_getrandmax();
        if ($isWeekend) {
            if ($r < 0.75) return 0;
            if ($r < 0.97) return 1;
            return 2;
        }
        if ($r < 0.25) return 0;
        if ($r < 0.80) return 1;
        if ($r < 0.98) return 2;
        return 3;
    }

    private function maybeSwitchBundle(string $primary): string
    {
        // stay with primary vs switch: configurable via env
        $bundles = ['oven', 'conveyor', 'coating', 'testing', 'press'];
        $pPrimary = (float) (env('ANALYTIC_DEMO_BUNDLE_PRIMARY_WEIGHT', 0.80));
        if ((mt_rand() / mt_getrandmax()) < $pPrimary) {
            return $primary;
        }
        $others = array_values(array_diff($bundles, [$primary]));
        return Arr::random($others);
    }

    private function composeSkuBasket(string $bundleKey, array $bundleConfigs): array
    {
        $cfg = $bundleConfigs[$bundleKey] ?? null;
        if (! $cfg) return [];

        $core = $cfg['core_head'] ?? [];
        $mid  = $cfg['mid'] ?? [];
        $long = $cfg['long_tail'] ?? [];

        // Split anchors (first up to 2) and optional base (rest)
        $anchors = array_slice($core, 0, min(2, count($core)));
        $base    = array_slice($core, count($anchors));

        $pAnchor   = (float) (env('ANALYTIC_DEMO_P_ANCHOR', 0.85)); // each anchor present independently
        $pOptional = (float) (env('ANALYTIC_DEMO_P_OPTIONAL', 0.55)); // optional base present
        $pLongTail = (float) (env('ANALYTIC_DEMO_P_LONGTAIL', 0.18)); // add one long-tail item
        $pCross    = (float) (env('ANALYTIC_DEMO_P_CROSS', 0.08)); // cross-bundle tiny chance

        $skus = [];

        foreach ($anchors as $sku) {
            if ((mt_rand() / mt_getrandmax()) < $pAnchor) {
                $skus[] = $sku;
            }
        }

        foreach ($base as $sku) {
            if ((mt_rand() / mt_getrandmax()) < $pOptional) {
                $skus[] = $sku;
            }
        }

        $lambda = (float) (env('ANALYTIC_DEMO_MID_LAMBDA', 1.5));
        $maxK   = (int) (env('ANALYTIC_DEMO_MID_MAXK', 3));
        $kMid   = $this->sampleTruncatedPoisson($lambda, $maxK);
        if ($kMid > 0 && ! empty($mid)) {
            $pick = (array) Arr::random($mid, min($kMid, count($mid)));
            $skus = array_merge($skus, $pick);
        }

        if (! empty($long) && (mt_rand() / mt_getrandmax()) < $pLongTail) {
            $skus[] = Arr::random($long);
        }

        if ((mt_rand() / mt_getrandmax()) < $pCross) {
            $otherBundles = array_values(array_diff(array_keys($bundleConfigs), [$bundleKey]));
            $alt = $bundleConfigs[Arr::random($otherBundles)] ?? null;
            if ($alt && ! empty($alt['mid'])) {
                $skus[] = Arr::random($alt['mid']);
            }
        }

        $skus = array_values(array_unique($skus));
        // Ensure at least two items to qualify as market-basket transaction
        if (count($skus) < 2) {
            // Prefer to add from mid or base if available
            if (! empty($mid)) {
                $skus[] = Arr::random($mid);
            } elseif (! empty($base)) {
                $skus[] = Arr::random($base);
            } elseif (! empty($anchors)) {
                $skus[] = Arr::random($anchors);
            }
            $skus = array_values(array_unique($skus));
        }

        return $skus;
    }

    private function mutateSkuBasket(array $current, string $bundleKey, array $bundleConfigs): array
    {
        $cfg = $bundleConfigs[$bundleKey] ?? null;
        if (! $cfg) return $current;
        $pool = array_values(array_unique(array_merge(
            $cfg['core_head'] ?? [],
            $cfg['mid'] ?? [],
            $cfg['long_tail'] ?? []
        )));

        $result = $current;
        // With 60% chance, toggle one item (add if absent, remove if present)
        if ((mt_rand() / mt_getrandmax()) < 0.60 && ! empty($pool)) {
            $candidate = Arr::random($pool);
            if (in_array($candidate, $result, true)) {
                // remove it if that keeps at least 2 items
                if (count($result) > 2) {
                    $result = array_values(array_diff($result, [$candidate]));
                }
            } else {
                $result[] = $candidate;
            }
        }
        // Small chance to replace one mid with another mid
        if ((mt_rand() / mt_getrandmax()) < 0.35 && ! empty($cfg['mid'])) {
            $mids = $cfg['mid'];
            $inMids = array_values(array_intersect($result, $mids));
            if (! empty($inMids)) {
                $toReplace = Arr::random($inMids);
                $replacement = Arr::random(array_values(array_diff($mids, [$toReplace])));
                $result = array_values(array_diff($result, [$toReplace]));
                $result[] = $replacement;
            }
        }

        return array_values(array_unique($result));
    }

    private function sampleTruncatedPoisson(float $lambda, int $maxK): int
    {
        // Discrete Poisson PMF truncated to [0..maxK]
        $weights = [];
        $sum = 0.0;
        for ($k = 0; $k <= $maxK; $k++) {
            // pmf = e^-lambda * lambda^k / k!
            $pmf = exp(-$lambda) * pow($lambda, $k) / max(1, $this->factorial($k));
            $weights[$k] = $pmf;
            $sum += $pmf;
        }
        // normalize
        $r = (mt_rand() / mt_getrandmax()) * $sum;
        $acc = 0.0;
        foreach ($weights as $k => $w) {
            $acc += $w;
            if ($r <= $acc) {
                return (int) $k;
            }
        }
        return 0;
    }

    private function factorial(int $n): int
    {
        $f = 1;
        for ($i = 2; $i <= $n; $i++) {
            $f *= $i;
        }
        return $f;
    }

    private function sampleExtraQuotes(): int
    {
        // 30% chance add extra quotes; if yes: 1 (~80%) or 2 (~20%)
        if ((mt_rand() / mt_getrandmax()) >= 0.30) {
            return 0;
        }
        return (mt_rand() / mt_getrandmax()) < 0.80 ? 1 : 2;
    }

    private function adjustQuotasToTarget(array &$quotas, array $days, int $target): void
    {
        $sum = array_sum($quotas);
        $n = count($quotas);

        // Helper to pick candidate indices with a simple weekday weight
        $weekdayWeight = function (int $idx) use ($days): float {
            $dow = $days[$idx]->dayOfWeek;
            // Weekday weight 1.0, weekend 0.5
            return in_array($dow, [Carbon::SATURDAY, Carbon::SUNDAY], true) ? 0.5 : 1.0;
        };

        // Increase until reaching target (cap per day = 2)
        while ($sum < $target) {
            $candidates = [];
            foreach ($quotas as $idx => $q) {
                if ($q < 2) {
                    $candidates[$idx] = (2 - $q) * $weekdayWeight($idx);
                }
            }
            if (empty($candidates)) {
                break; // cannot increase further without breaking cap
            }
            $pick = $this->weightedPick($candidates);
            $quotas[$pick]++;
            $sum++;
        }

        // Decrease if we overshoot (prefer weekend first, then large quotas)
        while ($sum > $target) {
            $candidates = [];
            foreach ($quotas as $idx => $q) {
                if ($q > 0) {
                    // weekend prioritized to reduce (higher weight), and larger q preferred
                    $dow = $days[$idx]->dayOfWeek;
                    $isWeekend = in_array($dow, [Carbon::SATURDAY, Carbon::SUNDAY], true);
                    $candidates[$idx] = ($isWeekend ? 2.0 : 1.0) * $q;
                }
            }
            if (empty($candidates)) {
                break;
            }
            $pick = $this->weightedPick($candidates);
            $quotas[$pick]--;
            $sum--;
        }
    }

    private function weightedPick(array $weights): int
    {
        $total = array_sum($weights);
        if ($total <= 0) {
            return array_key_first($weights);
        }
        $r = (mt_rand() / mt_getrandmax()) * $total;
        $acc = 0.0;
        foreach ($weights as $idx => $w) {
            $acc += max(0.0, (float) $w);
            if ($r <= $acc) {
                return (int) $idx;
            }
        }
        return (int) array_key_last($weights);
    }

    private function resolveDefaultOwner(): int
    {
        $adminId = DB::table('users')
            ->where('email', 'admin@example.com')
            ->value('id');

        if ($adminId) {
            return (int) $adminId;
        }

        return (int) DB::table('users')->min('id');
    }

    private function buildBundleConfigs($products, array $headSkus): array
    {
        foreach ($headSkus as $sku) {
            if (! isset($products[$sku])) {
                throw new \RuntimeException(sprintf('Head product SKU "%s" not found in catalog.', $sku));
            }
        }

        $bundleConfigs = [
            'oven' => [
                'label'       => 'Sistem Oven Pengering Modular',
                'core_head'   => ['FFL-DRY-CHAM', 'FFL-DRY-HEAT', 'FFL-PID-TC', 'FFL-TC-K'],
                'mid'         => [
                    'FFL-INS-50',
                    'FFL-HUM-SNS',
                    'FFL-DRAIN-SET',
                    'FFL-NOZ-CIP',
                    'FFL-SENS-PRX',
                    'FFL-GUARD-SS',
                    'FFL-ESTOP',
                    'FFL-SKN-UNIT',
                ],
                'long_tail_prefix' => 'FFL',
            ],
            'conveyor' => [
                'label'       => 'Sistem Konveyor Stainless Modular',
                'core_head'   => ['FFL-FR-SS304', 'FFL-CNV-MOD', 'FFL-CNV-DRV', 'FFL-VFD-075', 'FFL-SENS-PE'],
                'mid'         => [
                    'FFL-PNU-CLAMP',
                    'FFL-VLV-52',
                    'FFL-FRL-14',
                    'FFL-FIT-PT',
                    'FFL-HMI-7',
                    'FFL-BIN-SS304',
                    'BLK-SENS-REED',
                    'BLK-PRS-SNS',
                ],
                'long_tail_prefix' => 'FFL',
            ],
            'coating' => [
                'label'       => 'Sistem Coating & Dosing Cairan',
                'core_head'   => ['ALV-TANK-PE', 'ALV-PUMP-DIA', 'ALV-PLC-STD', 'ALV-PANEL-IP65'],
                'mid'         => [
                    'ALV-PUMP-PR',
                    'ALV-CHK-VLV',
                    'ALV-NEEDLE',
                    'ALV-FILTER-IN',
                    'ALV-TUBE-PTFE',
                    'ALV-QC-FIT',
                    'ALV-DLOG',
                    'ALV-TWR-LGT',
                ],
                'long_tail_prefix' => 'ALV',
            ],
            'testing' => [
                'label'       => 'Rig Pengujian & Kalibrasi',
                'core_head'   => ['TST-FRAME', 'TST-ACT-LIN', 'TST-PLC-IO'],
                'mid'         => [
                    'TST-DRV-CTRL',
                    'TST-LVDT',
                    'TST-LIMIT',
                    'TST-PS-24V',
                    'TST-SAF-DOOR',
                    'TST-HMI-7',
                ],
                'long_tail_prefix' => 'TST',
            ],
            'press' => [
                'label'       => 'Jig Press & Automation Pneumatik',
                'core_head'   => ['BLK-FR-WELD', 'BLK-PLC-IO', 'BLK-ENC-IP54', 'BLK-LCURT'],
                'mid'         => [
                    'BLK-STRIPR',
                    'BLK-CLAMP-TG',
                    'BLK-LIN-GUIDE',
                    'BLK-SILENCER',
                    'BLK-FLOW',
                    'BLK-FRL-38',
                ],
                'long_tail_prefix' => 'BLK',
            ],
        ];

        $allSkus = $products->keys()->all();
        $midSkus = array_merge(
            $bundleConfigs['oven']['mid'],
            $bundleConfigs['conveyor']['mid'],
            $bundleConfigs['coating']['mid'],
            $bundleConfigs['testing']['mid'],
            $bundleConfigs['press']['mid'],
        );

        $unusedSkus = array_values(array_diff($allSkus, $headSkus, $midSkus));

        $grouped = [];

        foreach ($unusedSkus as $sku) {
            $prefix = strtok($sku, '-');
            $grouped[$prefix][] = $sku;
        }

        foreach ($bundleConfigs as $key => &$config) {
            $prefix = $config['long_tail_prefix'];
            $config['long_tail'] = $grouped[$prefix] ?? [];
        }

        return $bundleConfigs;
    }

    private function buildOrganizationPlan(): array
    {
        return [
            ['name' => 'PT Andalas Food',   'bundle' => 'oven',     'quotes' => 24],
            ['name' => 'PT Cipta Rasa',     'bundle' => 'oven',     'quotes' => 23],
            ['name' => 'PT Surya Bakery',   'bundle' => 'oven',     'quotes' => 23],
            ['name' => 'PT Nusantara Steel','bundle' => 'conveyor', 'quotes' => 24],
            ['name' => 'PT Prima Plastik',  'bundle' => 'conveyor', 'quotes' => 23],
            ['name' => 'PT Maju Jaya',      'bundle' => 'conveyor', 'quotes' => 23],
            ['name' => 'PT Arjuna Metal',   'bundle' => 'coating',  'quotes' => 30],
            ['name' => 'PT Barokah Logam',  'bundle' => 'coating',  'quotes' => 30],
            ['name' => 'PT Sejahtera Abadi','bundle' => 'testing',  'quotes' => 25],
            ['name' => 'CV Sentosa',        'bundle' => 'testing',  'quotes' => 25],
            ['name' => 'PT Delta Pharma',   'bundle' => 'press',    'quotes' => 25],
            ['name' => 'PT Sinar Elektrik', 'bundle' => 'press',    'quotes' => 25],
        ];
    }

    private function buildAddressBook($organizations): array
    {
        $stateMap = [
            'Padang'    => ['state' => 'Sumatera Barat', 'postcode' => '25112'],
            'Jakarta'   => ['state' => 'DKI Jakarta',    'postcode' => '10210'],
            'Surabaya'  => ['state' => 'Jawa Timur',     'postcode' => '60111'],
            'Bekasi'    => ['state' => 'Jawa Barat',     'postcode' => '17113'],
            'Semarang'  => ['state' => 'Jawa Tengah',    'postcode' => '50242'],
            'Bandung'   => ['state' => 'Jawa Barat',     'postcode' => '40115'],
            'Tangerang' => ['state' => 'Banten',         'postcode' => '15111'],
            'Depok'     => ['state' => 'Jawa Barat',     'postcode' => '16432'],
            'Gresik'    => ['state' => 'Jawa Timur',     'postcode' => '61125'],
            'Sidoarjo'  => ['state' => 'Jawa Timur',     'postcode' => '61215'],
            'Cikarang'  => ['state' => 'Jawa Barat',     'postcode' => '17530'],
            'Karawang'  => ['state' => 'Jawa Barat',     'postcode' => '41315'],
        ];

        $addresses = [];

        foreach ($organizations as $organization) {
            $meta = json_decode($organization->address ?? '{}', true) ?? [];
            $city = $meta['city'] ?? 'Jakarta';
            $industry = $meta['industry'] ?? 'Manufacturing';
            $code = $meta['code'] ?? 'C000';

            $region = $stateMap[$city] ?? ['state' => 'Jawa Barat', 'postcode' => '40111'];

            $addresses[$organization->name] = [
                'company'  => $organization->name,
                'address1' => 'Jl. Industri '.$code.' '.$industry,
                'city'     => $city,
                'state'    => $region['state'],
                'country'  => 'ID',
                'postcode' => $region['postcode'],
            ];
        }

        return $addresses;
    }

    private function fallbackAddress(string $name): array
    {
        return [
            'company'  => $name,
            'address1' => 'Jl. Industri No. 1',
            'city'     => 'Jakarta',
            'state'    => 'DKI Jakarta',
            'country'  => 'ID',
            'postcode' => '10210',
        ];
    }

    private function buildMidCounters(array $bundleConfigs, array $orgPlan, array $longTailTotals): array
    {
        $counters = [];

        foreach ($bundleConfigs as $key => $bundle) {
            $quotes = $this->quotesByBundle($orgPlan, $key);
            $skuList = $bundle['mid'];
            $count = count($skuList);

            if ($count === 0 || $quotes === 0) {
                $counters[$key] = [];

                continue;
            }

            $totalLongTail = $longTailTotals[$key] ?? 0;
            $totalNeeded = $quotes * 2 - $totalLongTail;
            $minimumRequired = $count * 15;

            $totalNeeded = max($totalNeeded, $minimumRequired);
            $totalNeeded = min($totalNeeded, $quotes * 2);

            $base = intdiv($totalNeeded, $count);
            $remainder = $totalNeeded % $count;

            $base = max(15, $base);

            $counters[$key] = array_fill_keys($skuList, $base);

            $availableKeys = array_keys($counters[$key]);

            while ($remainder > 0) {
                $sku = Arr::random($availableKeys);
                $counters[$key][$sku]++;
                $remainder--;
            }
        }

        return $counters;
    }

    private function buildLongTailCounters(array $bundleConfigs, array $orgPlan): array
    {
        $counters = [];

        foreach ($bundleConfigs as $key => $bundle) {
            $quotes = $this->quotesByBundle($orgPlan, $key);
            $skuList = $bundle['long_tail'];
            $count = count($skuList);
            $midCount = count($bundleConfigs[$key]['mid']);

            if ($count === 0 || $quotes === 0) {
                $counters[$key] = [];

                continue;
            }

            $target = (int) round($quotes * 0.55);
            $target = max($target, $count * 3);
            $target = min($target, $quotes);

            $maxLongTail = max(0, $quotes * 2 - ($midCount * 15));

            if ($maxLongTail <= 0) {
                $counters[$key] = [];

                continue;
            }

            $target = min($target, $maxLongTail);

            $maxCandidates = max(1, intdiv($maxLongTail, 3));
            $skuList = array_slice($skuList, 0, min($count, $maxCandidates));
            $count = count($skuList);

            if ($count === 0 || $target === 0) {
                $counters[$key] = [];

                continue;
            }

            $target = max($target, $count * 3);
            $target = min($target, $maxLongTail);

            $base = max(3, intdiv($target, $count));
            $remainder = $target - ($base * $count);

            $counters[$key] = array_fill_keys($skuList, $base);

            $availableKeys = array_keys($counters[$key]);

            while ($remainder > 0) {
                $sku = Arr::random($availableKeys);
                $counters[$key][$sku]++;
                $remainder--;
            }
        }

        return $counters;
    }

    private function quotesByBundle(array $orgPlan, string $bundleKey): int
    {
        return array_reduce($orgPlan, function ($carry, $item) use ($bundleKey) {
            if ($item['bundle'] === $bundleKey) {
                return $carry + $item['quotes'];
            }

            return $carry;
        }, 0);
    }

    private function pickMidItems(string $bundleKey, array &$midCounters, int $count): array
    {
        if ($count <= 0) {
            return [];
        }

        if (empty($midCounters[$bundleKey])) {
            return [];
        }

        $selected = [];

        for ($i = 0; $i < $count; $i++) {
            $choices = array_filter($midCounters[$bundleKey], fn ($remaining) => $remaining > 0);

            if (empty($choices)) {
                break;
            }

            $choices = array_diff_key($choices, array_flip($selected));

            if (empty($choices)) {
                $choices = array_filter($midCounters[$bundleKey], fn ($remaining) => $remaining > 0);
            }

            $sku = Arr::random(array_keys($choices));

            $midCounters[$bundleKey][$sku]--;

            $selected[] = $sku;
        }

        return $selected;
    }

    private function maybePickLongTail(string $bundleKey, array &$longTailCounters, int $quotesRemaining): ?string
    {
        if (empty($longTailCounters[$bundleKey])) {
            return null;
        }

        $totalRemaining = array_sum($longTailCounters[$bundleKey]);

        if ($totalRemaining === 0 || $quotesRemaining <= 0) {
            return null;
        }

        $probability = min(1, $totalRemaining / $quotesRemaining);

        if (mt_rand() / mt_getrandmax() > $probability) {
            return null;
        }

        $choices = array_filter($longTailCounters[$bundleKey], fn ($remaining) => $remaining > 0);

        if (empty($choices)) {
            return null;
        }

        $sku = Arr::random(array_keys($choices));
        $longTailCounters[$bundleKey][$sku]--;

        return $sku;
    }

    private function randomDateIn2025(): Carbon
    {
        $start = Carbon::create(2025, 1, 1, 10, 0, 0);

        // End depends on current year
        $now = Carbon::now();
        if ($now->year === 2025) {
            $end = $now->copy()->setTime(18, 0, 0);
        } else {
            // Cap at 30 Dec 2025 (as requested)
            $end = Carbon::create(2025, 12, 30, 18, 0, 0);
        }

        if ($end->lt($start)) {
            // Fallback: if environment clock is before 2025-01-01
            $end = $start->copy();
        }

        $days = $start->diffInDays($end);

        return (clone $start)->addDays(random_int(0, max(0, $days)));
    }

    private function computeLeadValue(array $items): float
    {
        $sum = array_reduce($items, fn ($carry, $item) => $carry + ($item['total'] ?? 0), 0.0);

        $factor = random_int(105, 130) / 100;
        $value = $sum * $factor;

        return round($value / 10000) * 10000;
    }
}
