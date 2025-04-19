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
        Schema::create('promotions', function (Blueprint $table) {
            $table->bigIncrements('promotion_id');
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('type', ['percentage_discount', 'product_specific_discount', 'conditional_discount']);
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });

        Schema::create('promotion_products', function (Blueprint $table) {
            $table->bigIncrements('promotion_product_id');
            $table->unsignedBigInteger('promotion_id');
            $table->unsignedBigInteger('product_id');
            $table->decimal('applied_discount', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('promotion_id')->references('promotion_id')->on('promotions')->onDelete('cascade');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promotion_products');
        Schema::dropIfExists('promotions');
    }

};
