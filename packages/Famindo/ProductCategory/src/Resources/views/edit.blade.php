<x-admin::layouts>
    <x-slot:title>
        @lang('product-category::app.edit.title')
    </x-slot>

    <x-admin::form :action="route('admin.products.categories.update', $category->id)" method="PUT">
        <div class="flex flex-col gap-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                <div class="flex flex-col gap-2">
                    <x-admin::breadcrumbs name="products.categories.edit" :entity="$category" />

                    <div class="text-xl font-bold dark:text-white">
                        @lang('product-category::app.edit.title')
                    </div>
                </div>

                <div class="flex items-center gap-x-2.5">
                    <button type="submit" class="primary-button">
                        @lang('product-category::app.edit.save-btn')
                    </button>
                </div>
            </div>

            <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex flex-col gap-4">
                    <div class="flex flex-col gap-1">
                        <p class="text-base font-semibold text-gray-800 dark:text-white">
                            @lang('product-category::app.common.general')
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 max-lg:grid-cols-1">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('product-category::app.common.code')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="code"
                                :value="old('code', $category->code)"
                                rules="required"
                            />

                            <x-admin::form.control-group.error control-name="code" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">
                                @lang('product-category::app.common.name')
                            </x-admin::form.control-group.label>

                            <x-admin::form.control-group.control
                                type="text"
                                name="name"
                                :value="old('name', $category->name)"
                                rules="required"
                            />

                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>
                    </div>

                    <x-admin::form.control-group>
                        <x-admin::form.control-group.label>
                            @lang('product-category::app.common.description')
                        </x-admin::form.control-group.label>

                        <x-admin::form.control-group.control
                            type="textarea"
                            name="description"
                            :value="old('description', $category->description)"
                            rows="5"
                        />
                    </x-admin::form.control-group>
                </div>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>
