<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasFactory;

    // Определяем заполняемые поля
    protected $fillable = [
        'page_id', // связь с конкретной страницей
        'url', // поле для URL
        'parent_id', // для вложенных меню, если нужно
        'name', // название меню
        'category_id', // связь с категорией
        'custom_url',  // ссылка на элемент меню
        'position', // позиция в меню
        'is_active', // статус активности меню
        'slug', // slug для SEO
        'is_home',
        'menu_type',
        'type',
    ];

    // Определяем отношения
    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id'); // Связь с категорией
    }

    public function products()
    {
        return $this->hasMany(Product::class); // Предполагаем, что есть связь "один ко многим" с продуктами
    }

    // Связь с множеством страниц (один ко многим)
    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id'); // Связь с одной страницей
    }

    // Генерация slug при создании или обновлении записи
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($menu) {
            // Генерация slug, если оно пустое
            if (empty($menu->slug)) {
                $menu->slug = Str::slug($menu->name);
            }
        });

        static::updating(function ($menu) {
            // Не генерировать slug, если он уже задан вручную
            if (!$menu->isDirty('slug') || empty($menu->slug)) {
                $menu->slug = Str::slug($menu->name);
            }
        });
    }
}