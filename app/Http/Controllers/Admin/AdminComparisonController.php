<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Comparison;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminComparisonController extends Controller
{
    // Список всех сравнений
    public function index()
    {
        $comparisons = Comparison::with('products')->paginate(10);  // Получаем все сравнения с продуктами
        $allProducts = Product::all();  // Получаем все продукты для добавления в сравнение

        return view('admin.comparisons.index', compact('comparisons', 'allProducts'));
    }

    // Создание нового сравнения
    public function store(Request $request)
    {
        $comparison = Comparison::create(['user_id' => $request->user_id]);
        return redirect()->route('admin.comparisons.index')->with('success', 'Сравнение создано.');
    }

    // Просмотр конкретного сравнения
    public function show(Comparison $comparison)
    {
        $comparison->load('products');
        return view('admin.comparisons.show', compact('comparison'));
    }

    // Удаление всего сравнения
    public function destroy(Comparison $comparison)
    {
        $comparison->delete();
        return redirect()->route('admin.comparisons.index')->with('success', 'Сравнение удалено.');
    }

    // Очистка списка сравнения (удаляет все товары)
    public function clear(Comparison $comparison)
    {
        $comparison->products()->detach();
        return redirect()->route('admin.comparisons.show', $comparison)->with('success', 'Сравнение очищено.');
    }

    public function addProduct(Request $request, Comparison $comparison)
    {
        $product = Product::find($request->input('product_id'));

        if ($product) {
            // Добавляем товар в сравнение
            $comparison->products()->attach($product);
            return redirect()->route('admin.comparisons.show', $comparison)->with('success', 'Товар успешно добавлен в сравнение');
        }

        return back()->with('error', 'Товар не найден');
    }
}
