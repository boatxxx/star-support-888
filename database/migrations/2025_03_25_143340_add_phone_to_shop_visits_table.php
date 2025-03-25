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
        Schema::table('shop_visits', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('employee_id'); // เพิ่มคอลัมน์ phone
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_visits', function (Blueprint $table) {
            $table->dropColumn('phone'); // ลบคอลัมน์ phone ถ้าย้อนกลับ migration
        });
    }
};
