<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchQuery extends Model
{
    use HasFactory;

    // Указываем таблицу, если имя модели не совпадает с таблицей
    protected $table = 'search_queries';

    // Поля, которые можно массово заполнять
    protected $fillable = [
        'user_id',
        'query',
        'results_count',
        'search_location',
        'searched_at',
    ];

    /**
     * Связь с пользователем (один ко многим).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Дополнительные функции для удобства
     */

    // Возвращает форматированное время поиска
    public function getFormattedSearchedAtAttribute()
    {
        return $this->searched_at->format('Y-m-d H:i:s');
    }
}
