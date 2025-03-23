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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Название бренда
            $table->string('logo')->nullable(); // Логотип бренда (может быть null)
            $table->string('slug')->unique(); // ЧПУ (slug для SEO) для URL
            $table->text('description')->nullable(); // Описание бренда
            $table->integer('popularity')->nullable(); // Поле популярности бренда с возможностью null
            $table->timestamps(); // Временные метки создания и обновления
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
