<?php

namespace App\Services;

use App\Models\Matches;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class MatchService
{
    public static function getMatchesForUser($userId, $category = null): array
    {
        $allMatches = Matches::when($category, function ($query) use ($category) {
                return $query->where('category', $category);
            })
            ->orderBy('checkin_time')
            ->get();

        $playedMatches = [];

        $upcomingMatches = $allMatches->filter(function ($match) use (&$playedMatches, $userId) {
            $matchDate = Carbon::parse($match->kickoff_time);
            $users = json_decode($match->users, true);

            $wasPresent = collect($users)->contains(function ($user) use ($userId) {
                return $user['user_id'] === $userId && $user['presence'] === true;
            });

            if ($matchDate->isPast() && $wasPresent) {
                $playedMatches[] = $match;
                return false;
            }

            return !$matchDate->isPast();
        });

        return [
            'upcomingMatches' => $upcomingMatches->values(),
            'playedMatches' => collect($playedMatches)->values()
        ];
    }

    
}
