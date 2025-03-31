<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comparison;
use App\Models\ComparisonProduct;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminComparisonProductController extends Controller
{
    // Список товаров в сравнении
    public function index(Comparison $comparison)
    {
        $products = $comparison->products;
        return view('admin.comparison_products.index', compact('comparison', 'products'));
    }

    // Добавление товара в сравнение
    public function store(Request $request, Comparison $comparison)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $comparison->products()->attach($request->product_id);
        return redirect()->route('admin.comparison_products.index', $comparison)->with('success', 'Товар добавлен.');
    }

    // Обновление данных о товаре в сравнении (если нужны доп. поля)
    public function update(Request $request, Comparison $comparison, Product $product)
    {
        // Если в pivot-таблице `comparison_product` есть доп. данные, их можно обновлять
        return redirect()->route('admin.comparison_products.index', $comparison)->with('success', 'Товар обновлен.');
    }

    // Удаление товара из сравнения
    public function destroy(Comparison $comparison, Product $product)
    {
        $comparison->products()->detach($product->id);
        return redirect()->route('admin.comparison_products.index', $comparison)->with('success', 'Товар удален.');
    }
}
