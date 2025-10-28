<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = "orders";
protected $fillable = [
    "first_name",
    "last_name",
    "state",
    "address",
    "city",
    "zip_code",
    "phone",
    "email",
    "price",
    "user_id",
    "status",
    "product_id",
    "payment_status",
    "payment_method",
];


    public $timestamps = true;

    // 🔹 Add the relationship here
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }


}
