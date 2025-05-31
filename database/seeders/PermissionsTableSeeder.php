<?php

namespace Database\Seeders;

use App\Supports\Helper;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Helper::syncPermissions();
        $roles = config('permission.built_in_roles');

        foreach ($roles as $role => $permissions) {
            $role = Role::updateOrCreate(['name' => $role]);
            $role->givePermissionTo($permissions);
        }
    }
}
