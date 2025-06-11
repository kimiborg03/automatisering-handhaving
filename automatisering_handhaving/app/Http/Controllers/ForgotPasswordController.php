<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ForgotPasswordController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );
        // Check if the email exists in the users table
        return back()->with(
            // Check if reset link was sent successfully
            $status === Password::RESET_LINK_SENT
                ? ['success' => __($status)]
                : ['error' => __($status)]
        );
    }

public function reset(Request $request)
{
    $request->validate([
        // Validate the token and password
        'token' => 'required',
        'password' => 'required|min:8|confirmed',
    ]);

    // collect all password reset tokens
    $records = DB::table('password_reset_tokens')->get();

    $found = null;
    // find matching token
    foreach ($records as $record) {
        if (Hash::check($request->token, $record->token)) {
            $found = $record;
            break;
        }
    }
    // if no token, return error
    if (!$found) {
        return back()->withErrors(['token' => 'Deze reset link is ongeldig of verlopen.']);
    }
    $user = User::where('email', $found->email)->first();

    if (!$user) {
        return back()->withErrors(['email' => 'Gebruiker niet gevonden.']);
    }
    
    $user->password = Hash::make($request->password);
    $user->save();

    // delete used token
    DB::table('password_reset_tokens')->where('email', $found->email)->delete();

    // Redirect to login
    return redirect()->route('login')->with('success', 'Wachtwoord succesvol aangepast, je kunt nu inloggen.');
    }
}