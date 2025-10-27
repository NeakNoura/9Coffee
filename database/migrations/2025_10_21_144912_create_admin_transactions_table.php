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
   public function up(): void
{
    Schema::create('admin_transactions', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('admin_id');
        $table->decimal('amount', 15, 2);
        $table->string('method', 50);
        $table->string('status', 50)->default('pending');
        $table->timestamps();

        $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
    });
}

public function down(): void
{
    Schema::dropIfExists('admin_transactions');
}

};
