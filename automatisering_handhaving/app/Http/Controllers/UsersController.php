<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Groups;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        // filter on search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id')->paginate(30);
        $groups = Groups::all();
        // load more users
        if ($request->ajax()) {
            return view('partials.user-rows', compact('users', 'groups'))->render();
        }

        return view('admin.users', compact('users', 'groups'));
    }

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => [
            'required',
            'string',
            'max:255',
            Rule::unique('users')->ignore($id),
        ],
        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('users')->ignore($id),
        ],
        'group_id' => 'nullable|integer',
        'role' => 'nullable|string|max:255',
        'access' => 'nullable|string|max:255',
    ]);

    $user = User::findOrFail($id);
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
