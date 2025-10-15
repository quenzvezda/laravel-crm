<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PersonsFromOrganizationsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        $orgs = [
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

        foreach ($orgs as $i => $org) {
            $orgId = DB::table('organizations')->where('name', $org['name'])->value('id');
            if (! $orgId) {
                // Skip if organization not present (should be seeded first)
                continue;
            }

            // Person dummy data derived from organization
            $personName = 'PIC '.$org['name'];
            $slug       = Str::slug($org['name']);
            $email      = 'pic.'.$slug.'.'.strtolower($org['code']).'@example.com';
            $phone      = '0813'.str_pad((string)($i + 2020), 7, '0', STR_PAD_LEFT);

            $inserts[] = [
                'name'             => $personName,
                'emails'           => json_encode([[ 'value' => $email, 'label' => 'work' ]], JSON_UNESCAPED_SLASHES),
                'contact_numbers'  => json_encode([[ 'value' => $phone, 'label' => 'mobile' ]], JSON_UNESCAPED_SLASHES),
                'organization_id'  => $orgId,
                'job_title'        => 'PIC',
                'user_id'          => null,
                // Align with unique_id convention: user_id|organization_id|email|phone (omit null user_id)
                'unique_id'        => $orgId.'|'.$email.'|'.$phone,
                'created_at'       => $now,
                'updated_at'       => $now,
            ];
        }

        DB::table('persons')->upsert(
            $inserts,
            ['unique_id'],
            ['name', 'emails', 'contact_numbers', 'organization_id', 'job_title', 'user_id', 'updated_at']
        );
    }
}

