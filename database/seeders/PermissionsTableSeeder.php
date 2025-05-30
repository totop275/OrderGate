<?php

namespace Database\Seeders;

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
        $permissions = config('permission.built_in_permissions');
        $roles = config('permission.built_in_roles');

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach ($roles as $role => $permissions) {
            $role = Role::create(['name' => $role]);
            $role->givePermissionTo($permissions);
        }
    }
}
