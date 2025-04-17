<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login function
    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/home');
        }
        // incase of a failed login
        return back()->withErrors([
            'email' => 'De opgegeven inloggegevens zijn onjuist.',
        ]);
    }

    // Logout function
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
