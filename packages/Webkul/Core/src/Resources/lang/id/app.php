<?php

return [
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

    'validations' => [
        'captcha' => [
            'captcha'  => 'Validasi Captcha!',
            'required' => 'Silahkan pilih captcha.',
        ],

        'vat-id' => [
            'invalid-format' => 'Format VAT ID tidak valid.',
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
];
