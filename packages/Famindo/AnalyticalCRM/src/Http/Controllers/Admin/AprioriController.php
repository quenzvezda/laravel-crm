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
use Webkul\Admin\Http\Controllers\Controller;

class AprioriController extends Controller
{
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
}
