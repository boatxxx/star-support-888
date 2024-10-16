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
        Schema::create('product_loadings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_record_id'); // อ้างอิงจากออเดอร์
            $table->unsignedBigInteger('product_id'); // สินค้าที่ขึ้นรถ
            $table->unsignedBigInteger('vehicle_id'); // รถที่บรรทุกสินค้า
            $table->unsignedBigInteger('user_id'); // ใครเป็นคนรับผิดชอบ
            $table->integer('quantity'); // จำนวนสินค้า
            $table->timestamps();

            // ไม่ใช้ Foreign Keys
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_loadings');
    }
};
