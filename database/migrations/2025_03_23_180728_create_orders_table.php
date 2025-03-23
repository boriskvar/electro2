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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Внешний ключ для пользователя
            $table->decimal('total_price', 10, 2); // будет храниться с 10 цифрами, из которых 2 будут после запятой. Например, 99999999.99.
            $table->decimal('shipping_price', 10, 2)->default(0);
            $table->string('order_number')->unique(); // Уникальный номер заказа

            $table->string('status')->default('pending'); // Статус заказа("pending", "completed", "canceled")
            $table->string('payment_method')->nullable(); // Метод оплаты
            $table->text('payment_description')->nullable();
            $table->string('payment_status')->nullable(); // Статус оплаты

            // Адрес для выставления счета
            $table->string('first_name')->nullable(); // Имя для выставления счета
            $table->string('last_name')->nullable(); // Фамилия для выставления счета
            $table->string('email')->nullable(); // Email для выставления счета
            $table->string('city')->nullable(); // Город для выставления счета
            $table->string('country')->nullable(); // Страна для выставления счета
            $table->string('zip_code')->nullable(); // Почтовый индекс для выставления счета
            $table->string('tel')->nullable(); // Телефон для выставления счета
            $table->string('address')->nullable(); // Строка адреса для выставления счета

            // Адрес доставки
            $table->string('dif_first_name')->nullable(); // Имя для доставки
            $table->string('dif_last_name')->nullable(); // Фамилия для доставки
            $table->string('dif_email')->nullable(); // Email для доставки
            $table->string('dif_address')->nullable(); // Адрес для доставки
            $table->string('dif_city')->nullable(); // Город для доставки
            $table->string('dif_country')->nullable(); // Страна для доставки
            $table->string('dif_zip_code')->nullable(); // Почтовый индекс для доставки
            $table->string('dif_tel')->nullable(); // Телефон для доставки

            $table->string('shipping_status')->nullable(); // Статус доставки
            $table->text('order_notes')->nullable(); // Примечания к заказу

            // Дата заказа и доставки
            $table->timestamp('order_date')->nullable(); // Дата создания заказа
            $table->timestamp('delivery_date')->nullable(); // Ожидаемая дата доставки            
            $table->timestamps();

            // Внешний ключ
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Индексы
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
