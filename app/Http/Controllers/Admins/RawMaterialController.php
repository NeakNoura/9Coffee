<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RawMaterial;

class RawMaterialController extends Controller
{
    public function update(Request $request, $id)
    {
        $material = RawMaterial::findOrFail($id);
        $material->update(['quantity' => $request->quantity]);

        return redirect()->back()->with('success', 'Raw material stock updated successfully.');
    }

}
