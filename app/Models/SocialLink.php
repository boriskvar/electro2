<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SocialLink extends Model
{

    // Указываем таблицу, если имя модели не совпадает с таблицей
    protected $table = 'social_links';

    protected $fillable = [
        'name',
        'url',
        'icon_class',
        'type',
        'position',
        'open_in_new_tab',
        'active',

    ];
}
