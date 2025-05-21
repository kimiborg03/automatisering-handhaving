<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\URL;

class RegisterController extends Controller
{
    // Show register form
    public function showRegistrationForm()
    {
        $groups = DB::table('groups')->get();
        return view('register', compact('groups'));
    }

    public function register(Request $request)
    {
        // Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'group_id' => 'required|exists:groups,id',
            'role' => 'required|string',
        ]);


    $token = Str::random(64);

    // create user
    $user = User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'group_id' => $request->group_id,
        'role' => $request->role,
        'access' => 0,
        'password' => Hash::make(Str::random(10)), // temporary password
        'password_setup_token' => $token,
    ]);
        // generate signed URL for password setup
    $link = URL::signedRoute('password.setup.form', ['token' => $token]);

        // send email
        Mail::to($user->email)->send(new RegisterMail($user, $link));

        return redirect()->route('register')->with('success', 'Gebruiker succesvol geregistreerd!');
    }

    // show password setup form
    public function showPasswordSetupForm(Request $request, $token)
    {
        $user = User::where('password_setup_token', $token)->firstOrFail();
        if (!$request->hasValidSignature()) {
            abort(403, 'De link is verlopen of ongeldig.');
        }

        return view('auth.password_setup', compact('user'));
    }

    // set new password
    public function setPassword(Request $request, $token)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('password_setup_token', $token)->firstOrFail();

        $user->password = Hash::make($request->password);
        $user->password_setup_token = null; // remove token for single use
        $user->save();

        return redirect()->route('login')->with('success', 'Wachtwoord succesvol ingesteld. Je kunt nu inloggen.');
    }


    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}
