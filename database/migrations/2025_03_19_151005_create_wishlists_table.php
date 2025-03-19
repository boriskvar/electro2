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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('product_id');

            // Поле для времени добавления в wishlist
            $table->timestamp('added_at')->useCurrent();

            // Стандартные временные метки (если нужны)
            $table->timestamps();

            // Устанавливаем внешний ключ на пользователей
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Устанавливаем внешний ключ на продукты
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
