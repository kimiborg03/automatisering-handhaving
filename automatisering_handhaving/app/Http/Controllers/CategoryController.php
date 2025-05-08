<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matches;

class CategoryController extends Controller
{
    public function show($category)
    {

        // Retrieve matches that belong to the category
        $matches = Matches::where('category', $category)->get();
    
        // Return the category blade with the matches
        return view('category', compact('matches', 'category'));
    }
}