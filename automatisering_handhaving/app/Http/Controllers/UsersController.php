<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Groups;
use App\Models\Matches;
use Carbon\Carbon;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter on search term
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Haal alle users op (geen withCount, want we tellen handmatig)
        $users = $query->orderBy('id')->paginate(30);
        $groups = Groups::all();

        // Tel gespeelde wedstrijden per user (presence = true en datum voorbij)
$now = Carbon::now();
foreach ($users as $user) {
    $user->played_matches_count = Matches::where('kickoff_time', '<', $now)
        ->get()
        ->filter(function ($match) use ($user) {
            $usersArr = json_decode($match->users, true) ?? [];
            foreach ($usersArr as $u) {
                if (
                    (isset($u['user_id']) && $u['user_id'] == $user->id && !empty($u['presence']) && $u['presence'] === true)
                ) {
                    return true;
                }
            }
            return false;
        })->count();
}

        // Voor AJAX
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

        if ($originalGroupId != $newGroupId) {
            // delete user if presence is false
            $allMatches = Matches::all();

            foreach ($allMatches as $match) {
                $users = json_decode($match->users, true) ?? [];
                $updated = false;
                $filteredUsers = [];

                foreach ($users as $u) {
                    if ($u['user_id'] == $user->id && $u['presence'] === false) {
                        $updated = true;
                        continue;
                    }
                    $filteredUsers[] = $u;
                }

                if ($updated) {
                    $match->users = $filteredUsers;
                    $match->save();
                }
            }

            // add user to all matches for the new group
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