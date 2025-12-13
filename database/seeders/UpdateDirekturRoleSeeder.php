<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDirekturRoleSeeder extends Seeder
{
    public function run(): void
    {
        $role = DB::table('roles')->where('name', 'Direktur')->first();

        if (! $role) {
            return;
        }

        $permissions = json_decode($role->permissions, true) ?: [];

        // Add the new permissions if they don't exist
        if (! in_array('settings.signatures', $permissions)) {
            $permissions[] = 'settings.signatures';
        }
        if (! in_array('settings.signatures.index', $permissions)) {
            $permissions[] = 'settings.signatures.index';
        }

        DB::table('roles')->where('id', $role->id)->update([
            'permissions' => json_encode(array_values(array_unique($permissions)))
        ]);
    }
}
