<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comparison extends Model
{
    use HasFactory;

    protected $table = 'comparisons';
    protected $fillable = ['user_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'comparison_product', 'comparison_id', 'product_id');
    }
}
