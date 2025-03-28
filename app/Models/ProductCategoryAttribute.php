<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategoryAttribute extends Model
{
    // Указываем имя таблицы
    protected $table = 'product_category_attributes';

    // Определяем, какие поля могут быть массово присвоены (fillable)
    protected $fillable = ['value', 'product_id', 'category_attribute_id'];

    // Связь с продуктом
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Связь с категорией атрибутов
    public function categoryAttribute()
    {
        return $this->belongsTo(CategoryAttribute::class, 'category_attribute_id');
    }
}
