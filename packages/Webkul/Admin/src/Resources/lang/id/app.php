<?php

return [
    'acl' => [
        'create'                => 'Buat',
        'delete'                => 'Hapus',
        'edit'                  => 'Edit',
        'email'                 => 'Email',
        'export'                => 'Ekspor',
        'mass-delete'           => 'Hapus Massal',
        'mass-update'           => 'Update Massal',
        'print'                 => 'Cetak',
        'view'                  => 'Lihat',

        'leads' => [
            'create'           => 'Buat Prospek',
            'delete'           => 'Hapus Prospek',
            'edit'             => 'Edit Prospek',
            'mass-delete'      => 'Hapus Massal Prospek',
            'mass-update'      => 'Update Massal Prospek',
            'view'             => 'Lihat Prospek',
        ],

        'quotes' => [
            'create'           => 'Buat Penawaran',
            'delete'           => 'Hapus Penawaran',
            'edit'             => 'Edit Penawaran',
            'mass-delete'      => 'Hapus Massal Penawaran',
            'mass-update'      => 'Update Massal Penawaran',
            'view'             => 'Lihat Penawaran',
            'print'            => 'Cetak Penawaran',
        ],

        'contacts' => [
            'persons' => [
                'create'       => 'Buat Kontak Individu',
                'delete'       => 'Hapus Kontak Individu',
                'edit'         => 'Edit Kontak Individu',
                'mass-delete'  => 'Hapus Massal Kontak Individu',
                'mass-update'  => 'Update Massal Kontak Individu',
                'view'         => 'Lihat Kontak Individu',
            ],

            'organizations' => [
                'create'       => 'Buat Organisasi',
                'delete'       => 'Hapus Organisasi',
                'edit'         => 'Edit Organisasi',
                'mass-delete'  => 'Hapus Massal Organisasi',
                'mass-update'  => 'Update Massal Organisasi',
                'view'         => 'Lihat Organisasi',
            ],
        ],

        'products' => [
            'create'           => 'Buat Produk',
            'delete'           => 'Hapus Produk',
            'edit'             => 'Edit Produk',
            'mass-delete'      => 'Hapus Massal Produk',
            'mass-update'      => 'Update Massal Produk',
            'view'             => 'Lihat Produk',
        ],

        'analytics' => [
            'view'             => 'Lihat Analitik',
            'create'           => 'Buat Analisis',
            'export'           => 'Ekspor Analisis',
        ],
    ],

    'components' => [
        'activities' => [
            'actions' => [
                'file' => [
                    'downloaded'        => 'File diunduh.',
                ],

                'note' => [
                    'created'           => 'Catatan dibuat.',
                ],
            ],

            'index' => [
                'from'                  => 'Dari',
                'title'                 => 'Aktivitas',
                'no-activity-found'     => 'Tidak ada aktivitas ditemukan.',
            ],
        ],

        'datagrid' => [
            'actions' => [
                'delete'  => 'Hapus',
                'edit'    => 'Edit',
                'view'    => 'Lihat',
            ],

            'filters' => [
                'title'                 => 'Terapkan Filter',
                'apply-title'           => 'Terapkan Filter',
                'clear-title'           => 'Bersihkan Filter',
                'from'                  => 'Dari',
                'to'                    => 'Ke',
                'date-format'           => 'yyyy-mm-dd',
                'custom'                => 'Kustom',
                'range-separator'       => 'ke',
            ],

            'search' => [
                'title'                 => 'Cari',
            ],

            'mass-actions' => [
                'select-action'         => 'Pilih Aksi',
                'mass-delete-confirm'   => 'Apakah Anda yakin ingin menghapus :resource yang dipilih?',
                'mass-update-status'    => 'Apakah Anda yakin ingin memperbarui status :resource yang dipilih?',
                'delete'                => 'Apakah Anda yakin ingin melakukan aksi ini?',
                'edit'                  => 'Apakah Anda yakin ingin mengedit :resource ini?',
            ],

            'zero-index'            => 'Tidak ada :resource ditemukan',
        ],

        'layouts' => [
            'header' => [
                'title'                 => 'Krayin',
                'dropdown-toggle'       => 'Toggle Dropdown',

                'profile' => [
                    'view-profile'      => 'Lihat Profil',
                    'edit-profile'      => 'Edit Profil',
                    'logout'            => 'Keluar',
                ],

                'search' => [
                    'title'             => 'Cari',
                ],
            ],

            'sidebar' => [
                'dashboard'             => 'Dashboard',
                'leads'                 => 'Prospek',
                'quotes'                => 'Penawaran',
                'mail'                  => [
                    'title'             => 'Email',
                    'inbox'             => 'Kotak Masuk',
                    'draft'             => 'Draft',
                    'outbox'            => 'Terkirim',
                    'trash'             => 'Sampah',
                    'compose'           => 'Tulis Email',
                ],

                'activities'            => 'Aktivitas',
                'contacts'              => [
                    'title'             => 'Kontak',
                    'persons'           => 'Kontak Individu',
                    'organizations'     => 'Organisasi',
                ],

                'products'              => 'Produk',
                'analytics'             => 'Analitik',
                'settings'              => [
                    'title'             => 'Pengaturan',
                    'groups'            => 'Grup',
                    'roles'             => 'Peran',
                    'users'             => 'Pengguna',
                    'pipelines'         => 'Pipeline',
                    'sources'           => 'Sumber',
                    'types'             => 'Tipe',
                    'email-templates'   => 'Template Email',
                    'workflows'         => 'Alur Kerja',
                    'webhooks'          => 'Webhook',
                    'tags'              => 'Tag',
                    'attributes'        => 'Atribut',
                    'configuration'     => 'Konfigurasi',
                ],
            ],

            'powered-by'            => 'PT Famindo Teknik Karya Utama — Market Basket Analysis (Apriori) untuk rekomendasi penjualan.',
        ],

        'modal' => [
            'default-content'       => 'Konten Default',
            'default-header'        => 'Header Default',

            'create-lead-modal' => [
                'title'             => 'Buat Prospek',
                'save-btn'          => 'Simpan sebagai Prospek',
            ],

            'create-quote-modal' => [
                'title'             => 'Buat Penawaran',
                'save-btn'          => 'Simpan sebagai Penawaran',
            ],
        ],

        'menu' => [
            'dashboard'             => 'Dashboard',
            'leads'                 => 'Prospek',
            'quotes'                => 'Penawaran',
            'contacts'              => 'Kontak',
            'persons'               => 'Kontak Individu',
            'organizations'         => 'Organisasi',
            'products'              => 'Produk',
            'analytics'             => 'Analitik',
            'activities'            => 'Aktivitas',
            'mail'                  => 'Email',
            'settings'              => 'Pengaturan',
            'configuration'         => 'Konfigurasi',
            'inbox'                 => 'Kotak Masuk',
            'draft'                 => 'Draft',
            'outbox'                => 'Terkirim',
            'trash'                 => 'Sampah',
            'compose'               => 'Tulis Email',
            'users'                 => 'Pengguna',
            'roles'                 => 'Peran',
            'groups'                => 'Grup',
            'pipelines'             => 'Pipeline',
            'sources'               => 'Sumber',
            'types'                 => 'Tipe',
            'email-templates'       => 'Template Email',
            'workflows'             => 'Alur Kerja',
            'webhooks'              => 'Webhook',
            'tags'                  => 'Tag',
            'attributes'            => 'Atribut',
            'general'               => 'Umum',
            'locales'               => 'Locale',
            'currencies'            => 'Mata Uang',
            'lead-pipelines'        => 'Pipeline Prospek',
            'lead-sources'          => 'Sumber Prospek',
            'lead-types'            => 'Tipe Prospek',
            'warehouses'            => 'Gudang',
            'market-basket'         => 'Market Basket Analysis',
            'apriori'               => 'Analisis Apriori',
            'recommendations'       => 'Rekomendasi',
            'custom-engineering'    => 'Custom Engineering',
        ],
    ],

    'leads' => [
        'title'                 => 'Prospek',

        'index' => [
            'title'                 => 'Prospek',
            'create-btn'            => 'Buat Prospek',

            'datagrid' => [
                'title'               => 'Judul',
                'lead-value'          => 'Nilai Prospek',
                'source'              => 'Sumber',
                'lead-type'           => 'Tipe Prospek',
                'tag'                 => 'Tag',
                'sales-person'        => 'Sales Person',
                'expected-close-date' => 'Tanggal Tutup Diharapkan',
                'created-at'          => 'Dibuat Pada',
                'rotten-lead'         => 'Prospek Busuk',
                'delete'              => 'Hapus',
                'edit'                => 'Edit',
                'view'                => 'Lihat',
                'mass-delete'         => 'Hapus Massal',
                'mass-update'         => 'Update Massal',
            ],
        ],

        'create' => [
            'title'                 => 'Buat Prospek',
            'save-btn'              => 'Simpan sebagai Prospek',
            'general'               => 'Umum',
            'details'               => 'Detail',
            'products'              => 'Produk',
            'persons'               => 'Kontak Individu',
            'organization'          => 'Organisasi',
        ],

        'edit' => [
            'title'                 => 'Edit Prospek',
            'save-btn'              => 'Simpan Prospek',
        ],

        'view' => [
            'title'                 => 'Prospek: :title',

            'tags' => [
                'create-success'    => 'Tag berhasil dibuat.',
                'delete-success'    => 'Tag berhasil dihapus.',
            ],

            'attributes' => [
                'about'             => 'Tentang',
            ],

            'quotes' => [
                'title'             => 'Penawaran',
                'create-btn'        => 'Buat Penawaran',
                'subject'           => 'Subjek',
                'expired-at'        => 'Berakhir Pada',
                'grand-total'       => 'Total Keseluruhan',
                'created-at'        => 'Dibuat Pada',
                'actions'           => 'Aksi',
                'empty'             => 'Belum ada penawaran dibuat.',
            ],

            'activities' => [
                'title'             => 'Aktivitas',
                'empty'             => 'Belum ada aktivitas dicatat.',
            ],
        ],

        'common' => [
            'custom-attributes' => [
                'title'             => 'Atribut Kustom',
            ],

            'contact' => [
                'title'             => 'Kontak',
                'name'              => 'Nama',
                'emails'            => 'Email',
                'contact-numbers'   => 'Nomor Kontak',
                'organization'      => 'Organisasi',
                'organization-logo' => 'Logo Organisasi',
                'job-title'         => 'Jabatan',
            ],

            'products' => [
                'title'             => 'Produk',
                'create-btn'        => 'Buat Produk',
                'link-to-product'   => 'Hubungkan ke Produk',

                'datagrid' => [
                    'product'       => 'Produk',
                    'quantity'      => 'Kuantitas',
                    'price'         => 'Harga',
                    'amount'        => 'Jumlah',
                    'discount'      => 'Diskon',
                    'tax'           => 'Pajak',
                    'total'         => 'Total',
                    'action'        => 'Aksi',
                    'delete'        => 'Hapus',
                ],
            ],
        ],
    ],

    'quotes' => [
        'title'                 => 'Penawaran',

        'index' => [
            'title'                 => 'Penawaran',
            'create-btn'            => 'Buat Penawaran',

            'datagrid' => [
                'subject'           => 'Subjek',
                'sales-person'      => 'Sales Person',
                'expired-at'        => 'Berakhir Pada',
                'created-at'        => 'Dibuat Pada',
                'grand-total'       => 'Total Keseluruhan',
                'delete'            => 'Hapus',
                'edit'              => 'Edit',
                'view'              => 'Lihat',
                'mass-delete'       => 'Hapus Massal',
                'mass-update'       => 'Update Massal',
            ],
        ],

        'create' => [
            'title'                 => 'Buat Penawaran',
            'save-btn'              => 'Simpan Penawaran',
            'quote-details'         => 'Detail Penawaran',
            'quote-items'           => 'Item Penawaran',
            'billing-address'       => 'Alamat Penagihan',
            'shipping-address'      => 'Alamat Pengiriman',
        ],

        'edit' => [
            'title'                 => 'Edit Penawaran',
            'save-btn'              => 'Simpan Penawaran',
        ],
    ],

    'contacts' => [
        'persons' => [
            'title'             => 'Kontak Individu',

            'index' => [
                'title'             => 'Kontak Individu',
                'create-btn'        => 'Buat Kontak',

                'datagrid' => [
                    'name'            => 'Nama',
                    'emails'          => 'Email',
                    'contact-numbers' => 'Nomor Kontak',
                    'organization'    => 'Organisasi',
                    'created-at'      => 'Dibuat Pada',
                    'delete'          => 'Hapus',
                    'edit'            => 'Edit',
                    'view'            => 'Lihat',
                ],
            ],

            'create' => [
                'title'             => 'Buat Kontak Individu',
                'save-btn'          => 'Simpan Kontak',
            ],

            'edit' => [
                'title'             => 'Edit Kontak Individu',
                'save-btn'          => 'Simpan Kontak',
            ],
        ],

        'organizations' => [
            'title'             => 'Organisasi',

            'index' => [
                'title'             => 'Organisasi',
                'create-btn'        => 'Buat Organisasi',

                'datagrid' => [
                    'name'          => 'Nama',
                    'persons'       => 'Kontak Individu',
                    'address'       => 'Alamat',
                    'created-at'    => 'Dibuat Pada',
                    'delete'        => 'Hapus',
                    'edit'          => 'Edit',
                    'view'          => 'Lihat',
                ],
            ],

            'create' => [
                'title'             => 'Buat Organisasi',
                'save-btn'          => 'Simpan Organisasi',
            ],

            'edit' => [
                'title'             => 'Edit Organisasi',
                'save-btn'          => 'Simpan Organisasi',
            ],
        ],
    ],

    'products' => [
        'title'                 => 'Produk',

        'index' => [
            'title'             => 'Produk',
            'create-btn'        => 'Buat Produk',

            'datagrid' => [
                'sku'           => 'SKU',
                'name'          => 'Nama',
                'quantity'      => 'Kuantitas',
                'price'         => 'Harga',
                'created-at'    => 'Dibuat Pada',
                'delete'        => 'Hapus',
                'edit'          => 'Edit',
                'view'          => 'Lihat',
            ],
        ],

        'create' => [
            'title'             => 'Buat Produk',
            'save-btn'          => 'Simpan Produk',
            'general'           => 'Umum',
            'price'             => 'Harga',
            'inventories'       => 'Inventori',
        ],

        'edit' => [
            'title'             => 'Edit Produk',
            'save-btn'          => 'Simpan Produk',
        ],
    ],

    'dashboard' => [
        'index' => [
            'title'                         => 'Dashboard',
            'total-leads'                   => 'Total Prospek',
            'total-quotes'                  => 'Total Penawaran',
            'total-quote-value'             => 'Total Nilai Penawaran',
            'over-due-quotes'               => 'Penawaran Jatuh Tempo',
            'leads-by-stages'               => 'Prospek berdasarkan Tahap',
            'leads-by-sources'              => 'Prospek berdasarkan Sumber',
            'over-due-activities'           => 'Aktivitas Jatuh Tempo',
            'todays-activities'             => 'Aktivitas Hari Ini',
            'top-performing-reps'           => 'Sales Representative Terbaik',
            'leads-with-rotten-days'        => 'Prospek dengan Hari Busuk',
            'top-customers'                 => 'Pelanggan Teratas',
            'open-leads-by-states'          => 'Prospek Terbuka berdasarkan Negara Bagian',
            'lost-leads-by-states'          => 'Prospek Hilang berdasarkan Negara Bagian',
        ],
    ],

    'mail' => [
        'index' => [
            'compose'                       => 'Tulis Email',
            'draft'                         => 'Draft',
            'inbox'                         => 'Kotak Masuk',
            'outbox'                        => 'Terkirim',
            'trash'                         => 'Sampah',
        ],

        'compose' => [
            'title'                         => 'Tulis Email',
            'to'                            => 'Kepada',
            'cc'                            => 'CC',
            'bcc'                           => 'BCC',
            'subject'                       => 'Subjek',
            'content'                       => 'Konten',
            'send-btn'                      => 'Kirim',
        ],
    ],

    'configuration' => [
        'index' => [
            'title'                         => 'Konfigurasi',

            'general' => [
                'title'                     => 'Umum',
                'general'                   => 'Umum',
                'design'                    => 'Desain',
                'logo'                      => 'Logo',
                'locale-settings'           => 'Pengaturan Locale',
            ],

            'locales' => [
                'title'                     => 'Locale',
                'create-btn'                => 'Buat Locale',

                'create' => [
                    'title'                 => 'Buat Locale',
                    'code'                  => 'Kode',
                    'name'                  => 'Nama',
                    'save-btn'              => 'Simpan Locale',
                ],

                'edit' => [
                    'title'                 => 'Edit Locale',
                    'save-btn'              => 'Simpan Locale',
                ],
            ],

            'currencies' => [
                'title'                     => 'Mata Uang',
                'create-btn'                => 'Buat Mata Uang',

                'create' => [
                    'title'                 => 'Buat Mata Uang',
                    'code'                  => 'Kode',
                    'name'                  => 'Nama',
                    'save-btn'              => 'Simpan Mata Uang',
                ],

                'edit' => [
                    'title'                 => 'Edit Mata Uang',
                    'save-btn'              => 'Simpan Mata Uang',
                ],
            ],

            'email-templates' => [
                'title'                     => 'Template Email',
                'create-btn'                => 'Buat Template Email',

                'create' => [
                    'title'                 => 'Buat Template Email',
                    'save-btn'              => 'Simpan Template Email',
                ],

                'edit' => [
                    'title'                 => 'Edit Template Email',
                    'save-btn'              => 'Simpan Template Email',
                ],
            ],

            'lead-pipelines' => [
                'title'                     => 'Pipeline Prospek',
                'create-btn'                => 'Buat Pipeline',

                'create' => [
                    'title'                 => 'Buat Pipeline',
                    'save-btn'              => 'Simpan Pipeline',
                ],

                'edit' => [
                    'title'                 => 'Edit Pipeline',
                    'save-btn'              => 'Simpan Pipeline',
                ],
            ],

            'lead-sources' => [
                'title'                     => 'Sumber Prospek',
                'create-btn'                => 'Buat Sumber',

                'create' => [
                    'title'                 => 'Buat Sumber',
                    'save-btn'              => 'Simpan Sumber',
                ],

                'edit' => [
                    'title'                 => 'Edit Sumber',
                    'save-btn'              => 'Simpan Sumber',
                ],
            ],

            'lead-types' => [
                'title'                     => 'Tipe Prospek',
                'create-btn'                => 'Buat Tipe',

                'create' => [
                    'title'                 => 'Buat Tipe',
                    'save-btn'              => 'Simpan Tipe',
                ],

                'edit' => [
                    'title'                 => 'Edit Tipe',
                    'save-btn'              => 'Simpan Tipe',
                ],
            ],

            'users' => [
                'title'                     => 'Pengguna',
                'create-btn'                => 'Buat Pengguna',

                'create' => [
                    'title'                 => 'Buat Pengguna',
                    'save-btn'              => 'Simpan Pengguna',
                ],

                'edit' => [
                    'title'                 => 'Edit Pengguna',
                    'save-btn'              => 'Simpan Pengguna',
                ],
            ],

            'groups' => [
                'title'                     => 'Grup',
                'create-btn'                => 'Buat Grup',

                'create' => [
                    'title'                 => 'Buat Grup',
                    'save-btn'              => 'Simpan Grup',
                ],

                'edit' => [
                    'title'                 => 'Edit Grup',
                    'save-btn'              => 'Simpan Grup',
                ],
            ],

            'roles' => [
                'title'                     => 'Peran',
                'create-btn'                => 'Buat Peran',

                'create' => [
                    'title'                 => 'Buat Peran',
                    'save-btn'              => 'Simpan Peran',
                ],

                'edit' => [
                    'title'                 => 'Edit Peran',
                    'save-btn'              => 'Simpan Peran',
                ],
            ],

            'attributes' => [
                'title'                     => 'Atribut',
                'create-btn'                => 'Buat Atribut',

                'create' => [
                    'title'                 => 'Buat Atribut',
                    'save-btn'              => 'Simpan Atribut',
                ],

                'edit' => [
                    'title'                 => 'Edit Atribut',
                    'save-btn'              => 'Simpan Atribut',
                ],
            ],
        ],
    ],

    'response' => [
        'create-success'                    => ':name berhasil dibuat.',
        'update-success'                    => ':name berhasil diperbarui.',
        'delete-success'                    => ':name berhasil dihapus.',
        'delete-failed'                     => ':name tidak dapat dihapus karena memiliki :resource terkait.',

        'mass-operations' => [
            'delete-success'                => ':resource yang dipilih berhasil dihapus.',
            'update-success'                => ':resource yang dipilih berhasil diperbarui.',
        ],
    ],

    'settings' => [
        'title'                             => 'Pengaturan',
    ],
];
