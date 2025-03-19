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
            $table->id();
            $table->unsignedBigInteger('category_id'); // Внешний ключ для категории
            $table->string('name');
            $table->string('slug')->unique(); // Уникальный slug для SEO
            $table->json('images')->nullable(); // Массив изображений (JSON-формат)
            $table->text('description')->nullable();
            $table->text('details')->nullable(); // Поле для детальной информации
            $table->decimal('price', 8, 2); // Основная цена
            $table->decimal('old_price', 8, 2)->nullable(); // Старая цена (если есть скидка)
            $table->decimal('discount_percentage', 3, 2)->nullable(); // Процент скидки
            $table->decimal('rating', 2, 1)->nullable(); // Средний рейтинг (например, 4.5)
            $table->integer('reviews_count')->default(0); // Количество отзывов
            $table->integer('views_count')->default(0); // Количество просмотров продукта
            $table->boolean('in_stock')->default(true); // Есть в наличии или нет
            $table->integer('stock_quantity')->default(0); // Количество товаров на складе
            $table->boolean('is_new')->default(false); // Новинка
            $table->boolean('is_top_selling')->default(false); // Лидер продаж
            $table->json('colors')->nullable(); // Возможные цвета товара(JSON-формат)
            $table->json('sizes')->nullable(); // Возможные размеры товара(JSON-формат)
            $table->unsignedBigInteger('brand_id')->nullable(); // Внешний ключ для бренда (необязательно)
            $table->unsignedBigInteger('menu_id')->nullable(); // Внешний ключ для меню (необязательно)
            $table->unsignedSmallInteger('position')->nullable(); // Позиция для сортировки
            $table->timestamps();

            // Внешние ключи
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('set null');
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
