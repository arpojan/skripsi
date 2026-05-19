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
            'enclosure_id' => 'required|exists:enclosures,id'
        ]);

        // Save active enclosure to session
        session(['active_enclosure_id' => $request->enclosure_id]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Active enclosure selected.',
                'redirect' => route('dashboard', ['id' => $request->enclosure_id])
            ]);
        }

        return redirect()->route('dashboard', ['id' => $request->enclosure_id]);
    }
}
