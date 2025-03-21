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
        Schema::create('cart', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Делаем user_id необязательным
            $table->unsignedBigInteger('product_id');
            $table->integer('quantity')->default(1); // количество товаров
            $table->decimal('price_x_quantity', 10, 2); // Цена товара с учётом количества
            $table->timestamps(); // даты создания и обновления

            // Внешние ключи
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Индексы для быстрого поиска
            $table->index('user_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart');
    }
};
