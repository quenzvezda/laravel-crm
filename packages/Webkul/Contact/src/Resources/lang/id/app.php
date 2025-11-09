<?php

return [
    'persons' => [
        'create' => [
            'title'                => 'Buat Kontak Individu',
            'save-btn'             => 'Simpan Kontak',

            'general'              => 'Umum',
            'contact-info'         => 'Informasi Kontak',
            'additional-info'      => 'Informasi Tambahan',

            'name-control' => [
                'label'            => 'Nama',
                'placeholder'      => 'Nama Kontak',
            ],

            'job-title-control' => [
                'label'            => 'Jabatan',
                'placeholder'      => 'Jabatan',
            ],

            'organization-control' => [
                'label'            => 'Organisasi',
                'placeholder'      => 'Pilih Organisasi',
            ],

            'emails-control' => [
                'label'            => 'Email',
                'placeholder'      => 'Email',
                'add-more'         => 'Tambah Email',
            ],

            'contact-numbers-control' => [
                'label'            => 'Nomor Kontak',
                'placeholder'      => 'Nomor Kontak',
                'add-more'         => 'Tambah Nomor',
            ],

            'user-control' => [
                'label'            => 'Sales Owner',
                'placeholder'      => 'Pilih Sales Owner',
            ],
        ],

        'edit' => [
            'title'                => 'Edit Kontak Individu',
            'save-btn'             => 'Simpan Kontak',
        ],

        'view' => [
            'title'                => 'Kontak: :name',

            'about' => [
                'title'            => 'Tentang',
            ],

            'leads' => [
                'title'            => 'Prospek',
                'create-btn'       => 'Buat Prospek',
                'empty'            => 'Belum ada prospek untuk kontak ini.',
            ],

            'quotes' => [
                'title'            => 'Penawaran',
                'create-btn'       => 'Buat Penawaran',
                'empty'            => 'Belum ada penawaran untuk kontak ini.',
            ],

            'activities' => [
                'title'            => 'Aktivitas',
                'empty'            => 'Belum ada aktivitas dicatat.',
            ],
        ],

        'index' => [
            'title'                => 'Kontak Individu',
            'create-btn'           => 'Buat Kontak',
            'create-success'       => 'Kontak berhasil dibuat.',
            'update-success'       => 'Kontak berhasil diperbarui.',
            'delete-success'       => 'Kontak berhasil dihapus.',
            'delete-failed'        => 'Kontak tidak dapat dihapus.',

            'datagrid' => [
                'id'                   => 'ID',
                'name'                 => 'Nama',
                'emails'               => 'Email',
                'contact-numbers'      => 'Nomor Kontak',
                'organization'         => 'Organisasi',
                'created-at'           => 'Dibuat Pada',
                'updated-at'           => 'Diperbarui Pada',
                'delete'               => 'Hapus',
                'edit'                 => 'Edit',
                'view'                 => 'Lihat',
                'mass-delete'          => 'Hapus Massal',
                'mass-update'          => 'Update Massal',
            ],
        ],
    ],

    'organizations' => [
        'create' => [
            'title'                => 'Buat Organisasi',
            'save-btn'             => 'Simpan Organisasi',

            'general'              => 'Umum',
            'address-info'         => 'Informasi Alamat',
            'contact-info'         => 'Informasi Kontak',

            'name-control' => [
                'label'            => 'Nama',
                'placeholder'      => 'Nama Organisasi',
            ],

            'address-control' => [
                'label'            => 'Alamat',
                'placeholder'      => 'Alamat Organisasi',
            ],

            'user-control' => [
                'label'            => 'Sales Owner',
                'placeholder'      => 'Pilih Sales Owner',
            ],
        ],

        'edit' => [
            'title'                => 'Edit Organisasi',
            'save-btn'             => 'Simpan Organisasi',
        ],

        'view' => [
            'title'                => 'Organisasi: :name',

            'about' => [
                'title'            => 'Tentang',
            ],

            'persons' => [
                'title'            => 'Kontak Individu',
                'create-btn'       => 'Tambah Kontak',
                'empty'            => 'Belum ada kontak untuk organisasi ini.',
            ],

            'leads' => [
                'title'            => 'Prospek',
                'create-btn'       => 'Buat Prospek',
                'empty'            => 'Belum ada prospek untuk organisasi ini.',
            ],

            'quotes' => [
                'title'            => 'Penawaran',
                'create-btn'       => 'Buat Penawaran',
                'empty'            => 'Belum ada penawaran untuk organisasi ini.',
            ],

            'activities' => [
                'title'            => 'Aktivitas',
                'empty'            => 'Belum ada aktivitas dicatat.',
            ],
        ],

        'index' => [
            'title'                => 'Organisasi',
            'create-btn'           => 'Buat Organisasi',
            'create-success'       => 'Organisasi berhasil dibuat.',
            'update-success'       => 'Organisasi berhasil diperbarui.',
            'delete-success'       => 'Organisasi berhasil dihapus.',
            'delete-failed'        => 'Organisasi tidak dapat dihapus.',

            'datagrid' => [
                'id'                   => 'ID',
                'name'                 => 'Nama',
                'persons'              => 'Kontak Individu',
                'address'              => 'Alamat',
                'created-at'           => 'Dibuat Pada',
                'updated-at'           => 'Diperbarui Pada',
                'delete'               => 'Hapus',
                'edit'                 => 'Edit',
                'view'                 => 'Lihat',
                'mass-delete'          => 'Hapus Massal',
                'mass-update'          => 'Update Massal',
            ],
        ],
    ],
];
