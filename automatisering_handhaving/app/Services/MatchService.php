<?php

namespace App\Services;

use App\Models\Matches;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class MatchService
{
    public static function getMatchesForUser($userId, $category = null): array
    {
        \Illuminate\Support\Facades\Log::debug('getMatchesForUser called', ['userId' => $userId, 'category' => $category]);
        $allMatches = Matches::when($category, fn($query) => $query->where('category', $category))
            ->orderBy('checkin_time')
            ->get();

        $userMatches = $allMatches->filter(function ($match) use ($userId) {
            $users = json_decode($match->users, true);
            $contains = collect($users)->contains('user_id', $userId);
            \Illuminate\Support\Facades\Log::debug('Checking if user is in match', [
                'match_id' => $match->id,
                'user_in_match' => $contains,
                'users' => $users
            ]);
            return $contains;
        })->values();

        $playedMatches = [];

        $upcomingMatches = $userMatches->filter(function ($match) use (&$playedMatches, $userId) {
            $matchDate = Carbon::parse($match->kickoff_time);
            $users = json_decode($match->users, true);

            $wasPresent = collect($users)->contains(fn($user) => $user['user_id'] === $userId && $user['presence'] === true);
            \Illuminate\Support\Facades\Log::debug('Evaluating match for played/upcoming', [
                'match_id' => $match->id,
                'kickoff_time' => $match->kickoff_time,
                'isPast' => $matchDate->isPast(),
                'wasPresent' => $wasPresent,
                'userId' => $userId
            ]);

            if ($matchDate->isPast() && $wasPresent) {
                $playedMatches[] = $match;
                \Illuminate\Support\Facades\Log::debug('Added to playedMatches', ['match_id' => $match->id]);
                return false;
            }

            return !$matchDate->isPast();
        })->values();

        $availableMatches = $allMatches->filter(function ($match) use ($userId) {
            $matchDate = Carbon::parse($match->kickoff_time);
            $users = json_decode($match->users, true);

            $isInMatch = collect($users)->contains('user_id', $userId);

            \Illuminate\Support\Facades\Log::debug('Evaluating available match', [
                'match_id' => $match->id,
                'isInMatch' => $isInMatch,
                'isFuture' => $matchDate->isFuture()
            ]);

            return !$isInMatch && $matchDate->isFuture();
        })->values();

        \Illuminate\Support\Facades\Log::debug('Result counts', [
            'upcomingMatches' => $upcomingMatches->count(),
            'playedMatches' => count($playedMatches),
            'availableMatches' => $availableMatches->count()
        ]);

        return [
            'upcomingMatches' => $upcomingMatches->values(),
            'playedMatches' => collect($playedMatches)->values(),
            'availableMatches' => $availableMatches
        ];
    }
}
