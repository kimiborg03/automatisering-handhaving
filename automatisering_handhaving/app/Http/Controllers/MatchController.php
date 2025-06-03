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

        $deadline = $validated['deadline'] ?? Carbon::parse($checkinTime)->subDays(3)->format('Y-m-d H:i:s');

        Matches::create([
            'name' => $request->input('name-match'),
            'location' => $request->input('location'),
            'checkin_time' => $checkinTime,
            'kickoff_time' => $kickoffTime,
            'category' => $request->input('category'),
            'limit' => $request->input('Limit'),
            'deadline' => null,
            'comment' => $request->input('comment'),
            'users' => json_encode($userData),
            'groups' => $validated['groups'] ?? [],
        ]);

        return redirect()->route('admin.add-match')->with('success', "Wedstrijd '{$request->input('name-match')}' is succesvol toegevoegd!");
    }

    public function update(Request $request, $id)
    {
        logger()->info('--- Match Update Debug ---');
        logger()->info('Request data:', $request->all());

        $request->validate([
            'name-match' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'check-in-time' => 'required|date_format:H:i',
            'kick-off-time' => 'required|date_format:H:i',
            'category' => 'required|string',
            'Limit' => 'nullable|integer|min:1',
            'comment' => 'nullable|string|max:1000',
            'groups' => 'nullable|array',
            'groups.*' => 'integer|exists:groups,id',
        ]);

        $match = Matches::findOrFail($id);
        logger()->info('Loaded match:', $match->toArray());

        $checkin = $request->input('date') . ' ' . $request->input('check-in-time');
        $kickoff = $request->input('date') . ' ' . $request->input('kick-off-time');
        $groupIds = $request->input('groups');
        logger()->info('Group IDs:', ['group_ids' => $groupIds]);

        $users = collect();
        if (is_array($groupIds) && count($groupIds) > 0) {
            $users = User::whereIn('group_id', $groupIds)->get()->map(function ($user) {
                return [
                    'user_id' => $user->id,
                    'presence' => false,
                ];
            });
        }
        logger()->info('Users for match:', $users->toArray());

        $match->users = json_encode($users->values());
        $match->name = $request->input('name-match');
        $match->location = $request->input('location');
        $match->checkin_time = $checkin;
        $match->kickoff_time = $kickoff;
        $match->category = $request->input('category');
        $match->limit = $request->input('Limit');
        $match->comment = $request->input('comment');

        $saveSuccess = $match->save();
        logger()->info('Save success:', ['success' => $saveSuccess]);
        logger()->info('Updated match:', $match->toArray());

        return redirect()->back()->with('success', 'Wedstrijd succesvol bijgewerkt.');
    }

    public function show()
    {
        $groups = Groups::withCount('users')->get();
        return view('admin.add-match', compact('groups'));
    }

    public function showRegistrations($matchId)
    {
        $match = Matches::findOrFail($matchId);
        $users = json_decode($match->users, true) ?? [];

        $userIds = collect($users)->pluck('user_id')->all();
        $userDetails = User::whereIn('id', $userIds)->get();

        $groups = Groups::pluck('name', 'id');

        return view('matchregistrations', compact('match', 'users', 'userDetails', 'groups'));
    }

    public function updatePresence(Request $request, $matchId)
    {
        $match = Matches::findOrFail($matchId);
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
        $match = Matches::findOrFail($matchId);
        $users = json_decode($match->users, true) ?? [];
        $userIds = array_column($users, 'user_id');
        $userDetails = User::whereIn('id', $userIds)->orderBy('name')->get();
        $groups = Groups::pluck('name', 'id');

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

            foreach ($userDetails as $user) {
                fputcsv($handle, [
                    $user->name,
                    $groups[$user->group_id] ?? '-',
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
