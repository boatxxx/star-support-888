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
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('product_id');
            $table->text('product_id1')->nullable();
            $table->integer('Warehouse')->length(99);
            $table->string('name', 99);
            $table->string('description', 255);
            $table->decimal('price', 8, 2);
            $table->integer('stock');
            $table->date('expiration_date');
            $table->unsignedBigInteger('category_id'); // เพิ่มฟิลด์ category_id
            $table->timestamps();

            // กำหนดความสัมพันธ์กับตาราง categories
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
