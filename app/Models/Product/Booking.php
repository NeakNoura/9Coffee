<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $table = "bookings";

    protected $fillable = [
       "user_id",
       "first_name",
       "last_name",
       "date",
       "time",
       "phone",
       "message",
       "status",
       
    ];
    public  $timestamps = true;
}