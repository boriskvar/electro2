<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['category_id', 'attribute_name', 'attribute_type'];

    // Связь с категорией
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Связь с товарами через таблицу product_category_attributes
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_attributes')
            ->withPivot('value') // Значение характеристики для конкретного товара
            ->withTimestamps(); // Автоматическое обновление времени создания и изменения
    }
}
