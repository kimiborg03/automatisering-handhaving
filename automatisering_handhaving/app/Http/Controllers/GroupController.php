<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Groups;

class GroupController extends Controller
{
    public function addclass(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255', 
        ]);

            // Check if the class already exists
    if (Groups::where('name', $request->name)->exists()) {
        return redirect()->route('admin.classes')->with('error', "Er bestaat al een klas met de naam '{$request->name}'");
    }
        // create a new class
        Groups::create([
            'name' => $request->name,
        ]);

        // Redirect to class page
        return redirect()->route('admin.classes')->with('success', "Klas '{$request->name}' succesvol toegevoegd!");
    }

    public function deleteclass($id)
{
    // delete the class
    Groups::findOrFail($id)->delete();

    // return to class page
    return redirect()->route('admin.classes')->with('success', 'Klas succesvol verwijderd!');
}

public function updateclass(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);
        // Check if the class name already exists
        if (Groups::where('name', $request->name)->where('id', '!=', $id)->exists()) {
        return redirect()->route('admin.classes')->with('error', "Er bestaat al een klas met de naam '{$request->name}'");
    }
    // update the class
    $group = Groups::findOrFail($id);
    $group->update([
        'name' => $request->name,
    ]);

    // redirect to class page
    return redirect()->route('admin.classes')->with('success', 'Klas succesvol bijgewerkt!');
}
}