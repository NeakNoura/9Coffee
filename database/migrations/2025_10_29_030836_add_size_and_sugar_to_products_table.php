<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
        {
            Schema::table('products', function (Blueprint $table) {
                $table->json('size_prices')->nullable(); // e.g. {"S":2.5,"M":3.0,"L":3.5}
                $table->boolean('allow_sugar')->default(true);
            });
        }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
