<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
