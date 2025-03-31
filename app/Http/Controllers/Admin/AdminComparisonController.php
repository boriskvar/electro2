<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Comparison;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminComparisonController extends Controller
{
    // Список всех сравнений
    public function index()
    {
        $comparisons = Comparison::with(['user', 'products'])->get();
        $users = User::all();
        $products = Product::all();

        return view('admin.comparisons.index', compact('comparisons', 'users', 'products'));
    }

    // Создание нового сравнения
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        // Получаем или создаем сравнение для пользователя
        $comparison = Comparison::firstOrCreate(['user_id' => $request->user_id]);

        // Добавляем товар в сравнение
        if (!$comparison->products()->where('product_id', $request->product_id)->exists()) {
            $comparison->products()->attach($request->product_id);
        }

        return redirect()->route('admin.comparisons.index')->with('success', 'Товар добавлен в сравнение');
    }

    // Просмотр конкретного сравнения
    public function show(Comparison $comparison)
    {
        $comparison->load('products');
        return view('admin.comparisons.show', compact('comparison'));
    }

    // Удаление всего сравнения
    public function destroy($id)
    {
        $comparison = Comparison::findOrFail($id);
        $comparison->delete();

        return redirect()->route('admin.comparisons.index')->with('success', 'Сравнение удалено');
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
