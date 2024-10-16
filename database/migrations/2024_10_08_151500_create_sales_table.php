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
        Schema::create('sales', function (Blueprint $table) {
            $table->id(); // ID ของการขาย
            $table->bigInteger('shop_id')->unsigned(); // ID ของร้านค้า
            $table->bigInteger('product_id')->unsigned(); // ID ของสินค้า
            $table->decimal('total_price', 10, 2); // ราคาสุทธิ
            $table->date('sale_date'); // วันที่ขาย
            $table->bigInteger('user_id')->unsigned(); // ID ของผู้ขาย
            $table->timestamps(); // วันที่สร้างและอัปเดต
            $table->bigInteger('promotion_id')->nullable(); // Allow NULL values
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
