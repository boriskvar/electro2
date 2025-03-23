<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    // Указываем таблицу, с которой связана модель
    protected $table = 'wishlists';

    // Указываем, что эти поля могут быть массово заполнены
    protected $fillable = [
        'user_id',
        'product_id',
        'added_at',
    ];

    // Определяем связь с моделью User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Определяем связь с моделью Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
