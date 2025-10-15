<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PersonsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $rows = [
            ['code' => 'C001', 'name' => 'PT Andalas Food',      'industry' => 'Bakery',               'city' => 'Padang'],
            ['code' => 'C002', 'name' => 'PT Cipta Rasa',        'industry' => 'Bakery',               'city' => 'Jakarta'],
            ['code' => 'C003', 'name' => 'PT Nusantara Steel',   'industry' => 'Metal',                'city' => 'Surabaya'],
            ['code' => 'C004', 'name' => 'PT Prima Plastik',     'industry' => 'Plastik',              'city' => 'Bekasi'],
            ['code' => 'C005', 'name' => 'PT Surya Bakery',      'industry' => 'Bakery',               'city' => 'Semarang'],
            ['code' => 'C006', 'name' => 'PT Delta Pharma',      'industry' => 'Farmasi',              'city' => 'Bandung'],
            ['code' => 'C007', 'name' => 'PT Maju Jaya',         'industry' => 'General Manufacturing','city' => 'Tangerang'],
            ['code' => 'C008', 'name' => 'CV Sentosa',           'industry' => 'General Manufacturing','city' => 'Depok'],
            ['code' => 'C009', 'name' => 'PT Arjuna Metal',      'industry' => 'Metal',                'city' => 'Gresik'],
            ['code' => 'C010', 'name' => 'PT Barokah Logam',     'industry' => 'Metal',                'city' => 'Sidoarjo'],
            ['code' => 'C011', 'name' => 'PT Sinar Elektrik',    'industry' => 'Elektronik',           'city' => 'Cikarang'],
            ['code' => 'C012', 'name' => 'PT Sejahtera Abadi',   'industry' => 'General Manufacturing','city' => 'Karawang'],
        ];

        $inserts = [];

        foreach ($rows as $i => $row) {
            $slug  = Str::slug($row['name']);
            $email = strtolower($slug).".".strtolower($row['code'])."@example.com";
            $phone = '0812'.str_pad((string)($i + 1010), 7, '0', STR_PAD_LEFT); // e.g., 08121010xxx

            $inserts[] = [
                'name'             => $row['name'],
                'emails'           => json_encode([[ 'value' => $email, 'label' => 'work' ]], JSON_UNESCAPED_SLASHES),
                'contact_numbers'  => json_encode([[ 'value' => $phone, 'label' => 'mobile' ]], JSON_UNESCAPED_SLASHES),
                'organization_id'  => null,
                'job_title'        => 'PIC '.($row['industry'] ?? 'General'),
                'user_id'          => null,
                'unique_id'        => $row['code'].'|'.$slug.'|'.$email.'|'.$phone,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        // Idempotent: upsert on unique_id
        DB::table('persons')->upsert(
            $inserts,
            ['unique_id'],
            ['name', 'emails', 'contact_numbers', 'organization_id', 'job_title', 'user_id', 'updated_at']
        );
    }
}
