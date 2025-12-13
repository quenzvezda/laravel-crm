<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CustomRoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        // --- 1. Define Permissions ---

        // Permissions for Direktur
        $direkturPermissions = [
            'dashboard',
            'leads', 'leads.create', 'leads.view', 'leads.edit',
            'quotes', 'quotes.create', 'quotes.edit', 'quotes.print',
            'contacts', 'contacts.persons', 'contacts.persons.view', 'contacts.persons.create', 'contacts.persons.edit', 'contacts.persons.delete',
            'contacts.organizations', 'contacts.organizations.view', 'contacts.organizations.create', 'contacts.organizations.edit', 'contacts.organizations.delete',
            'products', 'products.view', 'products.create', 'products.edit', 'products.delete',
            'analytics', 'analytics.market_basket', 'analytics.market_basket.view',
            'settings',
            'settings.signatures',
            'settings.signatures.index',
        ];

        // Permissions for Marketing (limited access)
        $marketingPermissions = [
            'dashboard',
            'leads', 'leads.create', 'leads.view', 'leads.edit',
            'quotes', 'quotes.create', 'quotes.view', 'quotes.edit', 'quotes.print',
            'contacts', 'contacts.persons', 'contacts.persons.view', 'contacts.persons.create', 'contacts.persons.edit',
            'contacts.organizations', 'contacts.organizations.view', 'contacts.organizations.create', 'contacts.organizations.edit',
        ];

        // --- 2. Create or Update Roles ---

        // Direktur Role
        DB::table('roles')->updateOrInsert(
            ['name' => 'Direktur'],
            [
                'description'     => 'Role for Direktur with full operational access.',
                'permission_type' => 'custom',
                'permissions'     => json_encode($direkturPermissions),
                'created_at'      => $now,
                'updated_at'      => $now,
            ]
        );
        $direkturRole = DB::table('roles')->where('name', 'Direktur')->first();


        // Marketing Role
        DB::table('roles')->updateOrInsert(
            ['name' => 'Marketing'],
            [
                'description'     => 'Role for Marketing with access to Leads, Quotes, and Contacts.',
                'permission_type' => 'custom',
                'permissions'     => json_encode($marketingPermissions),
                'created_at'      => $now,
                'updated_at'      => $now,
            ]
        );
        $marketingRole = DB::table('roles')->where('name', 'Marketing')->first();

        // --- 3. Create or Update Users ---

        // Direktur User
        DB::table('users')->updateOrInsert(
            ['email' => 'direktur@example.com'],
            [
                'name'            => 'Direktur',
                'password'        => Hash::make('direktur123'),
                'status'          => 1,
                'role_id'         => $direkturRole->id,
                'view_permission' => 'global',
                'created_at'      => $now,
                'updated_at'      => $now,
            ]
        );

        // Marketing User
        DB::table('users')->updateOrInsert(
            ['email' => 'marketing@example.com'],
            [
                'name'            => 'Marketing',
                'password'        => Hash::make('marketing123'),
                'status'          => 1,
                'role_id'         => $marketingRole->id,
                'view_permission' => 'global',
                'created_at'      => $now,
                'updated_at'      => $now,
            ]
        );
    }
}
