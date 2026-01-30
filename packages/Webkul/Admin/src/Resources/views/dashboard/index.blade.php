<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.dashboard.index.title')
    </x-slot>

    <!-- Head Details Section -->
    {!! view_render_event('admin.dashboard.index.header.before') !!}

    <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
        {!! view_render_event('admin.dashboard.index.header.left.before') !!}

        <div class="grid gap-1.5">
            <p class="text-2xl font-semibold dark:text-white">
                @lang('admin::app.dashboard.index.title')
            </p>
        </div>

        {!! view_render_event('admin.dashboard.index.header.left.after') !!}

        <!-- Actions -->
        {!! view_render_event('admin.dashboard.index.header.right.before') !!}

        <v-dashboard-filters>
            <!-- Shimmer -->
            <div class="flex gap-1.5">
                <div class="light-shimmer-bg dark:shimmer h-[39px] w-[140px] rounded-md"></div>
                <div class="light-shimmer-bg dark:shimmer h-[39px] w-[140px] rounded-md"></div>
            </div>
        </v-dashboard-filters>

        {!! view_render_event('admin.dashboard.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.dashboard.index.header.after') !!}

    <!-- Body Component -->
    {!! view_render_event('admin.dashboard.index.content.before') !!}

    <div class="mt-3.5 flex gap-4 max-xl:flex-wrap">
        <!-- Left Section -->
        {!! view_render_event('admin.dashboard.index.content.left.before') !!}

        <div class="flex flex-1 flex-col gap-4 max-xl:flex-auto">
            <!-- Revenue Stats -->
            @include('admin::dashboard.index.revenue')

            <!-- Over All Stats -->
            @include('admin::dashboard.index.over-all')

            <!-- Total Leads Stats -->
            @include('admin::dashboard.index.total-leads')

            <div class="flex gap-4 max-lg:flex-wrap">
                <!-- Total Products -->
                @include('admin::dashboard.index.top-selling-products')

                <!-- Total Persons -->
                @include('admin::dashboard.index.top-persons')
            </div>
        </div>

        {!! view_render_event('admin.dashboard.index.content.left.after') !!}

        <!-- Right Section -->
        {!! view_render_event('admin.dashboard.index.content.right.before') !!}

        <div class="flex w-[378px] max-w-full flex-col gap-4 max-sm:w-full">
            <!-- Revenue by Types -->
            @include('admin::dashboard.index.open-leads-by-states')

            <!-- Revenue by Sources -->
            @include('admin::dashboard.index.revenue-by-sources')

            <!-- Revenue by Types -->
            @include('admin::dashboard.index.revenue-by-types')
        </div>

        {!! view_render_event('admin.dashboard.index.content.left.after') !!}
    </div>

    @if (bouncer()->hasPermission('dashboard.apriori_simulation'))
        <div class="mt-4">
            <v-apriori-simulation></v-apriori-simulation>
        </div>
    @endif

    {!! view_render_event('admin.dashboard.index.content.after') !!}

    @pushOnce('scripts')

        <script
            type="module"
            src="{{ vite()->asset('js/chart.js') }}"
        >
        </script>

        <script
            type="module"
            src="https://cdn.jsdelivr.net/npm/chartjs-chart-funnel@4.2.1/build/index.umd.min.js"
        >
        </script>

        <script
            type="text/x-template"
            id="v-dashboard-filters-template"
        >
            {!! view_render_event('admin.dashboard.index.date_filters.before') !!}

            <div class="flex gap-1.5">
                <x-admin::flat-picker.date
                    class="!w-[140px]"
                    ::allow-input="false"
                    ::max-date="filters.end"
                >
                    <input
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                        v-model="filters.start"
                        placeholder="@lang('admin::app.dashboard.index.start-date')"
                    />
                </x-admin::flat-picker.date>

                <x-admin::flat-picker.date
                    class="!w-[140px]"
                    ::allow-input="false"
                    ::max-date="filters.end"
                >
                    <input
                        class="flex min-h-[39px] w-full rounded-md border px-3 py-2 text-sm text-gray-600 transition-all hover:border-gray-400 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:border-gray-400"
                        v-model="filters.end"
                        placeholder="@lang('admin::app.dashboard.index.end-date')"
                    />
                </x-admin::flat-picker.date>
            </div>

            {!! view_render_event('admin.dashboard.index.date_filters.after') !!}
        </script>

        <script type="module">
            app.component('v-dashboard-filters', {
                template: '#v-dashboard-filters-template',

                data() {
                    return {
                        filters: {
                            channel: '',

                            start: "{{ $startDate->format('Y-m-d') }}",

                            end: "{{ $endDate->format('Y-m-d') }}",
                        }
                    }
                },

                watch: {
                    filters: {
                        handler() {
                            this.$emitter.emit('reporting-filter-updated', this.filters);
                        },

                        deep: true
                    }
                },
            });
        </script>

        <!-- Apriori Simulation Component -->
        <script type="text/x-template" id="v-apriori-simulation-template">
            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <p class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                    Simulasi Rekomendasi Produk (Apriori)
                </p>

                <div class="grid grid-cols-3 gap-8">
                    <!-- Selected Items -->
                    <div class="col-span-1">
                        <div class="mb-4 flex items-center justify-between">
                            <div class="text-sm font-medium text-gray-700 dark:text-gray-200">Keranjang Simulasi</div>
                            <span
                                class="cursor-pointer text-sm text-brandColor"
                                @click="addProduct"
                            >
                                + Tambah Item
                            </span>
                        </div>

                        <div v-if="selectedProducts.length > 0" class="flex flex-col gap-2">
                            <div v-for="(product, index) in selectedProducts" :key="index" class="flex items-center gap-2">
                                <x-admin::form.control-group class="!mb-0 w-full" style="z-index: 10;">
                                    <x-admin::lookup
                                        ::src="productSearchSrc"
                                        ::name="'products[' + index + '][id]'"
                                        ::value="product"
                                        :preload="true"
                                        placeholder="Cari produk..."
                                        @on-selected="(selectedProduct) => updateProduct(index, selectedProduct)"
                                    />
                                </x-admin::form.control-group>

                                <span
                                    class="icon-delete cursor-pointer text-lg text-gray-600 hover:text-red-500"
                                    @click="removeProduct(index)"
                                ></span>
                            </div>
                        </div>

                        <div v-else class="mt-4 text-center text-sm text-gray-500">
                            Keranjang masih kosong. Klik "+ Tambah Item" untuk memulai.
                        </div>
                    </div>

                    <!-- Recommendations -->
                    <div class="col-span-2">
                        <div class="mb-2 text-sm font-medium text-gray-700 dark:text-gray-200">Produk yang Mungkin Dibeli Bersama</div>

                        <div v-if="recommendations.length === 0 && !loading" class="text-sm text-gray-500">
                            Pilih produk di keranjang untuk melihat rekomendasi.
                        </div>

                        <div v-if="loading" class="text-sm text-gray-500">
                            Mencari rekomendasi...
                        </div>

                        <ul v-else class="grid grid-cols-1 gap-4">
                            <li v-for="rec in recommendations" :key="rec.product_id" class="flex items-center gap-3 rounded-md border border-gray-200 p-3 dark:border-gray-800">
                                <div class="flex min-w-0 flex-1 flex-col">
                                    <div class="font-semibold text-gray-800 dark:text-white">@{{ rec.name }}</div>
                                    <div class="text-xs text-gray-500">
                                        conf: @{{ (rec.metrics.confidence*100).toFixed(0) }}% |
                                        lift: @{{ rec.metrics.lift.toFixed(2) }}
                                    </div>
                                </div>
                                <button
                                    type="button"
                                    class="secondary-button shrink-0"
                                    @click="addRecommendedProduct(rec)"
                                >
                                    + Tambah
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </script>

        <script type="module">
            app.component('v-apriori-simulation', {
                template: '#v-apriori-simulation-template',

                data() {
                    return {
                        selectedProducts: [],
                        recommendations: [],
                        loading: false,
                        lastRequestKey: '',
                        productSearchSrc: "{{ route('admin.products.search') }}",
                    };
                },

                watch: {
                    selectedProducts: {
                        handler() {
                            const ids = this.selectedProducts.map(p => p.id).filter(Boolean).sort();
                            const requestKey = ids.join(',');

                            if (requestKey && requestKey !== this.lastRequestKey) {
                                this.lastRequestKey = requestKey;
                                this.fetchRecommendations(ids);
                            } else if (!requestKey) {
                                this.recommendations = [];
                                this.lastRequestKey = '';
                            }
                        },
                        deep: true
                    }
                },

                methods: {
                    addProduct() {
                        this.selectedProducts.push({ id: null, name: '' });
                    },

                    updateProduct(index, product) {
                        if (product) {
                            this.selectedProducts[index] = product;
                        } else {
                            this.selectedProducts.splice(index, 1);
                        }
                    },

                    removeProduct(index) {
                        this.selectedProducts.splice(index, 1);
                    },

                    addRecommendedProduct(rec) {
                        const product = { id: rec.product_id, name: rec.name, price: rec.price };

                        if (!this.selectedProducts.some(p => p.id === product.id)) {
                            // Find an empty slot or add a new one
                            const emptyIndex = this.selectedProducts.findIndex(p => !p.id);
                            if (emptyIndex !== -1) {
                                this.selectedProducts[emptyIndex] = product;
                            } else {
                                this.selectedProducts.push(product);
                            }
                        }
                    },

                    async fetchRecommendations(ids) {
                        this.loading = true;
                        this.recommendations = [];
                        try {
                            const { data } = await this.$axios.get("{{ route('admin.analytics.recommendations') }}", {
                                params: { product_ids: ids, limit: 6 },
                            });

                            const existingIds = new Set(this.selectedProducts.map(p => p.id));
                            this.recommendations = (data.data || []).filter(rec => !existingIds.has(rec.product_id));

                        } catch (e) {
                            console.error("Failed to fetch recommendations:", e);
                        } finally {
                            this.loading = false;
                        }
                    },
                }
            });
        </script>
    @endPushOnce
</x-admin::layouts>
