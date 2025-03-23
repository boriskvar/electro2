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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete(); // Внешний ключ для продукта
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // Для авторизованных пользователей
            $table->string('author_name')->nullable(); // Имя автора (нужно для анонимных)
            $table->string('email')->nullable(); // Email автора (нужно для анонимных)
            $table->unsignedTinyInteger('rating')->default(1); // Рейтинг (1-5)
            $table->text('review')->nullable(); // Текст отзыва
            $table->timestamps(); // Даты создания и обновления
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
