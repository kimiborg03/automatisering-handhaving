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
            'groups' => 'required|array',
            'Limit' => 'nullable|integer',
            'comment' => 'nullable',
            'deadline' => 'nullable',
        ]);

        $users = User::whereIn('group_id', $validated['groups'])->get();

        // Zorg ervoor dat je hier $userData gebruikt in plaats van $users
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
            'users' => json_encode($userData),  // Gebruik hier $userData
        ]);
        
        return redirect()->back()->with('success', 'Wedstrijd opgeslagen!');
    }



    public function show(){
        $groups = Groups::all();

        return view('admin.add-match', compact('groups'));
    }

    
}

