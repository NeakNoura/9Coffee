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
        Schema::create('receipts', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('order_id');
    $table->string('payer_email');
    $table->string('payer_name');
    $table->decimal('amount', 10, 2);
    $table->string('currency')->default('USD');
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
        Schema::dropIfExists('receipts');
    }
};
