<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class QuickViewController extends Controller
{
    public function show($productId)
    {
        $product = Product::with(['category', 'attributes'])->findOrFail($productId);
        $categoryAttributes = $product->category->attributes;

        // Собираем данные, как в сравнении
        $productData = [
            'product' => $product,
            'attributes' => $categoryAttributes->mapWithKeys(function ($attribute) use ($product) {
                return [$attribute->attribute_name => $product->attributes->where('id', $attribute->id)->first()->pivot->value ?? '—'];
            })->toArray()
        ];

        return view('web.quick-view.index', compact('product', 'categoryAttributes', 'productData'));
    }
}
