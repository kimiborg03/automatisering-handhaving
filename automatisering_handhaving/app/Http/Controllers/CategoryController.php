<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matches;
use App\Models\Groups;
use App\Services\MatchService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function show($category)
    {
        Log::info('Category is ' . $category);

        $userId = Auth::id();
        if ($category === null) {
            $groups = Groups::withCount('users')->get();
            $matches = MatchService::getMatchesForUser($userId);

            return view('home', [
                'userId' => $userId,
                'groups' => $groups,
                'allMatches' => $matches['upcomingMatches'],
                'playedMatches' => $matches['playedMatches'],
                'availableMatches' => $matches['availableMatches'],
            ]);
        } elseif ($category === 'all' && Auth::user() && Auth::user()->role === 'admin') {
            // Admin: Load ALL matches, not just those available to the user
            $groups = Groups::withCount('users')->get();
            $allMatches = Matches::all();
            $availableMatches = Matches::all(); // Show all matches as available for admin
            Log::info('Admin viewing all matches:', $allMatches->toArray());
            return view('category', compact('allMatches', 'category', 'availableMatches', 'groups'));
        } else {
            $matchData = MatchService::getMatchesForUser($userId);
            $groups = Groups::withCount('users')->get();
            // Ensure the filter matches the actual structure of availableMatches
            $availableMatches = collect($matchData['availableMatches'])->filter(function ($match) use ($category) {
                // Adjust 'category' to the correct property if needed
                return isset($match['category']) && $match['category'] == $category;
            })->values();
            $allMatches = Matches::where('category', $category)->get();
            return view('category', compact('allMatches', 'category', 'availableMatches', 'groups'));
        }
    }

    // Define a constant for matches per batch to make it easy to change
    const MATCHES_PER_BATCH = 6;

    public function loadMatches(Request $request)
    {
        try {
            $category = $request->input('category');
            if ($category === 'null') {
                $category = null;
            }
            $offset = $request->input('offset', 0);
            $userId = Auth::id();

            Log::info('Loading matches', [
                'category' => $category,
                'offset' => $offset,
                'user_id' => $userId
            ]);

            DB::enableQueryLog();

            if ($category === 'all') {
                // Admin: Load ALL matches ever
                $matches = Matches::orderBy('checkin_time', 'asc')
                    ->offset($offset)
                    ->limit(self::MATCHES_PER_BATCH)
                    ->get();
            } else {
                $matchesQuery = Matches::where('checkin_time', '>=', now());

                if ($category !== null && $category !== '') {
                    $matchesQuery->where('category', $category);
                    $matches = $matchesQuery
                        ->orderBy('checkin_time', 'asc')
                        ->offset($offset)
                        ->limit(self::MATCHES_PER_BATCH)
                        ->get();
                } else {
                    // Haal alle toekomstige wedstrijden op en filter lokaal op user_id in JSON
                    $matches = $matchesQuery
                        ->orderBy('checkin_time', 'asc')
                        ->get()
                        ->filter(function ($match) use ($userId) {
                            $users = is_string($match->users) ? json_decode($match->users, true) : $match->users;
                            return collect($users)->pluck('user_id')->contains($userId);
                        })
                        ->values()
                        ->slice($offset, self::MATCHES_PER_BATCH); // paginate lokaal
                }
            }

            Log::info(DB::getQueryLog());

            return response()->json($matches);
        } catch (\Exception $e) {
            Log::error('Error loading matches: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Server error loading matches.'], 500);
        }
    }
}
