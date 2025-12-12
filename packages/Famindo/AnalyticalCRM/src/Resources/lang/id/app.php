<?php

return [
    'analytics' => [
        'title'                     => 'Analitik',
        'market-basket'             => 'Market Basket Analysis',
        'apriori'                   => 'Algoritma Apriori',

        'index' => [
            'title'                 => 'Market Basket Analysis (Apriori)',
            'create-analysis'       => 'Buat Analisis Baru',
            'run-analysis'          => 'Jalankan Analisis',
            'export-csv'            => 'Ekspor CSV',
            'view-results'          => 'Lihat Hasil',

            'parameters' => [
                'title'             => 'Parameter Analisis',
                'date-range'        => 'Rentang Tanggal',
                'from-date'         => 'Tanggal Mulai',
                'to-date'           => 'Tanggal Akhir',
                'min-support'       => 'Minimum Support',
                'min-confidence'    => 'Minimum Confidence',
                'min-items'         => 'Minimum Items per Transaksi',
                'label'             => 'Label Analisis',
                'persist-transactions' => 'Simpan Data Transaksi',
            ],

            'snapshots' => [
                'title'             => 'Snapshot Analisis',
                'select-snapshot'   => 'Pilih Snapshot',
                'no-snapshots'      => 'Belum ada snapshot tersedia',
                'set-active'        => 'Jadikan Aktif',
            ],

            'results' => [
                'title'             => 'Hasil Analisis',
                'rules-found'       => 'Rules Ditemukan',
                'lhs'               => 'Antecedent (Jika)',
                'rhs'               => 'Consequent (Maka)',
                'support'           => 'Support',
                'confidence'        => 'Confidence',
                'lift'              => 'Lift',
                'created-at'        => 'Dibuat Pada',
                'period'            => 'Periode',
                'no-results'        => 'Tidak ada hasil analisis ditemukan',
            ],
        ],

        'create' => [
            'title'                 => 'Buat Analisis Market Basket',
            'save-btn'              => 'Jalankan Analisis',
            'analysis-label'        => 'Label Analisis',
            'analysis-label-placeholder' => 'Masukkan label untuk analisis ini',
        ],

        'recommendations' => [
            'title'                 => 'Rekomendasi Produk',
            'based-on'              => 'Berdasarkan: :items',
            'suggestions'           => 'Produk yang Disarankan',
            'add-to-quote'          => 'Tambahkan ke Penawaran',
            'add-to-lead'           => 'Tambahkan ke Prospek',
            'confidence-score'      => 'Confidence Score',
            'no-recommendations'    => 'Tidak ada rekomendasi ditemukan',
        ],

        'messages' => [
            'analysis-started'      => 'Analisis Market Basket telah dimulai. Silahkan tunggu beberapa saat.',
            'analysis-completed'    => 'Analisis Market Basket berhasil diselesaikan.',
            'analysis-failed'       => 'Analisis Market Basket gagal. Silahkan coba lagi.',
            'no-data-found'         => 'Tidak ada data transaksi ditemukan untuk parameter yang dipilih.',
            'insufficient-data'     => 'Data transaksi tidak mencukupi untuk analisis.',
            'export-success'        => 'Data berhasil diekspor ke CSV.',
            'recommendation-added'  => 'Rekomendasi produk berhasil ditambahkan.',
        ],

        'validations' => [
            'date-required'         => 'Tanggal mulai dan akhir harus diisi.',
            'date-invalid'          => 'Format tanggal tidak valid.',
            'from-greater-than-to'  => 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.',
            'support-required'      => 'Minimum support harus diisi.',
            'support-invalid'       => 'Minimum support harus berupa angka antara 0 dan 1.',
            'confidence-required'   => 'Minimum confidence harus diisi.',
            'confidence-invalid'    => 'Minimum confidence harus berupa angka antara 0 dan 1.',
            'min-items-required'    => 'Minimum items harus diisi.',
            'min-items-invalid'     => 'Minimum items harus berupa angka positif.',
            'label-required'        => 'Label analisis harus diisi.',
        ],

        'help' => [
            'support'               => 'Support: Persentase transaksi yang mengandung itemset tertentu',
            'confidence'            => 'Confidence: Probabilitas kemunculan consequent jika antecedent sudah ada',
            'lift'                  => 'Lift: Rasio confidence terhadap support consequent. > 1 menunjukkan korelasi positif',
            'min-support-help'      => 'Nilai minimum support untuk itemset (0.01 = 1%)',
            'min-confidence-help'   => 'Nilai minimum confidence untuk rules (0.5 = 50%)',
            'min-items-help'        => 'Jumlah minimum item dalam satu transaksi untuk dianalisis',
        ],

        'status' => [
            'queued'                => 'Dalam Antrian',
            'processing'            => 'Sedang Diproses',
            'completed'             => 'Selesai',
            'failed'                => 'Gagal',
        ],
    ],

    'custom-engineering' => [
        'title'                     => 'Custom Engineering Orders',
        'orders'                    => 'Pesanan Custom Engineering',
        'analysis'                  => 'Analisis Pesanan',
        'frequent-items'            => 'Item yang Sering Dipesan',
        'customer-patterns'         => 'Pola Pemesanan Pelanggan',
    ],
];
