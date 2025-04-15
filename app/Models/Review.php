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
        'review'
    ];

    public function product()
    {
        // Используем withTrashed, чтобы получить удалённые товары
        return $this->belongsTo(Product::class, 'product_id', 'id');
        // return $this->belongsTo(Product::class, 'product_id', 'id')->withTrashed();
    }

    public function user() // Добавьте это, если у вас есть связь с пользователем
    {
        return $this->belongsTo(User::class);
    }
}
