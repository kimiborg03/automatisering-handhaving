<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Groups;
use App\Models\Matches;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter on search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('id')->paginate(30);
        $groups = Groups::all();

        // Load more users via AJAX
        if ($request->ajax()) {
            return view('partials.user-rows', compact('users', 'groups'))->render();
        }

        return view('admin.users', compact('users', 'groups'));
    }

    public function update(Request $request, $id)
    {
        // validate
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

        $originalGroupId = $user->group_id;
        $newGroupId = $request->group_id;

        // Update user
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'group_id' => $newGroupId,
            'role' => $request->role,
            'access' => $request->has('access') ? 1 : 0,
        ]);

        // if the group has changed, update matches
        if ($originalGroupId != $newGroupId) {
            // Remove user from all matches where presence is false
            $matches = Matches::whereJsonContains('users', [['user_id' => $user->id]])->get();

            foreach ($matches as $match) {
                $users = json_decode($match->users, true) ?? [];

                // Filter out this user if presence is false
                $filteredUsers = collect($users)
                    ->reject(fn($u) => $u['user_id'] == $user->id && $u['presence'] === false)
                    ->values()
                    ->all();

                $match->users = $filteredUsers;
                $match->save();
            }

            // Add user to new group's matches with presence = false
            if ($newGroupId) {
                $matchesWithGroup = Matches::whereJsonContains('groups', $newGroupId)->get();

                foreach ($matchesWithGroup as $match) {
                    $users = json_decode($match->users, true) ?? [];

                    $alreadyIn = collect($users)->contains(fn($u) => $u['user_id'] == $user->id);

                    if (! $alreadyIn) {
                        $users[] = ['user_id' => $user->id, 'presence' => false];
                        $match->users = $users;
                        $match->save();
                    }
                }
            }
        }

        return redirect()->route('admin.users')->with('success', 'Gebruiker succesvol bijgewerkt!');
    }
}
