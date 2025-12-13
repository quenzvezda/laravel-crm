<x-admin::layouts>
    <x-slot:title>
        Kelola Tanda Tangan
    </x-slot:title>

    <div class="flex-col gap-4">
        {{-- Page Header --}}
        <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
            <div class="flex flex-col gap-2">
                <div class="flex cursor-pointer items-center">
                    <x-admin::breadcrumbs name="settings.signatures" />
                </div>
                <div class="text-xl font-bold dark:text-white">
                    Kelola Tanda Tangan Digital
                </div>
            </div>
        </div>

        <div class="mt-3.5 flex gap-2.5 max-xl:flex-wrap">
            {{-- Left Panel --}}
            <div class="flex flex-1 flex-col gap-2 max-xl:flex-auto">
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        Daftar Tanda Tangan
                    </h3>
                    <div class="table-responsive grid w-full">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-[1px] border-gray-300 dark:border-gray-800">
                                    <th class="p-2 text-left">Nama Tanda Tangan</th>
                                    <th class="p-2 text-left">Nama Pemilik</th>
                                    <th class="p-2 text-center">Pratinjau</th>
                                    <th class="p-2 text-center">Status</th>
                                    <th class="p-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($signatures as $signature)
                                    <tr class="border-b-[1px] border-gray-300 dark:border-gray-800">
                                        <td class="p-2">{{ $signature->name }}</td>
                                        <td class="p-2">{{ $signature->owner_name }}</td>
                                        <td class="p-2 text-center">
                                            <img src="{{ $signature->image_url }}" class="mx-auto h-10 w-auto" style="max-width: 100px;">
                                        </td>
                                        <td class="p-2 text-center">
                                            @if ($signature->is_active)
                                                <span class="label-active">Aktif</span>
                                            @else
                                                <span class="label-inactive">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="p-2 text-right">
                                            <div class="flex justify-end gap-2">
                                                @if (! $signature->is_active)
                                                    <form action="{{ route('admin.settings.signatures.set_active', $signature->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="text-blue-600 hover:underline">Jadikan Aktif</button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.settings.signatures.destroy', $signature->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tanda tangan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-4 text-center">Belum ada tanda tangan yang tersedia.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Right Panel --}}
            <div class="flex w-[360px] max-w-full flex-col gap-2 max-sm:w-full">
                <div class="box-shadow rounded bg-white p-4 dark:bg-gray-900">
                    <h3 class="mb-4 text-base font-semibold text-gray-800 dark:text-white">
                        Tambah Tanda Tangan Baru
                    </h3>
                    <x-admin::form :action="route('admin.settings.signatures.store')" enctype="multipart/form-data">
                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">Nama Tanda Tangan</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control type="text" name="name" rules="required" placeholder="Contoh: Tanda Tangan Direktur" />
                            <x-admin::form.control-group.error control-name="name" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">Nama Pemilik</x-admin::form.control-group.label>
                            <x-admin::form.control-group.control type="text" name="owner_name" rules="required" placeholder="Contoh: Budi Santoso" />
                            <x-admin::form.control-group.error control-name="owner_name" />
                        </x-admin::form.control-group>

                        <x-admin::form.control-group>
                            <x-admin::form.control-group.label class="required">File Gambar</x-admin::form.control-group.label>

                            <x-admin::media.images
                                name="image"
                                :uploaded-images="[]"
                            />

                            <p class="mt-2 text-xs text-gray-600 dark:text-gray-300">
                                Disarankan menggunakan format PNG transparan dengan rasio aspek persegi (kotak) atau persegi panjang mendatar.
                            </p>

                            <x-admin::form.control-group.error control-name="image" />
                        </x-admin::form.control-group>

                        <button type="submit" class="primary-button w-full">Simpan Tanda Tangan</button>
                    </x-admin::form>
                </div>
            </div>
        </div>
    </div>
</x-admin::layouts>
