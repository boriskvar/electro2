<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComparisonProduct extends Model
{
    use HasFactory;

    protected $table = 'comparison_product';
    protected $fillable = ['comparison_id', 'product_id'];

    // Связь с товаром
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Связь с сравнением
    public function comparison()
    {
        return $this->belongsTo(Comparison::class);
    }
}
