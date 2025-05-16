<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matches;
use Carbon\Carbon;
use App\Services\MatchService;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{


    function index()
    {
        $userId = Auth::id();

        $matches = MatchService::getMatchesForUser($userId);

        return view('home', [
            'userId' => $userId,
            'allMatches' => $matches['upcomingMatches'],
            'playedMatches' => $matches['playedMatches']
        ]);
    }
}
