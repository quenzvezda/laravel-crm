<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CustomerOrder;

class CustomerOrderSeeder extends Seeder
{
    public function run(): void
    {
        CustomerOrder::insert([
            [
                'order_date'       => '2023-03-04',
                'end_user_name'    => 'CV. GOKAR FOOD KARAWANG',
                'address'          => 'Karawang, Cilamaya',
                'machine_name'     => 'Mesin Fillet Manual, Mesin Fillet Automatis, Skinner dan Dryer',
                'machine_function' => "1. Mesin fillet manual untuk membelah perut ikan kalapan
2. Mesin fillet automatis untuk headlest (memotong kepala) dan membelah perut ikan kalapan
3. Mesin skinner untuk menghilangkan sisik ikan kalapan
4. Dryer untuk pengering ikan di dalam ruangan apabila cuaca hujan atau ...",
                'machine_system'   => null,
                'order_chronology' => null,
            ],
            [
                'order_date'       => '2024-09-03',
                'end_user_name'    => 'PT. ALGISINDO',
                'address'          => 'Setu, Bekasi, Jawa Barat',
                'machine_name'     => 'Mesin Blanking',
                'machine_function' => "Untuk melepas sisa potongan pada material impraboard setelah di pond (pelepasan asalnya manual dengan tangan setiap lubang, setelah ada mesin langsung 3 sheet bisa dilepaskan dalam waktu ±3 detik).",
                'machine_system'   => "Pelepasan sisa cutting pada impraboard dengan jig yang sesuai dengan partnya, lalu dipress menggunakan PIN INSERT yang digerakkan naik-turun memakai pneumatic merk SMC.",
                'order_chronology' => null,
            ],
            [
                'order_date'       => '2024-06-13',
                'end_user_name'    => 'PT. PERTAMINA LUBRICANS',
                'address'          => 'Koja, Jl. Jampea',
                'machine_name'     => 'Auto Leveling System',
                'machine_function' => "Untuk mengetahui leveling cairan yang digunakan di lab; jika habis maka alarm berbunyi agar operator segera mengisi cairan.",
                'machine_system'   => null,
                'order_chronology' => null,
            ],
            [
                'order_date'       => '2025-02-01',
                'end_user_name'    => 'PT. TUV NORDS INDONESIA',
                'address'          => 'Jababeka II, Cikarang Selatan, Bekasi',
                'machine_name'     => '1) Mesin Cook Handle Test; 2) Mesin Load Test',
                'machine_function' => "1) Untuk pengujian handle panci single dan double sesuai SNI
2) Untuk menguji kekuatan sendok sesuai SNI.",
                'machine_system'   => null,
                'order_chronology' => null,
            ],
        ]);
    }
}
