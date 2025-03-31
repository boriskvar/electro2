<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'sizes' => 'array', // Преобразование JSON в массив
        'colors' => 'array', // Преобразование JSON в массив
        'images' => 'array', // Преобразование JSON в массив
    ];

    // Разрешаем массовое заполнение этих полей
    protected $fillable = [
        'name',
        'slug', // Уникальный slug для SEO
        'description',
        'details', // Поле для детальной информации
        'price',
        'old_price', // Старая цена (если есть скидка)
        'in_stock', // Есть в наличии или нет
        'rating', // Средний рейтинг
        'reviews_count', // Количество отзывов
        'views_count', // Количество просмотров
        'colors', // Возможные цвета товара
        'sizes', // Возможные размеры товара
        'stock_quantity', // Количество товаров на складе
        'category_id', // Внешний ключ для категории
        'brand_id', // Внешний ключ для бренда (необязательно)
        'menu_id',
        'images', // Массив изображений
        'is_top_selling',
        'discount_percentage',
        'is_new',
        'position',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            $product->updateDiscount();
        });
    }

    public function updateDiscount()
    {
        if ($this->old_price && $this->price < $this->old_price) {
            $this->discount_percentage = round((($this->old_price - $this->price) / $this->old_price) * 100);
        } else {
            $this->discount_percentage = null; // Обнуляем, если нет скидки
        }
    }

    // Связь с категорией
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Связь с заказами (через таблицу order_items)
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items');
    }

    // Связь с отзывами
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Связь с брендом
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Связь с меню
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // Связь с характеристиками через промежуточную таблицу
    public function categoryAttributes()
    {
        return $this->belongsToMany(CategoryAttribute::class, 'product_category_attributes')
            ->withPivot('value');
    }

    public function attributes()
    {
        return $this->hasMany(ProductCategoryAttribute::class, 'product_id');
    }
}
