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
        Schema::create('promotion_conditions', function (Blueprint $table) {
            $table->bigIncrements('condition_id');
            $table->unsignedBigInteger('promotion_id');
            $table->enum('condition_type', ['buy_x_get_y', 'quantity_based', 'other']);
            $table->string('condition_value', 255)->nullable();
            $table->timestamps();

            $table->foreign('promotion_id')->references('promotion_id')->on('promotions')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotion_conditions');
    }
};
