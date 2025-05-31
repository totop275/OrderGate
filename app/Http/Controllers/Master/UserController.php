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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'roles' => 'required|array',
            'status' => 'required|in:' . implode(',', [User::STATUS_ACTIVE, User::STATUS_INACTIVE]),
        ]);
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
        ]);

        $user->syncRoles($validated['roles']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User created successfully.',
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $activeSidebar = 'users.index';
        $roles = Role::all();
        return view('master.user.edit', compact('user', 'activeSidebar', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'roles' => 'required|array',
            'status' => 'required|in:' . implode(',', [User::STATUS_ACTIVE, User::STATUS_INACTIVE]),
        ]);
        
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'status' => $validated['status'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $user->syncRoles($validated['roles']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'User updated successfully.',
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === request()->user()->id) {
            return response()->json([
                'message' => 'You cannot delete your own account.',
            ], 400);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ]);
    }
}
