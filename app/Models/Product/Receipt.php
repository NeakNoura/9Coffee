<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned
    protected $fillable = [
        'order_id',
        'payer_email',
        'payer_name',
        'amount',
        'currency',
    ];
}