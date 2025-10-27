<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product\Product; // Make sure the path is correct

class Cart extends Model
{
    use HasFactory;

    protected $table = "cart";

    protected $fillable = [
        "pro_id",
        "name",
        "image",
        "price",
        "user_id",
        "created_at",
        "updated_at",
    ];

    public $timestamps = true;

    // Add this relationship
    public function product()
    {
        return $this->belongsTo(Product::class, 'pro_id'); // 'pro_id' is the foreign key
    }
}