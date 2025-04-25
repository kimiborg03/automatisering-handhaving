<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\RegisterMail;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    // Laat het registratieformulier zien
    public function showRegistrationForm()
    {
        $groups = DB::table('groups')->get(); // alle groepen ophalen
        return view('register', compact('groups'));
    }

    // Verwerk registratie
    public function register(Request $request)
    {
        // Validatie
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'group_id' => 'required|exists:groups,id',
            'role' => 'required|string',
        ]);

        // Genereer random wachtwoord
        $randomPassword = Str::random(10);

        // Maak gebruiker aan
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'group_id' => $request->group_id,
            'role' => $request->role,
            'access' => 0,
            'password' => Hash::make($randomPassword),
        ]);


        Mail::to($user->email)->send(new RegisterMail($user));

        return redirect('/register');
    }
}
