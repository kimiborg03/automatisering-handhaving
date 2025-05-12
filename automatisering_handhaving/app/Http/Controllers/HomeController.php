<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matches;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{


    function index()
    {
        $userId = Auth::id();
        $allMatches = Matches::get()->sortBy('checkin_time'); // Haal alle op, filteren we later
        $playedMatches = [];

        // Gebruik filter om alleen toekomstige wedstrijden over te houden
        $upcomingMatches = $allMatches->filter(function ($match) use (&$playedMatches, $userId) {
            $matchDate = Carbon::parse($match->kickoff_time);
            if ($matchDate->isPast()) {
                $users = json_decode($match->users, true);

                $wasPresent = collect($users)->contains(function ($user) use ($userId) {
                    return $user['user_id'] === $userId && $user['presence'] === true; // Gebruik hier 'user_id'
                });

                if ($wasPresent) {
                    $playedMatches[] = $match;
                }

                return false; // Verwijderen uit upcoming
            }

            return true; // Behouden in upcoming

        });

        // Herindexeren
        $allMatches = $upcomingMatches->values();

        return view('home', compact('userId', 'allMatches', 'playedMatches'));
    }
}
