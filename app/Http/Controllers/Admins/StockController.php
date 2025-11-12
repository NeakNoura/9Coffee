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

    // Return JSON for AJAX
    if($request->ajax()){
        return response()->json([
            'success' => true,
            'new_quantity' => $material->quantity
        ]);
    }

    return redirect()->back()->with('success', 'Stock updated successfully!');
}

}
