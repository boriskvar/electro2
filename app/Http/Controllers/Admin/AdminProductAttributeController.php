<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductAttribute;
use App\Http\Controllers\Controller;

class AdminProductAttributeController extends Controller
{
    public function index()
    {
        // Получаем все атрибуты
        $productAttribute = ProductAttribute::all();
        return view('admin.product_attributes.index', compact('productAttribute'));
    }

    public function create()
    {
        $products = Product::all();  // Получаем все товары
        return view('admin.product_attributes.create', compact('products'));
    }

    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'product_id' => 'required|exists:products,id', // Проверяем, что product_id существует в таблице продуктов
            'attribute_name' => 'required|string|max:255',  // Название характеристики
            'attribute_value' => 'required|string|max:255', // Значение характеристики
        ]);

        // Сохраняем характеристику товара
        $productAttribute = ProductAttribute::create([
            'product_id' => $request->product_id,
            'attribute_name' => $request->attribute_name,
            'attribute_value' => $request->attribute_value,
        ]);

        // Проверка успешного создания записи
        if ($productAttribute) {
            return redirect()->route('admin.product-attributes.index')->with('success', 'Характеристика добавлена!');
        }

        // В случае неудачи можно вернуть ошибку
        return back()->with('error', 'Ошибка при добавлении характеристики товара!');
    }


    public function show(ProductAttribute $productAttribute)
    {
        // Загружаем продукт, к которому относится характеристика
        $product = $productAttribute->product;

        return view('admin.product_attributes.show', compact('productAttribute', 'product'));
    }

    public function edit(ProductAttribute $productAttribute)
    {
        // Получаем продукт, связанный с характеристикой
        $product = $productAttribute->product; // Предполагается, что у тебя есть связь в модели ProductAttribute

        return view('admin.product_attributes.edit', compact('productAttribute', 'product'));
    }

    public function update(Request $request, ProductAttribute $productAttribute)
    {
        // Валидация данных
        $request->validate([
            'attribute_name' => 'required|string|max:255', // Название характеристики товара
            'attribute_value' => 'required|string|max:255', // Значение характеристики товара
        ]);

        // Обновляем только необходимые поля
        $productAttribute->update([
            'attribute_name' => $request->attribute_name,
            'attribute_value' => $request->attribute_value,
        ]);

        // Проверка успешного обновления
        return redirect()->route('admin.product-attributes.index')->with('success', 'Характеристика обновлена!');
    }


    public function destroy(ProductAttribute $productAttribute)
    {
        $productAttribute->delete();
        return redirect()->route('admin.product-attributes.index')->with('success', 'Характеристика удалена!');
    }
}
