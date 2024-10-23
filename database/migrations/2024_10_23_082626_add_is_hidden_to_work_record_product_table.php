<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('work_record_product', function (Blueprint $table) {
            $table->boolean('is_hidden')->default(false); // เพิ่มฟิลด์ is_hidden
        });
    }

    public function down()
    {
        Schema::table('work_record_product', function (Blueprint $table) {
            $table->dropColumn('is_hidden'); // ลบฟิลด์เมื่อ rollback
        });
    }

};
