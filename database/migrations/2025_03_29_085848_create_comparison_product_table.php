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
        Schema::create('comparison_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comparison_id');
            $table->unsignedBigInteger('product_id');

            // Внешние ключи
            $table->foreign('comparison_id')->references('id')->on('comparisons')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparison_product');
    }
};
