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
        Schema::create('work_record_product', function (Blueprint $table) {
            $table->id(); // primary key ของตาราง
            $table->unsignedBigInteger('work_record_id');
            // ใส่เฉพาะ work_record_id ออเดอร์บันทึก
            $table->unsignedBigInteger('product_id'); // ใส่เฉพาะ product_id โดยไม่เชื่อมกับตารางอื่น
            $table->integer('quantity'); // จำนวนสินค้าที่ขนขึ้นรถ
            $table->unsignedBigInteger('created_by'); // ผู้บันทึกข้อมูล
            $table->timestamps(); // created_at และ updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_record_product');
    }
};
