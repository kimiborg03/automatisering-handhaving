<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matches;

class CategoryController extends Controller
{
    public function show($category)
    {
        return view('category', compact('category'));
    }
    
    public function loadMatches(Request $request)
    {
        $category = $request->input('category');
        $offset = $request->input('offset', 0);
    
        $matches = Matches::where('category', $category)
        // load the matches  based on check in time
        ->where('checkin_time', '>=', now())
        ->orderBy('checkin_time', 'asc')
        ->offset($offset)
        ->limit(12)
        ->get();
    
        return response()->json($matches);
    }
    
}