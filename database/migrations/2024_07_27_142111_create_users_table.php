<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {

        Schema::create('users', function (Blueprint $table) {

            $table->bigIncrements('user_id'); // ใช้ user_id เป็น primary key
            $table->string('name', 255); // ชื่อผู้ใช้งาน varchar 255
            $table->string('email', 255)->unique(); // อีเมล varchar 255
            $table->unsignedBigInteger('role_id'); // Role ID
            $table->string('status', 255); // สถานะผู้ใช้งาน varchar 255
            $table->string('password', 255); // รหัสผ่าน Varchar 255
            $table->string('img_user', 255)->nullable(); // รูปภาพยูเซอร์ Varchar 255
            $table->timestamps(); // สร้าง timestamps สำหรับ created_at และ updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }

};
