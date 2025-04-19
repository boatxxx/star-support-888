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
        Schema::create('inventory_loads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_record_id'); // รหัสงานที่สินค้าถูกโหลดกลับ
            $table->unsignedBigInteger('product_id'); // รหัสสินค้า
            $table->integer('quantity'); // จำนวนสินค้าที่ถูกโหลดกลับ
            $table->unsignedBigInteger('warehouse_id'); // รหัสคลังสินค้าที่สินค้าถูกโหลดกลับ
            $table->unsignedBigInteger('created_by'); // รหัสผู้ใช้งานที่ทำการโหลด
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_loads');
    }
};
