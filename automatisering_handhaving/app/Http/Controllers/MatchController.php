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


    // function to show the page for adding a match
    public function show(){
        $groups = Groups::withCount('users')->get();

        return view('admin.add-match', compact('groups'));
    }


// function for page with registrations for a match
public function showRegistrations($matchId)
{
    $match = \App\Models\Matches::findOrFail($matchId);
    $users = json_decode($match->users, true) ?? [];

    $userIds = collect($users)->pluck('user_id')->all();
    $userDetails = \App\Models\User::whereIn('id', $userIds)->get();

    $groups = \App\Models\Groups::pluck('name', 'id');

    return view('matchregistrations', compact('match', 'users', 'userDetails', 'groups'));
}


// function to update the presence of a user in a match
public function updatePresence(Request $request, $matchId)
{
    $match = \App\Models\Matches::findOrFail($matchId);
    $users = json_decode($match->users, true) ?? [];

    foreach ($users as &$user) {
        if ($user['user_id'] == $request->user_id) {
            $user['presence'] = (bool)$request->presence;
            break;
        }
    }

    $match->users = json_encode($users);
    $match->save();

    return response()->json(['success' => true]);
}


public function exportExcel($matchId)
{
    // check if the user is authenticated
    $match = \App\Models\Matches::findOrFail($matchId);
    $users = json_decode($match->users, true) ?? [];
    $userIds = array_column($users, 'user_id');
    $userDetails = \App\Models\User::whereIn('id', $userIds)->orderBy('name')->get();
    $groups = \App\Models\Groups::pluck('name', 'id');

    // name the file
    $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', strtolower($match->name));
    $filename = 'deelnemers_' . $cleanName . '_' . now()->format('Ymd_His') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    $callback = function() use ($userDetails, $groups) {
        $handle = fopen('php://output', 'w');

        fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
        fputcsv($handle, ['Naam', 'Klas']);

        // Loop user details and write
        foreach ($userDetails as $user) {
            fputcsv($handle, [
                $user->name,
                $groups[$user->group_id] ?? '-',
            ]);
        }
        fclose($handle);
    };
    
    // Return the response as a stream
    return response()->stream($callback, 200, $headers);
}
    
}

