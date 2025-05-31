<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseCRUDController;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleApiController extends BaseCRUDController
{
    protected $model = Role::class;

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'required|array'
        ]);

        $role = Role::create(['name' => $validated['name']]);
        $role->givePermissionTo($validated['permissions']);

        return [
            'data' => $role,
            'message' => 'Role created successfully.',
        ];
    }

    public function update(Request $request, $role)
    {
        $role = Role::where('id', $role)->firstOrFail();
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'required|array'
        ]);
        
        $role->update(['name' => $validated['name']]);
        $role->syncPermissions($validated['permissions']);

        return [
            'data' => $role,
            'message' => 'Role updated successfully.',
        ];
    }

    public function destroy($role)
    {
        $role = Role::where('id', $role)->firstOrFail();

        if ($role->name === 'Admin') {
            throw new \Exception('Admin role cannot be deleted.');
        }

        $role->delete();

        return [
            'message' => 'Role deleted successfully.',
        ];
    }
}
