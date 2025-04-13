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
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('name');                  // Название соцсети (Facebook, Email и т.д.)
            $table->string('url');                   // Ссылка (https://..., mailto:..., viber:// и т.д.)
            $table->string('icon_class')->nullable(); // CSS класс иконки (fa fa-facebook и т.п.)
            $table->string('type')->default('external');
            // Тип: external, email, messenger — можно использовать для фильтрации и логики
            $table->integer('position')->default(0); // Порядок отображения
            $table->boolean('open_in_new_tab')->default(true); // Открытие в новой вкладке
            $table->boolean('active')->default(true);          // Активна ли ссылка
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};
