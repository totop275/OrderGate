<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\BaseCRUDController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserApiController extends BaseCRUDController
{
    protected $model = User::class;

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

        return [
            'data' => $user,
            'message' => 'User created successfully.',
        ];
    }

    public function update(Request $request, $user)
    {
        $user = User::where('id', $user)->orWhere('email', $user)->firstOrFail();
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

        return [
            'data' => $user,
            'message' => 'User updated successfully.',
        ];
    }

    public function destroy($user)
    {
        $user = User::where('id', $user)->orWhere('email', $user)->firstOrFail();

        if ($user->id === request()->user()->id) {
            throw new \Exception('You cannot delete your own account.');
        }

        $user->delete();

        return [
            'message' => 'User deleted successfully.',
        ];
    }
}
