<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Groups;

class UsersController extends Controller
{
    public function index()
    {
        $users = User::all();
         $groups = Groups::all();
         return view('admin.users', compact('users', 'groups'));
    }
    
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'group_id' => 'nullable|integer',
        'role' => 'nullable|string|max:255',
        'access' => 'nullable|string|max:255',
    ]);

    $user = \App\Models\User::findOrFail($id);
    $user->update([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'group_id' => $request->group_id,
        'role' => $request->role,
        'access' => $request->has('access') ? 1 : 0,
    ]);


    return redirect()->route('admin.users')->with('success', 'Gebruiker succesvol bijgewerkt!');
}
}