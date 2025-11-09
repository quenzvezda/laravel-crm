<?php

return [
    'layouts' => [
        'leads'                => 'Prospek',
        'lead-id'              => 'ID Prospek',
    ],

    'leads' => [
        'create' => [
            'title'                => 'Buat Prospek',
            'save-btn'             => 'Simpan sebagai Prospek',

            'basic-details'        => 'Detail Dasar',
            'details'              => 'Detail',
            'lead-details'         => 'Detail Prospek',
            'products'             => 'Produk',
            'persons'              => 'Kontak Individu',
            'organization'         => 'Organisasi',

            'title-control' => [
                'label'            => 'Judul',
                'placeholder'      => 'Judul Prospek',
            ],

            'description-control' => [
                'label'            => 'Deskripsi',
                'placeholder'      => 'Deskripsi Prospek',
            ],

            'lead-value-control' => [
                'label'            => 'Nilai Prospek',
                'placeholder'      => 'Nilai Prospek',
            ],

            'lead-type-control' => [
                'label'            => 'Tipe Prospek',
                'placeholder'      => 'Pilih Tipe',
            ],

            'lead-pipeline-control' => [
                'label'            => 'Pipeline',
                'placeholder'      => 'Pilih Pipeline',
            ],

            'lead-pipeline-stage-control' => [
                'label'            => 'Tahap Pipeline',
                'placeholder'      => 'Pilih Tahap',
            ],

            'lead-source-control' => [
                'label'            => 'Sumber',
                'placeholder'      => 'Pilih Sumber',
            ],

            'user-control' => [
                'label'            => 'Sales Person',
                'placeholder'      => 'Pilih Sales Person',
            ],

            'expected-close-date-control' => [
                'label'            => 'Tanggal Tutup Diharapkan',
            ],

            'tags-control' => [
                'label'            => 'Tag',
                'placeholder'      => 'Pilih Tag',
            ],

            'person-control' => [
                'label'            => 'Kontak Individu',
                'placeholder'      => 'Pilih Kontak Individu',
                'add-new-person'   => 'Tambah kontak baru',
            ],

            'organization-control' => [
                'label'            => 'Organisasi',
                'placeholder'      => 'Pilih Organisasi',
                'add-new-organization' => 'Tambah organisasi baru',
            ],

            'products-control' => [
                'label'            => 'Produk',
                'add-product'      => 'Tambah Produk',

                'quantity-control' => [
                    'label'        => 'Kuantitas',
                    'placeholder'  => 'Kuantitas',
                ],

                'price-control' => [
                    'label'        => 'Harga',
                    'placeholder'  => 'Harga',
                ],

                'amount-control' => [
                    'label'        => 'Jumlah',
                    'placeholder'  => 'Jumlah',
                ],

                'action' => [
                    'label'        => 'Aksi',
                    'delete'       => 'Hapus',
                ],
            ],
        ],

        'edit' => [
            'title'                => 'Edit Prospek',
            'save-btn'             => 'Simpan Prospek',
        ],

        'view' => [
            'title'                => 'Prospek: :title',

            'attributes' => [
                'about'            => 'Tentang',
            ],

            'persons' => [
                'title'            => 'Kontak Individu',
                'add-person'       => 'Tambah Kontak',
            ],

            'organizations' => [
                'title'            => 'Organisasi',
                'add-organization' => 'Tambah Organisasi',
            ],

            'quotes' => [
                'title'            => 'Penawaran',
                'create-btn'       => 'Buat Penawaran',
                'subject'          => 'Subjek',
                'expired-at'       => 'Berakhir Pada',
                'grand-total'      => 'Total Keseluruhan',
                'created-at'       => 'Dibuat Pada',
                'actions'          => 'Aksi',
                'empty'            => 'Belum ada penawaran dibuat.',
            ],

            'products' => [
                'title'            => 'Produk',
                'create-btn'       => 'Tambah Produk',

                'datagrid' => [
                    'product'      => 'Produk',
                    'quantity'     => 'Kuantitas',
                    'price'        => 'Harga',
                    'amount'       => 'Jumlah',
                    'action'       => 'Aksi',
                    'delete'       => 'Hapus',
                ],
            ],

            'activities' => [
                'title'            => 'Aktivitas',
                'create-btn'       => 'Buat Aktivitas',
                'empty'            => 'Belum ada aktivitas dicatat.',
            ],

            'tags' => [
                'title'            => 'Tag',
                'create-success'   => 'Tag berhasil dibuat.',
                'delete-success'   => 'Tag berhasil dihapus.',
            ],
        ],

        'index' => [
            'title'                => 'Prospek',
            'create-btn'           => 'Buat Prospek',
            'create-success'       => 'Prospek berhasil dibuat.',
            'update-success'       => 'Prospek berhasil diperbarui.',
            'delete-success'       => 'Prospek berhasil dihapus.',
            'delete-failed'        => 'Prospek tidak dapat dihapus.',

            'datagrid' => [
                'id'                       => 'ID',
                'title'                    => 'Judul',
                'status'                   => 'Status',
                'stage'                    => 'Tahap',
                'lead-value'               => 'Nilai Prospek',
                'source'                   => 'Sumber',
                'lead-type'                => 'Tipe Prospek',
                'tag'                      => 'Tag',
                'sales-person'             => 'Sales Person',
                'expected-close-date'      => 'Tanggal Tutup Diharapkan',
                'created-at'               => 'Dibuat Pada',
                'updated-at'               => 'Diperbarui Pada',
                'rotten-lead'              => 'Prospek Busuk',
                'delete'                   => 'Hapus',
                'edit'                     => 'Edit',
                'view'                     => 'Lihat',
                'mass-delete'              => 'Hapus Massal',
                'mass-update'              => 'Update Massal',
            ],
        ],
    ],

    'type' => [
        'new-lead'     => 'Prospek Baru',
        'existing-customer' => 'Pelanggan Existing',
    ],

    'pipeline-stages' => [
        'new'          => 'Baru',
        'follow-up'    => 'Tindak Lanjut',
        'prospect'     => 'Prospek',
        'negotiation'  => 'Negosiasi',
        'won'          => 'Menang',
        'lost'         => 'Kalah',
    ],

    'sources' => [
        'email'        => 'Email',
        'web'          => 'Web',
        'phone'        => 'Telepon',
        'contact-form' => 'Form Kontak',
        'advertisement' => 'Iklan',
        'organic-search' => 'Pencarian Organik',
        'social-media' => 'Media Sosial',
        'referral'     => 'Rujukan',
        'partner'      => 'Partner',
        'trade-show'   => 'Pameran Dagang',
    ],
];
