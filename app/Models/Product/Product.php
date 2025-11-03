<?php

namespace App\Models\Product;
use App\Models\RawMaterial;
use App\Models\ProductType;
use App\Models\SubType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Product extends Model
{
    use HasFactory;
    protected $table = "products";

protected $fillable = [
    "name",
    "image",
    "price",
    "description",
    "product_type_id", // changed from "type" string
    "sub_type_id",     // new sub-type column
    "quantity",
];

public function type() {
    return $this->belongsTo(ProductType::class, 'product_type_id');
}

public function subType() {
    return $this->belongsTo(SubType::class, 'sub_type_id');
}


     // Relationship to raw materials
public function rawMaterials()
{
    return $this->belongsToMany(RawMaterial::class, 'product_raw_material')
                ->withPivot('quantity_required')
                ->withTimestamps();
}

public function deductIngredients(int $qty)
{
    foreach ($this->rawMaterials as $raw) {
        $requiredQty = $raw->pivot->quantity_required * $qty;
        $raw->decrement('quantity', $requiredQty);
    }
}



    // Relationship to orders
    public function orders()
    {
        return $this->hasMany(\App\Models\Product\Order::class);
    }
    public  $timestamps = false;

// In App\Models\Product\Product.php
public function getAvailableStockAttribute()
{
    // If product has no raw materials, fallback to product quantity
    if ($this->rawMaterials->isEmpty()) {
        return $this->quantity ?? 0;
    }

    $minStock = null;

    foreach ($this->rawMaterials as $material) {
        $possible = floor($material->quantity / $material->pivot->quantity_required);

        if ($minStock === null || $possible < $minStock) {
            $minStock = $possible;
        }
    }

    return $minStock ?? 0;
}


public function updateStock(Request $request, $id){
    $request->validate([
        'quantity' => 'required|integer|min:0'
    ]);

    $product = Product::findOrFail($id);
    $product->quantity = $request->quantity;
    $product->save();

    return response()->json(['success' => true, 'quantity' => $product->quantity]);
}















}
