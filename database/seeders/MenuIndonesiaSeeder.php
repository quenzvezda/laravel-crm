<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuIndonesiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1) Set global admin locale to Indonesian via Configuration -> General -> Locale Settings
        $this->upsertConfig('general.general.locale_settings.locale', 'id');

        // 2) Sidebar/top-level menus commonly used in this project
        $this->upsertConfig('general.settings.menu.dashboard', 'Dashboard');
        $this->upsertConfig('general.settings.menu.leads', 'Prospek');
        $this->upsertConfig('general.settings.menu.quotes', 'Penawaran');
        $this->upsertConfig('general.settings.menu.activities', 'Aktivitas');

        // Contacts (parent and children)
        // Note: Sidebar view checks 'general.settings.menu.{key}'. For parent 'contacts',
        // we set both 'contacts' and 'contacts.contacts' for safety/consistency with core_config fields.
        $this->upsertConfig('general.settings.menu.contacts', 'Kontak');
        $this->upsertConfig('general.settings.menu.contacts.contacts', 'Kontak');
        $this->upsertConfig('general.settings.menu.contacts.persons', 'Kontak Individu');
        $this->upsertConfig('general.settings.menu.contacts.organizations', 'Organisasi');

        // Mail (parent and common children)
        $this->upsertConfig('general.settings.menu.mail', 'Email');
        $this->upsertConfig('general.settings.menu.mail.mail', 'Email');
        $this->upsertConfig('general.settings.menu.mail.inbox', 'Kotak Masuk');
        $this->upsertConfig('general.settings.menu.mail.draft', 'Draft');
        $this->upsertConfig('general.settings.menu.mail.outbox', 'Terkirim');
        $this->upsertConfig('general.settings.menu.mail.sent', 'Terkirim');
        $this->upsertConfig('general.settings.menu.mail.trash', 'Sampah');

        // Products
        $this->upsertConfig('general.settings.menu.products', 'Produk');

        // Settings and configuration
        $this->upsertConfig('general.settings.menu.settings', 'Pengaturan');
        $this->upsertConfig('general.settings.menu.configuration', 'Konfigurasi');

        // 3) Custom Analytical CRM menu (Famindo/AnalyticalCRM)
        // Keys derived from packages/Famindo/AnalyticalCRM/src/Config/menu.php
        $this->upsertConfig('general.settings.menu.analytics', 'Analitik');
        $this->upsertConfig('general.settings.menu.analytics.market_basket', 'Market Basket (Apriori)');

        $this->command?->info('[MenuIndonesiaSeeder] Updated locale and Indonesian labels for sidebar/menu.');
    }

    private function upsertConfig(string $code, string $value): void
    {
        $existing = DB::table('core_config')->where('code', $code)->first();

        if ($existing) {
            DB::table('core_config')
                ->where('code', $code)
                ->update(['value' => $value, 'updated_at' => now()]);
        } else {
            DB::table('core_config')->insert([
                'code'       => $code,
                'value'      => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

