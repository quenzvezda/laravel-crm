<x-admin::layouts>
    <x-slot:title>
        Product Categories
    </x-slot>

    <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
        <div class="text-xl font-bold dark:text-white">Product Categories</div>
        <a href="{{ route('admin.products.categories.create') }}" class="primary-button">Add Category</a>
    </div>

    <div class="box-shadow mt-4 rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-600 dark:text-gray-300">
                    <th class="p-2">Code</th>
                    <th class="p-2">Name</th>
                    <th class="p-2">Description</th>
                    <th class="p-2 w-32">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $cat)
                    <tr class="border-t border-gray-200 dark:border-gray-800">
                        <td class="p-2">{{ $cat->code }}</td>
                        <td class="p-2">{{ $cat->name }}</td>
                        <td class="p-2">{{ $cat->description }}</td>
                        <td class="p-2">
                            <a href="{{ route('admin.products.categories.edit', $cat->id) }}" class="secondary-button">Edit</a>
                            <button class="danger-button" onclick="deleteCategory({{ $cat->id }})">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="p-2" colspan="4">No categories</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>
    </div>

    @pushOnce('scripts')
        <script type="module">
            window.deleteCategory = function(id) {
                if (! confirm('Delete this category?')) return;
                fetch(`{{ route('admin.products.categories.delete', ':id') }}`.replace(':id', id), {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(() => window.location.reload());
            }
        </script>
    @endPushOnce
</x-admin::layouts>

