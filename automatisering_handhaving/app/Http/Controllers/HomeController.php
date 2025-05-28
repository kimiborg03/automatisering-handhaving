<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matches;
use App\Models\Groups;
use Carbon\Carbon;
use App\Services\MatchService;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{


    function index()
    {
        $userId = Auth::id();
        $matches = Matches::all();
        $groups = Groups::withCount('users')->get();
        $matches = MatchService::getMatchesForUser($userId);

        return view('home', [
            'userId' => $userId,
            'groups' => $groups,
            'allMatches' => $matches['upcomingMatches'],
            'playedMatches' => $matches['playedMatches'],
            'availableMatches' => $matches['availableMatches'],
        ]);
    }
}
