<x-admin::layouts>
    <x-slot:title>
        @lang('product-category::app.index.title')
    </x-slot>

    <div class="flex flex-col gap-4">
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <x-admin::breadcrumbs name="products.categories" />

                <div class="text-xl font-bold dark:text-white">
                    @lang('product-category::app.index.title')
                </div>
            </div>

            <div class="flex items-center gap-x-2.5">
                <a href="{{ route('admin.products.categories.create') }}" class="primary-button">
                    @lang('product-category::app.index.create-btn')
                </a>
            </div>
        </div>

        <x-admin::datagrid :src="route('admin.products.categories.index')">
            <x-admin::shimmer.datagrid />
        </x-admin::datagrid>
    </div>
</x-admin::layouts>

