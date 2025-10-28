<?php

namespace App\Models;

use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Model;

class RawMaterial extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'unit'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_raw_material')
                    ->withPivot('quantity_required')
                    ->withTimestamps();
    }
}
