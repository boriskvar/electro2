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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable(); // Внешний ключ для родительской категории
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('name');
            $table->string('slug')->unique(); // Уникальный slug
            $table->text('description')->nullable(); // Поле для описания
            $table->string('image')->nullable();
            $table->boolean('active')->default(true); // Активная категория
            $table->integer('display_order')->default(1); // Порядок отображения
            $table->boolean('is_sale')->default(false); // Отметка о распродаже
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
