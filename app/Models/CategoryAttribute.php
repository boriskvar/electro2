<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryAttribute extends Model
{
    protected $fillable = ['category_id', 'attribute_name', 'attribute_type'];

    // Связь с категорией
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category_attributes')
            ->withPivot('value'); // Значение характеристики
    }
}
