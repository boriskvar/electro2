<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryAttribute extends Model
{
    protected $fillable = ['category_id', 'attribute_name', 'attribute_type'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
