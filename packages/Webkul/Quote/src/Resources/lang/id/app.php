<?php

return [
    'quotes' => [
        'create' => [
            'title'                => 'Buat Penawaran',
            'save-btn'             => 'Simpan Penawaran',

            'quote-details'        => 'Detail Penawaran',
            'quote-items'          => 'Item Penawaran',
            'address-details'      => 'Detail Alamat',
            'billing-address'      => 'Alamat Penagihan',
            'shipping-address'     => 'Alamat Pengiriman',
            'adjustment-amount'    => 'Jumlah Penyesuaian',

            'subject-control' => [
                'label'            => 'Subjek',
                'placeholder'      => 'Subjek Penawaran',
            ],

            'description-control' => [
                'label'            => 'Deskripsi',
                'placeholder'      => 'Deskripsi Penawaran',
            ],

            'expired-at-control' => [
                'label'            => 'Berakhir Pada',
            ],

            'user-control' => [
                'label'            => 'Sales Person',
                'placeholder'      => 'Pilih Sales Person',
            ],

            'person-control' => [
                'label'            => 'Kontak Individu',
                'placeholder'      => 'Pilih Kontak Individu',
            ],

            'billing-address-control' => [
                'label'            => 'Alamat Penagihan',
                'address'          => 'Alamat',
                'country'          => 'Negara',
                'state'            => 'Provinsi',
                'city'             => 'Kota',
                'postcode'         => 'Kode Pos',
            ],

            'shipping-address-control' => [
                'label'            => 'Alamat Pengiriman',
                'address'          => 'Alamat',
                'country'          => 'Negara',
                'state'            => 'Provinsi',
                'city'             => 'Kota',
                'postcode'         => 'Kode Pos',
            ],

            'quote-items-control' => [
                'add-item'         => 'Tambah Item',

                'product-control' => [
                    'label'        => 'Produk',
                    'placeholder'  => 'Pilih Produk',
                ],

                'quantity-control' => [
                    'label'        => 'Kuantitas',
                    'placeholder'  => 'Kuantitas',
                ],

                'price-control' => [
                    'label'        => 'Harga',
                    'placeholder'  => 'Harga',
                ],

                'discount-control' => [
                    'label'        => 'Diskon',
                    'placeholder'  => 'Diskon',
                ],

                'tax-control' => [
                    'label'        => 'Pajak',
                    'placeholder'  => 'Pajak',
                ],

                'total-control' => [
                    'label'        => 'Total',
                ],

                'action' => [
                    'label'        => 'Aksi',
                    'delete'       => 'Hapus',
                ],
            ],

            'adjustment-control' => [
                'label'            => 'Penyesuaian',
                'placeholder'      => 'Jumlah Penyesuaian',
            ],
        ],

        'edit' => [
            'title'                => 'Edit Penawaran',
            'save-btn'             => 'Simpan Penawaran',
        ],

        'view' => [
            'title'                => 'Penawaran #:id',
            'print-btn'            => 'Cetak',
            'download-btn'         => 'Unduh',

            'quote-details'        => 'Detail Penawaran',
            'quote-items'          => 'Item Penawaran',
            'billing-address'      => 'Alamat Penagihan',
            'shipping-address'     => 'Alamat Pengiriman',

            'activities' => [
                'title'            => 'Aktivitas',
                'empty'            => 'Belum ada aktivitas dicatat.',
            ],
        ],

        'index' => [
            'title'                => 'Penawaran',
            'create-btn'           => 'Buat Penawaran',
            'create-success'       => 'Penawaran berhasil dibuat.',
            'update-success'       => 'Penawaran berhasil diperbarui.',
            'delete-success'       => 'Penawaran berhasil dihapus.',
            'delete-failed'        => 'Penawaran tidak dapat dihapus.',

            'datagrid' => [
                'id'                   => 'ID',
                'subject'              => 'Subjek',
                'sales-person'         => 'Sales Person',
                'person'               => 'Kontak',
                'expired-at'           => 'Berakhir Pada',
                'created-at'           => 'Dibuat Pada',
                'updated-at'           => 'Diperbarui Pada',
                'grand-total'          => 'Total Keseluruhan',
                'delete'               => 'Hapus',
                'edit'                 => 'Edit',
                'view'                 => 'Lihat',
                'mass-delete'          => 'Hapus Massal',
                'mass-update'          => 'Update Massal',
            ],
        ],

        'pdf' => [
            'title'                => 'Penawaran',
            'quote-id'             => 'ID Penawaran',
            'subject'              => 'Subjek',
            'expired-at'           => 'Berakhir Pada',
            'created-at'           => 'Dibuat Pada',
            'grand-total'          => 'Total Keseluruhan',
            'sub-total'            => 'Sub Total',
            'tax-amount'           => 'Jumlah Pajak',
            'discount-amount'      => 'Jumlah Diskon',
            'adjustment-amount'    => 'Jumlah Penyesuaian',
            'billing-address'      => 'Alamat Penagihan',
            'shipping-address'     => 'Alamat Pengiriman',
            'item'                 => 'Item',
            'price'                => 'Harga',
            'quantity'             => 'Kuantitas',
            'discount'             => 'Diskon',
            'tax'                  => 'Pajak',
            'total'                => 'Total',
        ],
    ],
];
