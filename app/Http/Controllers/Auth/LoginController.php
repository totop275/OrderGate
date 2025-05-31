<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!User::where('email', $request->email)->exists()) {
            return redirect()->route('login')->withErrors(['email' => 'Email not registered in the system'])->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('landing');
        }

        return redirect()->route('login')->withErrors(['password' => 'Incorrect email and password combination'])->withInput();
    }

    public function generateToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'sometimes|nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Incorrect email and password combination'], 401);
        }

        $token = $user->createToken($request->device_name ?? 'default')->plainTextToken;
        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function me()
    {
        return response()->json(request()->user());
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function logoutApi()
    {
        request()->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
