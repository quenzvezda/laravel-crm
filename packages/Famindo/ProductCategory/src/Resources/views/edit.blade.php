<x-admin::layouts>
    <x-slot:title>
        Edit Product Category
    </x-slot>

    <x-admin::form :action="route('admin.products.categories.update', $category->id)" method="PUT">
        <div class="box-shadow rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            <div class="text-xl font-bold dark:text-white mb-4">Edit Category</div>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">Code</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="code" :value="$category->code" rules="required"/>
                <x-admin::form.control-group.error control-name="code" />
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label class="required">Name</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="text" name="name" :value="$category->name" rules="required"/>
                <x-admin::form.control-group.error control-name="name" />
            </x-admin::form.control-group>

            <x-admin::form.control-group>
                <x-admin::form.control-group.label>Description</x-admin::form.control-group.label>
                <x-admin::form.control-group.control type="textarea" name="description">{{ $category->description }}</x-admin::form.control-group.control>
            </x-admin::form.control-group>

            <div class="mt-4">
                <button type="submit" class="primary-button">Update</button>
                <a href="{{ route('admin.products.categories.index') }}" class="secondary-button">Cancel</a>
            </div>
        </div>
    </x-admin::form>
</x-admin::layouts>

