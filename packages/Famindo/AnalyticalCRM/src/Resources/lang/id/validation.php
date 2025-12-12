<?php

return [
    'required'                     => 'Kolom :attribute wajib diisi.',
    'string'                       => 'Kolom :attribute harus berupa teks.',
    'numeric'                      => 'Kolom :attribute harus berupa angka.',
    'between'                      => 'Kolom :attribute harus berada di antara :min dan :max.',
    'min'                          => 'Kolom :attribute minimal :min.',
    'max'                          => 'Kolom :attribute maksimal :max.',
    'date'                         => 'Kolom :attribute harus berupa tanggal yang valid.',
    'date_format'                  => 'Kolom :attribute harus sesuai format :format.',
    'after'                        => 'Kolom :attribute harus tanggal setelah :date.',
    'before'                       => 'Kolom :attribute harus tanggal sebelum :date.',
    'in'                           => 'Pilihan pada :attribute tidak valid.',
    'exists'                       => 'Pilihan pada :attribute tidak ditemukan.',
    'unique'                       => ':attribute sudah digunakan.',
    'confirmed'                    => 'Konfirmasi :attribute tidak cocok.',
    'email'                        => 'Format email pada :attribute tidak valid.',

    'custom' => [
        'min_support' => [
            'required'             => 'Minimum support wajib diisi.',
            'numeric'              => 'Minimum support harus berupa angka.',
            'between'              => 'Minimum support harus antara 0.01 dan 1.',
        ],

        'min_confidence' => [
            'required'             => 'Minimum confidence wajib diisi.',
            'numeric'              => 'Minimum confidence harus berupa angka.',
            'between'              => 'Minimum confidence harus antara 0.01 dan 1.',
        ],

        'min_items' => [
            'required'             => 'Minimum items wajib diisi.',
            'numeric'              => 'Minimum items harus berupa angka.',
            'min'                  => 'Minimum items minimal 2.',
        ],

        'from_date' => [
            'required'             => 'Tanggal mulai wajib diisi.',
            'date'                 => 'Format tanggal mulai tidak valid.',
        ],

        'to_date' => [
            'required'             => 'Tanggal akhir wajib diisi.',
            'date'                 => 'Format tanggal akhir tidak valid.',
            'after'                => 'Tanggal akhir harus setelah tanggal mulai.',
        ],

        'label' => [
            'required'             => 'Label analisis wajib diisi.',
            'string'               => 'Label analisis harus berupa teks.',
            'max'                  => 'Label analisis maksimal 255 karakter.',
        ],
    ],

    'attributes' => [
        'min_support'              => 'minimum support',
        'min_confidence'           => 'minimum confidence',
        'min_items'                => 'minimum items',
        'from_date'                => 'tanggal mulai',
        'to_date'                  => 'tanggal akhir',
        'label'                    => 'label',
        'persist_transactions'     => 'simpan transaksi',
    ],
];
