<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $casts = [
        'sizes' => 'array', // преобразование JSON в массив
        'colors' => 'array', // преобразование JSON в массив(чтоб обращаться к цвету напрямую, как к массиву: $image = $product->images[0];)
        'images' => 'array', // преобразование JSON в массив (Теперь можете обращаться к изображениям напрямую, как к массиву: $image = $product->images[0];)
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
        // Новые поля
        'is_top_selling',
        'discount_percentage',
        'is_new',
        'position',
    ];

    public static function boot()
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
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Связь с заказами (через таблицу order_items)
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id', 'id'); // product_id это внешний ключ в таблице reviews
    }

    // Связь с моделью Brand (обратная связь)
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id'); // Каждый продукт принадлежит одному бренду
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class); // Продукт принадлежит одному меню
    }
}