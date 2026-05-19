<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enclosure;

class SelectEnclosureController extends Controller
{
    /**
     * Set active enclosure in session and redirect to dashboard.
     */

    public function index()
    {
        $enclosures = Enclosure::all();
        return view('enclosure.select', compact('enclosures'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'enclosure_id' => 'required|exists:enclosures,id',
            'name' => 'required|string|max:255',
        ]);

        // Cari enclosure
        $enclosure = Enclosure::findOrFail($request->enclosure_id);

        // Update data
        $enclosure->name = $request->name;

        // Simpan
        $enclosure->save();

        return redirect()->route('enclosure.select');
    }
}
