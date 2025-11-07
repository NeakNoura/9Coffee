<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable();
            $table->unsignedBigInteger('product_type_id'); // relation to ProductType
            $table->unsignedBigInteger('sub_type_id')->nullable(); // optional
            $table->integer('quantity')->default(0); // stock quantity
            $table->enum('status', ['draft','active','inactive'])->default('draft'); // workflow status
            $table->timestamps();

            $table->foreign('product_type_id')->references('id')->on('product_types')->onDelete('cascade');
            $table->foreign('sub_type_id')->references('id')->on('sub_types')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
