<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Groups;
use App\Models\Matches;
use App\Models\User;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class MatchController extends Controller
{

    public function updateMatch(Request $request, $matchId)
    {
        $match = Matches::findOrFail($matchId);
        $users = json_decode($match->users, true) ?? [];
        $newUserId = $request->input('user_id');

        Log::info('Gebruiker toevoegen aan wedstrijd', [
            'match_id' => $matchId,
            'user_id' => $newUserId,
            'bestaande_users' => $users
        ]);

        $alreadyExists = collect($users)->contains('user_id', $newUserId);

        if (! $alreadyExists) {
            $users[] = [
                'user_id' => $newUserId,
                'presence' => true,
            ];

            $match->users = json_encode($users);
            $match->save();

            return response()->json(['status' => 'user_added', 'user_id' => $newUserId]);
        }

        return response()->json(['status' => 'already_exists']);
    }
    public function deleteUserFromMatch(Request $request, $matchId)
    {
        $match = Matches::findOrFail($matchId);
        $users = json_decode($match->users, true); // Decode JSON to array

        $userIdToRemove = $request->input('user_id'); // ✅ get user ID from the request

        // Filter out the user
        $filteredUsers = array_filter($users, function ($user) use ($userIdToRemove) {
            return $user['id'] !== $userIdToRemove;
        });

        // Reindex and update
        $match->users = json_encode(array_values($filteredUsers)); // reset array keys
        $match->save();

        // Geef een JSON-response terug in plaats van redirect
        return response()->json(['status' => 'user_removed', 'match_id' => $matchId]);
    }
    public function store(Request $request)
    {
        $checkinTime = $request->date . ' ' . $request->input('check-in-time') . ':00';
        $kickoffTime = $request->date . ' ' . $request->input('kick-off-time') . ':00';
        $validated = $request->validate([
            'name-match' => 'required|string',
            'location' => 'required|string',
            'date' => 'required|date',
            'check-in-time' => 'required',
            'kick-off-time' => 'required',
            'category' => 'required|string',
            'groups' => 'nullable|array', // 👈 allow no groups
            'Limit' => 'nullable|integer',
            'comment' => 'nullable',
            'deadline' => 'nullable',
        ]);

        $users = collect(); // 👈 empty fallback
        if (!empty($validated['groups'])) {
            $users = User::whereIn('group_id', $validated['groups'])->get();
        }

        $userData = $users->map(function ($user) {
            return [
                'user_id' => $user->id,
                'presence' => true,
            ];
        });

        Matches::create([
            'name' => $request->input('name-match'),
            'location' => $request->input('location'),
            'checkin_time' => $checkinTime,
            'kickoff_time' => $kickoffTime,
            'category' => $request->input('category'),
            'limit' => $request->input('Limit'),
            'deadline' => $request->input('deadline'),
            'comment' => $request->input('comment'),
            'users' => json_encode($userData),
        ]);
        
        return redirect()->back()->with('success', 'Wedstrijd opgeslagen!');
    }



    public function show(){
        $groups = Groups::all();

        return view('add-match', compact('groups'));
    }
}
