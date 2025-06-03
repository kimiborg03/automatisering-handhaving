<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class PasswordSetupController extends Controller
{   
    // show password setup form
    public function showSetupForm($token)
    {
        $user = User::where('password_setup_token', $token)->first();

        if (!$user) {
            abort(404); //invalid token
        }

        return view('auth.password_setup', ['token' => $token, 'email' => $user->email]);
    }
    // handle password setup
    public function setPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('password_setup_token', $request->token)->firstOrFail();
        // save the new password, verify the token and set the user as active
        $user->password = Hash::make($request->password);
        $user->password_setup_token = null;
        $user->email_verified_at = now();
        $user->access = 1; // activate the user
        $user->save();

        Auth::login($user); // log the user in

        return redirect()->route('home')->with('success', 'Wachtwoord ingesteld en ingelogd!');
    }

    public function changePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'new_password' => 'required|confirmed|min:8',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'Huidig wachtwoord klopt niet.']);
    }

    $user->password = Hash::make($request->new_password);
    $user->save();

    return back()->with('success', 'Wachtwoord succesvol gewijzigd!');
}
}
