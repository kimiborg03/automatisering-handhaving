<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Login function
public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    // Check of de inloggegevens correct zijn
    if (Auth::validate($credentials)) {
        // Haal de gebruiker op
        $user = \App\Models\User::where('email', $request->email)->first();

        // Controleer access en password_setup_token
        if ($user->access == 0 && is_null($user->password_setup_token)) {
            return back()->withErrors([
                'email' => 'Je account is niet actief. Neem contact op met de beheerder.',
            ]);
        }

        // Log de gebruiker in
        Auth::login($user);
        return redirect()->intended('/home');
    }

    // Als de combinatie onjuist is
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
