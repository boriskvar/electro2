<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comparison extends Model
{
    use HasFactory;

    protected $table = 'comparisons';
    protected $fillable = ['user_id'];

    // Связь "много к одному" с пользователями
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Связь "многие ко многим" с продуктами
    public function products()
    {
        return $this->belongsToMany(Product::class, 'comparison_product', 'comparison_id', 'product_id');
    }
}
