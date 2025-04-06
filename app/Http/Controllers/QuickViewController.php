<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class QuickViewController extends Controller
{
    public function show($productId)
    {
        $product = Product::with(['category.attributes', 'attributes'])->findOrFail($productId);

        // Собираем характеристики по аналогии с сравнением товаров
        $attributes = $product->category->attributes->mapWithKeys(function ($categoryAttribute) use ($product) {
            $value = $product->attributes
                ->firstWhere('category_attribute_id', $categoryAttribute->id)
                ->value ?? '—'; // значение из pivot

            return [$categoryAttribute->attribute_name => $value];
        })->toArray();

        return view('web.quick-view.index', [
            'product' => $product,
            'attributes' => $attributes,
        ]);
    }
}
