<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->integer('quantity')->default(1)->after('product_id');
        $table->string('size')->nullable()->after('quantity');
        $table->integer('sugar')->nullable()->after('size'); // or decimal if you need grams
    });
}

public function down(): void
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropColumn(['quantity', 'size', 'sugar']);
    });
}

};
