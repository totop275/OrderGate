<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Supports\Helper;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:roles.browse')->only(['index']);
        $this->middleware('can:roles.create')->only(['create', 'store']);
        $this->middleware('can:roles.update')->only(['edit', 'update']);
        $this->middleware('can:roles.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = Role::with('permissions')->select('roles.*');
            $cb = fn ($fn) => $fn;

            return DataTables::of($query)
                ->addColumn('action', function ($role) use ($cb) {
                    return <<<HTML
                        <div class="d-flex gap-2">
                            <a href="{$cb(route('roles.edit', $role->id))}" class="btn btn-primary btn-sm" title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm delete-btn" title="Delete" data-id="{$role->id}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    HTML;
                })
                ->make(true);
        }

        return view('master.role.index');
    }

    public function create()
    {
        Helper::syncPermissions();

        $activeSidebar = 'roles.index';
        $permissionGroups = Permission::pluck('name')->groupBy(fn ($permission) => explode('.', $permission)[0]);
        return view('master.role.create', compact('activeSidebar', 'permissionGroups'));
    }

    public function store(Request $request)
    {
        $apiResponse = (new RoleApiController)->store($request);

        return redirect()->route('roles.index')
            ->with('success', $apiResponse['message']);
    }

    public function edit(Role $role)
    {
        Helper::syncPermissions();

        $activeSidebar = 'roles.index';
        $permissionGroups = Permission::pluck('name')->groupBy(fn ($permission) => explode('.', $permission)[0]);
        return view('master.role.edit', compact('role', 'activeSidebar', 'permissionGroups'));
    }

    public function update(Request $request, $role)
    {
        $apiResponse = (new RoleApiController)->update($request, $role);

        return redirect()->route('roles.index')
            ->with('success', $apiResponse['message']);
    }

    public function destroy($role)
    {
        $apiResponse = (new RoleApiController)->destroy($role);

        return $apiResponse;
    }
}
