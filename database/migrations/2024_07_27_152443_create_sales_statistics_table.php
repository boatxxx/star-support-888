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
        Schema::create('sales_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->decimal('total_sales', 8, 2);
            $table->decimal('total_returns', 8, 2);
            $table->timestamps();
            $table->boolean('paymentCheck')->default(false); // ตั้งค่าเริ่มต้นเป็น false


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_statistics');
    }
};
