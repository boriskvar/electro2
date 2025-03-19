<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id', // Если у вас есть поле для связи с пользователем
        'author_name',
        'email',
        'rating',
        'review_text'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id'); // product_id — внешний ключ в таблице reviews
        //return $this->belongsTo(Product::class);
    }

    public function user() // Добавьте это, если у вас есть связь с пользователем
    {
        return $this->belongsTo(User::class);
    }
}