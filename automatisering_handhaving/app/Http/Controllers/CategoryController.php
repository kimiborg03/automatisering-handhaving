<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matches;
use App\Services\MatchService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function show($category)
    {
        $allMatches = Matches::where('category', $category)->get(); // Pas dit aan naar jouw logica
        return view('category', compact('allMatches', 'category'));
    }

    public function loadMatches(Request $request)
    {
        $category = $request->input('category');
        $offset = $request->input('offset', 0);
        $userId = Auth::id();

        Log::info('Loading matches', [
            'category' => $category,
            'offset' => $offset,
            'user_id' => $userId
        ]);

        // Tijdelijk zonder filter
        $matches = Matches::where('category', $category)
            ->where('checkin_time', '>=', now())
            ->orderBy('checkin_time', 'asc')
            ->offset($offset)
            ->limit(12)
            ->get();


        return response()->json($matches);
    }
}