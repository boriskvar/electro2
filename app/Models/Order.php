<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Разрешаем массовое заполнение этих полей
    protected $fillable = [
        'user_id',
        'total_price',
        'shipping_price',
        'order_number',

        'status', // Например: "Выполнен", "В процессе выполнения", "Отменен"
        'payment_method',
        'payment_description',
        'payment_status',

        'first_name',
        'last_name',
        'email',
        'city',
        'country',
        'zip_code',
        'tel',
        'address',


        'dif_first_name',
        'dif_last_name',
        'dif_email',
        'dif_city',
        'dif_country',
        'dif_zip_code',
        'dif_tel',
        'dif_address',

        'shipping_status',
        'order_notes',
        'order_date',
        'delivery_date',
    ];

    // Связь с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с элементами заказа
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Связь с продуктами через OrderItem
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items')->withPivot('quantity', 'price');
    }

    // Связь с контактами
    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    // Связь с платежами (многие платежи принадлежат одному заказу)
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Добавляем нужные поля в $casts для автоматического преобразования в даты (После этого поля будут автоматически конвертироваться в объекты Carbon, и вы сможете использовать метод format:)
    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
    ];

    // Геттер для извлечения изображений
    public function getDecodedImagesAttribute()
    {
        return is_string($this->images) ? json_decode($this->images, true) : $this->images;
    }
}
