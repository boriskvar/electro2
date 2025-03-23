<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    // Укажите имя таблицы
    protected $table = 'cart';

    // Укажите, если у вас есть поля, которые не являются временными метками
    public $timestamps = false;

    // Поля, которые можно заполнять массово
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price_x_quantity'
    ];

    // Связь с таблицей 'users'
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с таблицей 'products'
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getDiscountPercentageAttribute()
    {
        return $this->product->discount_percentage ?? 0; // Значение по умолчанию, если скидка не указана
    }
}
