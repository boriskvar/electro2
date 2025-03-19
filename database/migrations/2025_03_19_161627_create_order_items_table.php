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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete(); // Внешний ключ для заказа
            $table->foreignId('product_id')->constrained()->cascadeOnDelete(); // Внешний ключ для продукта    
            $table->unsignedInteger('quantity'); // Количество товара, если не предполагаются отрицательные значения
            $table->decimal('price_x_quantity', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
