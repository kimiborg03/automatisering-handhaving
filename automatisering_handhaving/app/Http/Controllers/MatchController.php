<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Groups;
use App\Models\Matches;
use App\Models\User;

use Illuminate\Http\Request;

class MatchController extends Controller
{

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

        $userData = $users->map(function ($user) {
            return [
                'user_id' => $user->id,
                'presence' => false,
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
            'users' => json_encode($users),
        ]);
        
        return redirect()->back()->with('success', 'Wedstrijd opgeslagen!');
    }


    public function show(){
        $groups = Groups::all();

        return view('add-match', compact('groups'));
    }
}
