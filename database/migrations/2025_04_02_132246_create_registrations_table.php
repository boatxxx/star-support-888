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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('level');
            $table->string('course_type');
            $table->string('major');
            $table->integer('academic_year');
            $table->date('register_date');
            $table->string('receipt_path')->nullable();
            $table->boolean('is_keyed')->default(false);
            $table->timestamps();
        });
    
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
