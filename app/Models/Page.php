<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'slug',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Связь с меню  (один ко многим)
    public function menu()
    {
        return $this->hasOne(Menu::class, 'page_id'); // Связь с меню (если одно меню для одной страницы)
    }
}