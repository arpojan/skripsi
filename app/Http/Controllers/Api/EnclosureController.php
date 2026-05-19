<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enclosure;

class EnclosureController extends Controller
{
    /**
     * Update enclosure settings like name, description, etc.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|nullable'
        ]);

        $enclosure = Enclosure::findOrFail($id);
        
        if ($request->has('name')) {
            $enclosure->name = $request->input('name');
        }
        if ($request->has('description')) {
            $enclosure->description = $request->input('description');
        }
        
        $enclosure->save();

        return response()->json([
            'success' => true,
            'message' => 'Enclosure updated successfully',
            'data' => $enclosure
        ]);
    }
}
