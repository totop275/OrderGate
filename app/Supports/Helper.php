<?php

namespace App\Supports;

use Spatie\Permission\Models\Permission;

class Helper
{
    public static function syncPermissions()
    {
        $permissions = config('permission.built_in_permissions');
        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission]);
        }
    }
}