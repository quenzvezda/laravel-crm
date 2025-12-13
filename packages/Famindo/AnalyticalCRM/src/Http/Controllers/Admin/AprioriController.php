<?php

namespace Famindo\AnalyticalCRM\Http\Controllers\Admin;

use Carbon\Carbon;
use Famindo\AnalyticalCRM\DataGrids\AprioriRulesDataGrid;
use Famindo\AnalyticalCRM\Models\AprioriRun;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Famindo\AnalyticalCRM\Jobs\RunAprioriJob;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Http\Controllers\Controller;
use Webkul\Core\Traits\PDFHandler;

class AprioriController extends Controller
{
    use PDFHandler;

    public function index(): View|JsonResponse|BinaryFileResponse
    {
        $runs = AprioriRun::ordered()->get();

        $requestedRunId = request()->input('run_id');

        if (! $requestedRunId && $runs->isNotEmpty()) {
            $active = $runs->firstWhere('is_active', true);
            $requestedRunId = $active?->id ?? $runs->first()->id;
        }

        if ($requestedRunId) {
            request()->merge(['run_id' => $requestedRunId]);
        }

        if (request()->ajax() || request()->boolean('export') || request()->has('format')) {
            return datagrid(AprioriRulesDataGrid::class)->process();
        }

        $currentRun = $runs->firstWhere('id', $requestedRunId);

        return view('analyticalcrm::admin.analytics.market-basket.index', [
            'runs'          => $runs,
            'currentRun'    => $currentRun,
            'currentRunId'  => $requestedRunId,
        ]);
    }

    public function run(): RedirectResponse
    {
        $data = request()->validate([
            'from'        => ['nullable', 'date'],
            'to'          => ['nullable', 'date', 'after_or_equal:from'],
            'support'     => ['required', 'numeric', 'min:0', 'max:1'],
            'confidence'  => ['required', 'numeric', 'min:0', 'max:1'],
            'min_items'   => ['nullable', 'integer', 'min:1'],
            'persist'     => ['nullable', 'boolean'],
            'save'        => ['nullable', 'boolean'],
            'label'       => ['nullable', 'string', 'max:255'],
            'activate'    => ['nullable', 'boolean'],
        ]);

        $options = [
            '--support'    => (string) ($data['support'] ?? 0.05),
            '--confidence' => (string) ($data['confidence'] ?? 0.6),
            '--min-items'  => (string) ($data['min_items'] ?? 2),
            '--save'       => true,
        ];

        if (! empty($data['from'])) {
            $options['--from'] = Carbon::parse($data['from'])->toDateString();
        }

        if (! empty($data['to'])) {
            $options['--to'] = Carbon::parse($data['to'])->toDateString();
        }

        if (! empty($data['persist'])) {
            $options['--persist'] = true;
        }

        if (auth()->guard('user')->check()) {
            $options['--created_by'] = (string) auth()->guard('user')->id();
        }

        if (! empty($data['label'])) {
            $options['--label'] = $data['label'];
        }

        if (! empty($data['activate'])) {
            $options['--activate'] = true;
        }

        // Dispatch background job instead of running synchronously
        RunAprioriJob::dispatch($options)->onQueue('analytics');

        session()->flash('success', 'Apriori analysis has been queued. You can continue using the app while it runs.');

        return redirect()->route('admin.analytics.market_basket.index');
    }

    public function activate(AprioriRun $run): RedirectResponse
    {
        $run->activate();

        session()->flash('success', 'Snapshot berhasil dijadikan aktif.');

        return redirect()->route('admin.analytics.market_basket.index', [
            'run_id' => $run->id,
        ]);
    }

    public function exportPdf(int $runId)
    {
        $run = AprioriRun::findOrFail($runId);
        $limit = 100;
        $totalRules = $run->rules()->count();
        $rules = $run->rules()->orderBy('lift', 'desc')->limit($limit)->get();

        $html = view('analyticalcrm::admin.analytics.market-basket.pdf', [
            'run'        => $run,
            'rules'      => $rules,
            'totalRules' => $totalRules,
            'limit'      => $limit,
        ])->render();

        return $this->downloadPDF($html, 'MarketBasketAnalysis_' . $run->id . '_' . date('d-m-Y'));
    }

    public function recommendations(): JsonResponse
    {
        $productIds = array_values(array_filter((array) request('product_ids'), fn ($v) => is_numeric($v)));
        $limit = (int) (request('limit', 8));
        $limit = max(1, min(50, $limit));

        $activeRun = AprioriRun::where('is_active', true)->first();

        if (empty($productIds) || ! $activeRun) {
            return response()->json([
                'meta' => [
                    'run' => $activeRun ? [
                        'id'    => $activeRun->id,
                        'name'  => $activeRun->name,
                        'date'  => optional($activeRun->created_at)->toDateString(),
                    ] : null,
                ],
                'data' => [],
            ]);
        }

        $currentProducts = DB::table('products')
            ->whereIn('id', $productIds)
            ->get(['id', 'sku']);

        $currentCodes = [];
        foreach ($currentProducts as $p) {
            $code = $p->sku && trim($p->sku) !== '' ? (string) $p->sku : ('product:' . (int) $p->id);
            $currentCodes[$code] = true;
        }

        if (empty($currentCodes)) {
            return response()->json([
                'meta' => [ 'run' => [ 'id' => $activeRun->id, 'name' => $activeRun->name, 'date' => optional($activeRun->created_at)->toDateString() ] ],
                'data' => [],
            ]);
        }

        $codes = array_keys($currentCodes);

        $query = DB::table('apriori_rules')->where('run_id', $activeRun->id);
        $query->where(function ($q) use ($codes) {
            foreach ($codes as $code) {
                $q->orWhereJsonContains('lhs', $code);
            }
        });

        $rules = $query->get(['lhs', 'rhs', 'support', 'confidence', 'lift']);

        $recommendations = [];

        foreach ($rules as $rule) {
            $lhs = json_decode($rule->lhs, true) ?: [];
            $isSubset = true;
            foreach ($lhs as $l) {
                if (! isset($currentCodes[$l])) {
                    $isSubset = false;
                    break;
                }
            }
            if (! $isSubset) {
                continue;
            }

            $rhs = json_decode($rule->rhs, true) ?: [];
            foreach ($rhs as $code) {
                if (isset($currentCodes[$code])) {
                    continue;
                }

                $existing = $recommendations[$code] ?? null;
                $cand = [
                    'support'    => (float) $rule->support,
                    'confidence' => (float) $rule->confidence,
                    'lift'       => (float) $rule->lift,
                ];

                if (! $existing || [$cand['lift'], $cand['confidence'], $cand['support']] > [$existing['lift'], $existing['confidence'], $existing['support']]) {
                    $recommendations[$code] = $cand;
                }
            }
        }

        if (empty($recommendations)) {
            return response()->json([
                'meta' => [ 'run' => [ 'id' => $activeRun->id, 'name' => $activeRun->name, 'date' => optional($activeRun->created_at)->toDateString() ] ],
                'data' => [],
            ]);
        }

        $skuCodes = [];
        $idCodes = [];
        foreach (array_keys($recommendations) as $code) {
            if (str_starts_with($code, 'product:')) {
                $id = (int) substr($code, strlen('product:'));
                if ($id > 0) {
                    $idCodes[$id] = $code;
                }
            } else {
                $skuCodes[$code] = $code;
            }
        }

        $productsByCode = [];

        if (! empty($skuCodes)) {
            $rows = DB::table('products')->whereIn('sku', array_keys($skuCodes))->get(['id', 'sku', 'name', 'price']);
            foreach ($rows as $row) {
                $productsByCode[$row->sku] = $row;
            }
        }

        if (! empty($idCodes)) {
            $rows = DB::table('products')->whereIn('id', array_keys($idCodes))->get(['id', 'sku', 'name', 'price']);
            foreach ($rows as $row) {
                $productsByCode['product:' . (int) $row->id] = $row;
            }
        }

        $list = [];
        foreach ($recommendations as $code => $metrics) {
            $p = $productsByCode[$code] ?? null;
            if (! $p) {
                continue;
            }

            $list[] = [
                'product_id' => (int) $p->id,
                'sku'        => (string) ($p->sku ?? ''),
                'name'       => (string) ($p->name ?? $p->sku ?? ('Product #' . $p->id)),
                'price'      => (float) ($p->price ?? 0),
                'metrics'    => $metrics,
            ];
        }

        usort($list, function ($a, $b) {
            return [$b['metrics']['lift'], $b['metrics']['confidence'], $b['metrics']['support']] <=> [$a['metrics']['lift'], $a['metrics']['confidence'], $a['metrics']['support']];
        });

        $list = array_slice($list, 0, $limit);

        return response()->json([
            'meta' => [
                'run' => [
                    'id'   => $activeRun->id,
                    'name' => $activeRun->name,
                    'date' => optional($activeRun->created_at)->toDateString(),
                ],
            ],
            'data' => $list,
        ]);
    }
}
