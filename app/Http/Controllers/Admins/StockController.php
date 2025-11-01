<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RawMaterial;

class StockController extends Controller
{

    public function index()
    {
        $rawMaterials = RawMaterial::all();
        return view('admins.stock', compact('rawMaterials'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|numeric|min:0',
        ]);

        $material = RawMaterial::findOrFail($id);
        $material->quantity = $request->quantity;
        $material->save();

        return redirect()->back()->with('success', 'Stock updated successfully!');
    }
}
