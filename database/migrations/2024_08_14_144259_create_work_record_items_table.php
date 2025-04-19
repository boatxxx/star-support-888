<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('work_record_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_record_id');
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('quantity');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('work_record_id')->references('id')->on('work_records')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('work_record_items');
    }
};
