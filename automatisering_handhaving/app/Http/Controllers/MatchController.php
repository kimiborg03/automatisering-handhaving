<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Groups;
use App\Models\Matches;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** @var \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard $guard */

class MatchController extends Controller
{

    public function deleteMatch(Request $request, $id)
    {
        $match = Matches::findOrFail($id);
        $match->delete();

        return response()->json(['status' => 'match_deleted', 'match_id' => $id]);
    }

    public function setDeadlineToNow(Request $request, $id)
    {
        $guard = Auth::guard();

        $user = $guard->user();

        // $user = auth()->user();

        // if (!$user->is_admin) {
        //     abort(403, 'Unauthorized');
        // }

        $match = Matches::findOrFail($id);
        $match->deadline = now();
        $match->save();

        return response()->json(['status' => 'success']);
    }
    public function removeDeadline(Request $request, $id)
    {

        $match = Matches::findOrFail($id);
        $match->deadline = null;
        $match->save();

        return response()->json(['status' => 'success']);
    }


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
                'presence' => false,
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

        $totalUsers = $users->count();
        $limit = (int) ($validated['Limit'] ?? 0);

        if ($limit > 0 && $totalUsers > $limit) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['Limit' => "Het totaal aantal geselecteerde gebruikers ($totalUsers) is groter dan het limiet ($limit)."]);
        }


        $userData = $users->map(function ($user) {
            return [
                'user_id' => $user->id,
                'presence' => false,
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

    public function update(Request $request, $id)
    {
        // Validatie
        $validated = $request->validate([
            'name-match' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'check-in-time' => 'required|date_format:H:i',
            'kick-off-time' => 'required|date_format:H:i',
            'category' => 'required|string',
            'Limit' => 'nullable|integer|min:1',
            'comment' => 'nullable|string|max:1000',
            'groups' => 'required|array',
            'groups.*' => 'integer|exists:groups,id',
        ]);

        // Vind de match
        $match = Matches::findOrFail($id);

        // Combineer datum en tijd tot datetime
        $checkin = $request->input('date') . ' ' . $request->input('check-in-time');
        $kickoff = $request->input('date') . ' ' . $request->input('kick-off-time');
        $groupIds = $request->input('groups');
        $users = User::whereIn('group_id', $groupIds)->get()->map(function ($user) {
            return [
                'user_id' => $user->id,
                'presence' => false,
            ];
        });

        // Update velden
        $match->users = $users;

        $match->name = $request->input('name-match');
        $match->location = $request->input('location');
        $match->checkin_time = $checkin;
        $match->kickoff_time = $kickoff;
        $match->category = $request->input('category');
        $match->limit = $request->input('Limit');  // Hoofdletter omdat dat ook in je form zo is
        $match->comment = $request->input('comment');

        // Opslaan
        $match->save();

        // Redirect terug met succesmelding
        return redirect()->back()->with('success', 'Wedstrijd succesvol bijgewerkt.');
    }

    public function show()
    {
        $groups = Groups::withCount('users')->get();

        return view('admin.add-match', compact('groups'));
    }
}
