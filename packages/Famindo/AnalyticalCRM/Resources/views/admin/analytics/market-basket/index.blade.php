<x-admin::layouts>
    <x-slot:title>
        Market Basket (Apriori)
    </x-slot>

    @php
        $runs = $runs ?? collect();
        $currentRunId = $currentRunId ?? null;
        $currentRun = $currentRun ?? null;
        $hasSnapshots = $runs->isNotEmpty();
        $gridSrc = $hasSnapshots && $currentRunId
            ? route('admin.analytics.market_basket.index', ['run_id' => $currentRunId])
            : route('admin.analytics.market_basket.index');
        $exportBase = [
            'export' => 1,
        ];
        if ($currentRunId) {
            $exportBase['run_id'] = $currentRunId;
        }
    @endphp

    @pushOnce('styles')
        <style>
            .sku-tooltip-card {
                position: fixed;
                z-index: 60;
                pointer-events: none;
                background-color: rgba(17, 24, 39, 0.95);
                color: #f9fafb;
                padding: 0.75rem 0.85rem;
                border-radius: 0.5rem;
                box-shadow: 0 18px 38px rgba(15, 23, 42, 0.35);
                max-width: 20rem;
                display: none;
                font-size: 0.8125rem;
                line-height: 1.2rem;
            }

            .sku-tooltip-card.is-visible {
                display: block;
            }

            .sku-tooltip-card__title {
                font-weight: 600;
                font-size: 0.875rem;
                margin-bottom: 0.3rem;
            }

            .sku-tooltip-card__description {
                font-size: 0.75rem;
                color: rgba(226, 232, 240, 0.9);
                margin-bottom: 0.3rem;
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }

            .sku-tooltip-card__price {
                font-weight: 600;
                font-size: 0.8125rem;
                color: #facc15;
            }
        </style>
    @endpushOnce

    @pushOnce('scripts')
        <script type="module">
            document.addEventListener('DOMContentLoaded', () => {
                const tooltip = document.createElement('div');
                tooltip.className = 'sku-tooltip-card';
                document.body.appendChild(tooltip);

                const formatCurrency = (value) => {
                    if (! value) {
                        return '-';
                    }

                    const number = Number(value);

                    if (Number.isNaN(number)) {
                        return value;
                    }

                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        maximumFractionDigits: 0,
                    }).format(number);
                };

                let activeEl = null;

                const hideTooltip = () => {
                    tooltip.classList.remove('is-visible');
                    activeEl = null;
                };

                const renderTooltip = (el) => {
                    const name = el.dataset.name || el.dataset.sku || 'Produk';
                    const description = el.dataset.description || '—';
                    const price = formatCurrency(el.dataset.price);

                    tooltip.innerHTML = `
                        <div class="sku-tooltip-card__title">${name}</div>
                        <div class="sku-tooltip-card__description">${description}</div>
                        <div class="sku-tooltip-card__price">${price}</div>
                    `;
                };

                const positionTooltip = (event) => {
                    const padding = 16;
                    const rect = tooltip.getBoundingClientRect();

                    let x = event.clientX + padding;
                    let y = event.clientY + padding;

                    if (x + rect.width > window.innerWidth - padding) {
                        x = event.clientX - rect.width - padding;
                    }

                    if (y + rect.height > window.innerHeight - padding) {
                        y = event.clientY - rect.height - padding;
                    }

                    tooltip.style.left = `${Math.max(padding, x)}px`;
                    tooltip.style.top = `${Math.max(padding, y)}px`;
                };

                document.addEventListener('mouseover', (event) => {
                    const target = event.target.closest('.sku-tooltip');

                    if (target) {
                        activeEl = target;
                        renderTooltip(target);
                        tooltip.classList.add('is-visible');
                        positionTooltip(event);
                    } else if (! event.target.closest('.sku-tooltip-card')) {
                        hideTooltip();
                    }
                });

                document.addEventListener('mousemove', (event) => {
                    if (! activeEl) {
                        return;
                    }

                    positionTooltip(event);
                });

                document.addEventListener('mouseout', (event) => {
                    if (! activeEl) {
                        return;
                    }

                if (! event.relatedTarget || ! event.relatedTarget.closest('.sku-tooltip')) {
                        hideTooltip();
                    }
                });

                window.addEventListener('scroll', hideTooltip, true);
            });
        </script>
    @endpushOnce

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="analytics.market_basket" />

                <div class="text-xl font-bold dark:text-white">
                    Market Basket (Apriori)
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                @if ($hasSnapshots && $currentRunId)
                    <a
                        href="{{ route('admin.analytics.market_basket.index', array_merge($exportBase, ['format' => 'csv'])) }}"
                        class="secondary-button"
                    >
                        Ekspor Rekap (CSV)
                    </a>

                    <a
                        href="{{ route('admin.analytics.market_basket.index', array_merge($exportBase, ['format' => 'xlsx'])) }}"
                        class="secondary-button"
                    >
                        Ekspor Rekap (XLSX)
                    </a>

                    <a
                        href="{{ route('admin.analytics.market_basket.export_pdf', $currentRunId) }}"
                        target="_blank"
                        class="secondary-button"
                    >
                        Ekspor Rekap (PDF)
                    </a>
                @else
                    <span class="secondary-button cursor-not-allowed opacity-50">
                        Ekspor Rekap (CSV)
                    </span>

                    <span class="secondary-button cursor-not-allowed opacity-50">
                        Ekspor Rekap (XLSX)
                    </span>

                    <span class="secondary-button cursor-not-allowed opacity-50">
                        Ekspor Rekap (PDF)
                    </span>
                @endif
            </div>
        </div>

        <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="flex flex-col gap-2 lg:w-1/2">
                    <div class="text-base font-semibold">
                        Versi Rekap
                    </div>

                    @if ($hasSnapshots)
                        <form
                            method="GET"
                            action="{{ route('admin.analytics.market_basket.index') }}"
                            class="flex flex-col gap-3 sm:flex-row sm:items-end sm:gap-4"
                        >
                            <div class="flex flex-col">
                                <label class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Pilih Rekap
                                </label>

                                <select
                                    name="run_id"
                                    onchange="this.form.submit()"
                                    class="mt-1 rounded border border-gray-300 bg-white px-3 py-2 text-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
                                >
                                    @foreach ($runs as $run)
                                        <option value="{{ $run->id }}" @selected($run->id === $currentRunId)>
                                            {{ $run->name ?? ('Snapshot #' . $run->id) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($currentRun)
                                <div class="flex items-center gap-3">
                                    @if ($currentRun->is_active)
                                        <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-green-800 dark:bg-green-900/30 dark:text-green-200">
                                            Aktif
                                        </span>
                                    @else
                                        <x-admin::form :action="route('admin.analytics.market_basket.activate', $currentRun->id)" method="POST">
                                            @csrf
                                            <button type="submit" class="secondary-button">
                                                Aktifkan
                                            </button>
                                        </x-admin::form>
                                    @endif
                                </div>
                            @endif
                        </form>
                    @else
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Belum ada snapshot Apriori yang tersimpan. Jalankan analisis untuk membuat versi pertama.
                        </p>
                    @endif
                </div>

                @if ($currentRun)
                    <div class="grid gap-4 sm:grid-cols-2 lg:w-1/2">
                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Periode</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ optional($currentRun->period_start)->format('Y-m-d') ?? '—' }}
                                –
                                {{ optional($currentRun->period_end)->format('Y-m-d') ?? '—' }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Support / Confidence</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ number_format((float) $currentRun->support, 3) }}
                                /
                                {{ number_format((float) $currentRun->confidence, 3) }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Min Items</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $currentRun->min_items }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Transaksi / Rules</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $currentRun->transactions_count }} / {{ $currentRun->rules_count }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Dibuat Pada</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ optional($currentRun->created_at)->format('Y-m-d H:i') }}
                            </div>
                        </div>

                        <div>
                            <div class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">Status</div>
                            <div class="text-sm capitalize text-gray-900 dark:text-gray-100">
                                {{ $currentRun->status }}
                                @if ($currentRun->status === 'failed' && $currentRun->error_message)
                                    <div class="text-xs text-red-500">
                                        {{ \Illuminate\Support\Str::limit($currentRun->error_message, 80) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900 lg:col-span-1">
                <div class="mb-2 text-base font-semibold">Jalankan Analisis Baru</div>

                <x-admin::form :action="route('admin.analytics.market_basket.run')" method="POST">
                    @csrf

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <x-admin::form.control-group.label>
                                Dari
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control type="date" name="from" />
                        </div>

                        <div>
                            <x-admin::form.control-group.label>
                                Sampai
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control type="date" name="to" />
                        </div>
                    </div>

                    <div class="mt-3 grid grid-cols-3 gap-3">
                        <div>
                            <x-admin::form.control-group.label>
                                Support
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control type="number" name="support" step="0.01" min="0" max="1" value="0.05" />
                        </div>

                        <div>
                            <x-admin::form.control-group.label>
                                Confidence
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control type="number" name="confidence" step="0.01" min="0" max="1" value="0.6" />
                        </div>

                        <div>
                            <x-admin::form.control-group.label>
                                Min. Item
                            </x-admin::form.control-group.label>
                            <x-admin::form.control-group.control type="number" name="min_items" min="1" value="2" />
                        </div>
                    </div>

                    <div class="mt-3">
                        <x-admin::form.control-group.label>
                            Nama Label (Snapshot)
                        </x-admin::form.control-group.label>
                        <x-admin::form.control-group.control
                            type="text"
                            name="label"
                            placeholder="Contoh: Q1 2025 - 90 Hari"
                        />
                    </div>

                    <div class="mt-3 flex flex-col gap-2">
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="persist" value="1" class="rounded" />
                            <span>Simpan Data Transaksi</span>
                        </label>

                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="activate" value="1" class="rounded" />
                            <span>Jadikan snapshot aktif setelah selesai</span>
                        </label>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="primary-button">Proses &amp; Simpan Rules</button>
                    </div>
                </x-admin::form>
            </div>

            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-0 dark:border-gray-800 dark:bg-gray-900 lg:col-span-2">
                <x-admin::datagrid src="{{ $gridSrc }}">
                    <x-admin::shimmer.datagrid />
                </x-admin::datagrid>
            </div>
        </div>
    </div>
</x-admin::layouts>
