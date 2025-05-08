<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Matches;

use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{


    function index()
    {
        $userId = Auth::id(); // Same result
        $allMatches = Matches::all();
        // or: $userId = auth()->user()->id;

        return view('home', compact('userId', 'allMatches'));
    }
}
