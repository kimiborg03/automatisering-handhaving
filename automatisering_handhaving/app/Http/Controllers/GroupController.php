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

        // create a new class
        Groups::create([
            'name' => $request->name,
        ]);

        // Redirect to class page
        return redirect()->route('admin.classes')->with('success', 'Klas succesvol toegevoegd!');
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

    // update the class
    $group = Groups::findOrFail($id);
    $group->update([
        'name' => $request->name,
    ]);

    // redirect to class page
    return redirect()->route('admin.classes')->with('success', 'Klas succesvol bijgewerkt!');
}
}