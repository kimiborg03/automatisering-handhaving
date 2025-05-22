<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Groups;
use App\Models\Matches;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
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
        $users = json_decode($match->users, true);

        $userIdToRemove = (int) $request->input('user_id');

        logger()->info('--- Delete User From Match Debug ---');
        logger()->info('Match ID:', ['match_id' => $matchId]);
        logger()->info('Requested user_id to remove:', ['user_id' => $userIdToRemove]);
        logger()->info('Original users:', $users);

        $filteredUsers = array_filter($users, function ($user) use ($userIdToRemove) {
            return $user['user_id'] != $userIdToRemove;
        });

        logger()->info('Filtered users:', $filteredUsers);

        $match->users = json_encode(array_values($filteredUsers));
        $saveSuccess = $match->save();

        if ($saveSuccess) {
            logger()->info('Match succesvol opgeslagen met nieuwe users.');
        } else {
            logger()->warning('Opslaan van match is mislukt!');
        }

        return response()->json(data: ['status' => 'user_removed', 'match_id' => $matchId]);
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
            'groups' => 'nullable|array',
            'Limit' => 'nullable|integer',
            'comment' => 'nullable',
            'deadline' => 'nullable|date',
        ]);

        $users = collect();
        if (!empty($validated['groups'])) {
            $users = User::whereIn('group_id', $validated['groups'])->get();
        }

        $userData = $users->map(function ($user) {
            return [
                'user_id' => $user->id,
                'presence' => true,
            ];
        });

        // Bereken deadline als die niet is meegegeven
        $deadline = $validated['deadline'] ?? Carbon::parse($checkinTime)->subDays(3)->format('Y-m-d H:i:s');

        Matches::create([
            'name' => $request->input('name-match'),
            'location' => $request->input('location'),
            'checkin_time' => $checkinTime,
            'kickoff_time' => $kickoffTime,
            'category' => $request->input('category'),
            'limit' => $request->input('Limit'),
            'deadline' => $deadline,
            'comment' => $request->input('comment'),
            'users' => json_encode($userData),
            'groups' => $validated['groups'] ?? [],
        ]);

        return redirect()->route('admin.add-match')->with('success', "Wedstrijd '{$request->input('name-match')}' is succesvol toegevoegd!");

    
    }



    public function show(){
        $groups = Groups::all();

        return view('admin.add-match', compact('groups'));
    }

    
}

