<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:users.browse')->only(['index']);
        $this->middleware('can:users.create')->only(['create', 'store']);
        $this->middleware('can:users.update')->only(['edit', 'update']);
        $this->middleware('can:users.delete')->only(['destroy']);
    }

    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $query = User::with('roles')->select('users.*');
            $cb = fn ($fn) => $fn;

            return DataTables::of($query)
                ->addColumn('action', function ($user) use ($cb) {
                    return <<<HTML
                        <div class="d-flex gap-2">
                            <a href="{$cb(route('users.edit', $user->id))}" class="btn btn-primary btn-sm" title="Edit">
                                <i class="bx bx-edit"></i>
                            </a>
                            <button class="btn btn-danger btn-sm delete-btn" title="Delete" data-id="{$user->id}">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    HTML;
                })
                ->make(true);
        }

        return view('master.user.index');
    }

    public function create()
    {
        $activeSidebar = 'users.index';
        $roles = Role::all();
        return view('master.user.create', compact('activeSidebar', 'roles'));
    }

    public function store(Request $request)
    {
        $apiResponse = (new UserApiController)->store($request);

        return redirect()->route('users.index')
            ->with('success', $apiResponse['message']);
    }

    public function edit(User $user)
    {
        $activeSidebar = 'users.index';
        $roles = Role::all();
        return view('master.user.edit', compact('user', 'activeSidebar', 'roles'));
    }

    public function update(Request $request, $user)
    {
        $apiResponse = (new UserApiController)->update($request, $user);

        return redirect()->route('users.index')
            ->with('success', $apiResponse['message']);
    }

    public function destroy($user)
    {
        $apiResponse = (new UserApiController)->destroy($user);

        return $apiResponse;
    }
}
