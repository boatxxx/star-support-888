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
        Schema::create('returns', function (Blueprint $table) {
            $table->id(); // รหัสการคืนสินค้า
            $table->unsignedBigInteger('sale_id'); // รหัสการขาย
            $table->date('return_date'); // วันที่ทำการคืนสินค้า
            $table->text('reason'); // สาเหตุการคืนสินค้า
            $table->timestamps(); // วันที่สร้าง, วันที่และเวลา
            $table->unsignedBigInteger('user_id'); // คนที่คืนสินค้า
            $table->unsignedBigInteger('shop_id'); // ร้านค้าทีคืนสินค้า


        });
        Schema::create('return_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('return_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity');
            $table->timestamps();


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
        Schema::dropIfExists('return_items');
    }
};
