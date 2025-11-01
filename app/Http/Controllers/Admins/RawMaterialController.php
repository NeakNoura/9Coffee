<?php
namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product\Product;
use App\Models\RawMaterial;
use App\Models\Product\Order;


class RawMaterialController extends Controller
{
    // Show all raw materials
    public function index()
    {
        $rawMaterials = RawMaterial::orderBy('id', 'asc')->get();
        return view('admins.stock', compact('rawMaterials'));
    }

    // Update raw material quantity
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $material = RawMaterial::findOrFail($id);
        $material->quantity = $request->quantity;
        $material->save();

        return redirect()->route('admin.raw-material.stock')->with('success', 'Stock updated successfully!');
    }

    // Place an order using raw materials
    public function orderProduct(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity;

        foreach ($product->rawMaterials as $material) {
            if ($material->quantity < ($material->pivot->quantity_required * $quantity)) {
                return back()->with('error', $material->name . ' is not enough!');
            }
        }

        foreach ($product->rawMaterials as $material) {
            $material->quantity -= $material->pivot->quantity_required * $quantity;
            $material->save();
        }

        Order::create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $product->price * $quantity,
            'status' => 'Pending'
        ]);

        return back()->with('success', 'Order placed and stock updated!');
    }

// Show raw material stock
public function viewRawMaterials() {
    $rawMaterials = RawMaterial::all();
    return view('admins.stock', compact('rawMaterials'));
}


// Update raw material quantity
public function updateRawMaterial(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:0',
    ]);

    $material = RawMaterial::findOrFail($id);
    $material->quantity = $request->quantity;
    $material->save();

    return redirect()->route('admin.raw-material.stock')->with('success', 'Stock updated successfully!');
}

public function addQuantity(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    $product = Product::findOrFail($id);
    $product->quantity += $request->quantity;
    $product->save();

    return response()->json([
        'success' => true,
        'message' => "Added {$request->quantity} to {$product->name}",
        'new_quantity' => $product->quantity
    ]);
}




}
