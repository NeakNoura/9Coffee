<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('user_id');
    $table->unsignedBigInteger('product_id');
    $table->string('first_name');
    $table->string('last_name');
    $table->string('state')->nullable();
    $table->string('address');
    $table->string('city')->nullable();
    $table->string('zip_code')->nullable();
    $table->string('phone');
    $table->string('email');
    $table->decimal('price', 10, 2);
    $table->string('status')->default('pending');
    $table->string('payment_status')->default('pending');
    $table->timestamps();

});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
