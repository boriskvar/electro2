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
        Schema::create('product_category_attributes', function (Blueprint $table) {
            $table->id();
            // Убедитесь, что ваши таблицы products и category_attributes существуют
            $table->foreignId('product_id')->constrained('products'); // Связь с таблицей products
            $table->foreignId('category_attribute_id')->constrained('category_attributes'); // Связь с таблицей category_attributes
            $table->string('value'); // Значение характеристики для товара
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_category_attributes');
    }
};
