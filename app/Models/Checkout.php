<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;

    // Указываем, какие поля могут быть массово присвоены
    protected $fillable = [
        'user_id',
        'cart_items',
        'total_price',

        'shipping_address',
        'billing_first_name',
        'billing_last_name',
        'billing_email',
        'billing_tel',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_city',
        'billing_country',
        'billing_zip_code',
        'order_notes',
    ];

    // Определение связи с пользователем
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*protected $casts = [
        'cart_items' => 'array', // Преобразует cart_items в массив
        'total_price' => 'decimal:2', // Преобразует total_price в десятичное число с 2 знаками после запятой
    ];*/
}
