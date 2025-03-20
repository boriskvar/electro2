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
        Schema::create('search_queries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // Внешний ключ для пользователя (поиск может быть и от неавторизованного пользователя)
            $table->string('query'); // Сам поисковый запрос
            $table->integer('results_count')->default(0); // Количество найденных результатов
            $table->string('search_location')->nullable(); // Опционально, например, для указания категории или раздела поиска
            $table->timestamp('searched_at'); // Время поиска
            $table->timestamps();

            // Связи
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('search_queries');
    }
};
