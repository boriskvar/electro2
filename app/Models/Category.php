<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Разрешаем массовое заполнение этих полей
    protected $fillable = ['name', 'slug', 'description',  'parent_id', 'image', 'active', 'display_order', 'is_sale'];

    // Связь с продуктами
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Метод для получения дочерних категорий
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Метод для получения родительской категории
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Связь со страницами
    public function pages()
    {
        return $this->hasMany(Page::class, 'category_id');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    // Связь с характеристиками категории
    public function attributes()
    {
        return $this->hasMany(CategoryAttribute::class);
    }
}
