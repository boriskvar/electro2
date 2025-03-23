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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Внешний ключ для пользователя, может быть null
            $table->unsignedBigInteger('order_id')->nullable(); // Внешний ключ для заказа, может быть null
            $table->string('name')->nullable(); // Добавлено поле для имени контактного лица
            $table->string('address')->nullable(); // Поле адреса может быть необязательным
            $table->string('phone')->nullable(); // Поле телефона может быть необязательным
            $table->string('email')->unique(); // Уникальное поле для email
            $table->timestamps();

            // Внешние ключи
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Сначала удаляем внешние ключи
            $table->dropForeign(['user_id']);
            $table->dropForeign(['order_id']);
        });

        // Затем удаляем столбцы
        Schema::dropIfExists('contacts');
    }
};
