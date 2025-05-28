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
        $userId = Auth::id();

        $matches = MatchService::getMatchesForUser($userId);
        $matches = Matches::all(); // of met paginate()
        $groups = Groups::withCount('users')->get();
        $availableMatches = $matches['availableMatches'];
        $allMatches = Matches::where('category', $category)->get(); // Pas dit aan naar jouw logica
        return view('category', compact('allMatches', 'category', 'availableMatches', 'groups'));
    }

    public function loadMatches(Request $request)
    {

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

        $matchesQuery = Matches::where('checkin_time', '>=', now());

        if ($category !== null && $category !== '') {
            $matchesQuery->where('category', $category);
        }

        $matches = $matchesQuery
            ->orderBy('checkin_time', 'asc')
            ->offset($offset)
            ->limit(12)
            ->get();

        Log::info(DB::getQueryLog());

        return response()->json($matches);
    }
}