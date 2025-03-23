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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable(); // ID родительского пункта для подменю
            $table->unsignedBigInteger('category_id')->nullable(); // ID категории (если меню ведет на категорию)
            $table->unsignedBigInteger('page_id')->nullable();  // или после другого поля, если нужно
            $table->string('menu_type')->default('main')->index(); // 'main' для основного меню, 'footer' для футера
            $table->string('type')->nullable();
            $table->boolean('is_home')->default(false);
            $table->string('name'); // Название пункта меню
            $table->string('slug')->unique(); // добавляем поле slug
            $table->boolean('is_active')->default(true); // Активен ли пункт меню
            $table->unsignedInteger('position')->default(0); // Позиция в меню
            $table->string('url')->nullable();  // укажите нужное место после какого поля
            $table->string('custom_url')->nullable(); // Произвольный URL


            $table->timestamps(); // Временные метки

            // Внешние ключи
            $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
