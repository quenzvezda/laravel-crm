<?php

return [
    'products' => [
        'create' => [
            'title'                => 'Buat Produk',
            'save-btn'             => 'Simpan Produk',

            'general'              => 'Umum',
            'price'                => 'Harga',
            'inventories'          => 'Inventori',

            'name-control' => [
                'label'            => 'Nama',
                'placeholder'      => 'Nama Produk',
            ],

            'sku-control' => [
                'label'            => 'SKU',
                'placeholder'      => 'SKU Produk',
            ],

            'description-control' => [
                'label'            => 'Deskripsi',
                'placeholder'      => 'Deskripsi Produk',
            ],

            'price-control' => [
                'label'            => 'Harga',
                'placeholder'      => 'Harga Produk',
            ],

            'quantity-control' => [
                'label'            => 'Kuantitas',
                'placeholder'      => 'Kuantitas',
            ],
        ],

        'edit' => [
            'title'                => 'Edit Produk',
            'save-btn'             => 'Simpan Produk',
        ],

        'view' => [
            'title'                => 'Produk: :name',

            'about' => [
                'title'            => 'Tentang',
            ],

            'inventories' => [
                'title'            => 'Inventori',
                'warehouse'        => 'Gudang',
                'quantity'         => 'Kuantitas',
                'in-stock'         => 'Tersedia',
                'allocated'        => 'Dialokasikan',
                'on-hand'          => 'Tersedia di Tangan',
            ],
        ],

        'index' => [
            'title'                => 'Produk',
            'create-btn'           => 'Buat Produk',
            'create-success'       => 'Produk berhasil dibuat.',
            'update-success'       => 'Produk berhasil diperbarui.',
            'delete-success'       => 'Produk berhasil dihapus.',
            'delete-failed'        => 'Produk tidak dapat dihapus.',

            'datagrid' => [
                'id'                   => 'ID',
                'sku'                  => 'SKU',
                'name'                 => 'Nama',
                'description'          => 'Deskripsi',
                'quantity'             => 'Kuantitas',
                'price'                => 'Harga',
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
