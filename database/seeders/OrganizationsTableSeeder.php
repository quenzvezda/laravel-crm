<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationsTableSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $rows = [
            ['code' => 'C001', 'name' => 'PT Andalas Food',    'industry' => 'Bakery',               'city' => 'Padang'],
            ['code' => 'C002', 'name' => 'PT Cipta Rasa',      'industry' => 'Bakery',               'city' => 'Jakarta'],
            ['code' => 'C003', 'name' => 'PT Nusantara Steel', 'industry' => 'Metal',                'city' => 'Surabaya'],
            ['code' => 'C004', 'name' => 'PT Prima Plastik',   'industry' => 'Plastik',              'city' => 'Bekasi'],
            ['code' => 'C005', 'name' => 'PT Surya Bakery',    'industry' => 'Bakery',               'city' => 'Semarang'],
            ['code' => 'C006', 'name' => 'PT Delta Pharma',    'industry' => 'Farmasi',              'city' => 'Bandung'],
            ['code' => 'C007', 'name' => 'PT Maju Jaya',       'industry' => 'General Manufacturing','city' => 'Tangerang'],
            ['code' => 'C008', 'name' => 'CV Sentosa',         'industry' => 'General Manufacturing','city' => 'Depok'],
            ['code' => 'C009', 'name' => 'PT Arjuna Metal',    'industry' => 'Metal',                'city' => 'Gresik'],
            ['code' => 'C010', 'name' => 'PT Barokah Logam',   'industry' => 'Metal',                'city' => 'Sidoarjo'],
            ['code' => 'C011', 'name' => 'PT Sinar Elektrik',  'industry' => 'Elektronik',           'city' => 'Cikarang'],
            ['code' => 'C012', 'name' => 'PT Sejahtera Abadi', 'industry' => 'General Manufacturing','city' => 'Karawang'],
        ];

        $inserts = [];

        foreach ($rows as $row) {
            $inserts[] = [
                'name'       => $row['name'],
                'address'    => json_encode([
                    'city'     => $row['city'],
                    'industry' => $row['industry'],
                    'code'     => $row['code'],
                ], JSON_UNESCAPED_SLASHES),
                'user_id'    => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Idempotent: organizations.name is unique
        DB::table('organizations')->upsert(
            $inserts,
            ['name'],
            ['address', 'user_id', 'updated_at']
        );
    }
}

