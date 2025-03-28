<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\CategoryAttribute;
use App\Models\ProductCategoryAttribute;
use Illuminate\Http\Request;

class AdminProductAttributeValueController extends Controller
{
    public function index()
    {
        // Получаем все значения характеристик товаров с необходимыми связями
        $productAttributes = ProductCategoryAttribute::with(['product', 'categoryAttribute'])->get();

        // dd($productAttributes); // Отладка для проверки данных
        // Передаем данные в представление
        return view('admin.product_attributes.index', compact('productAttributes'));
    }

    // Отображение формы для создания новой характеристики товара
    public function create()
    {
        // Получаем список всех товаров и категорий атрибутов
        $products = Product::all();
        $categoryAttributes = CategoryAttribute::all();

        return view('admin.product_attributes.create', compact('products', 'categoryAttributes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'category_attribute_id' => 'required|exists:category_attributes,id',
            'value' => 'required|string|max:255',
        ]);

        // Создаем новую запись в таблице product_category_attributes
        ProductCategoryAttribute::create([
            'product_id' => $request->product_id,
            'category_attribute_id' => $request->category_attribute_id,
            'value' => $request->value,
        ]);

        return redirect()->route('admin.product-attributes.index')->with('success', 'Значение характеристики добавлено');
    }

    public function show($id)
    {
        // Получаем атрибут по id
        $attribute = ProductCategoryAttribute::findOrFail($id);

        // Получаем связанную категорию атрибута
        $categoryAttribute = $attribute->categoryAttribute;

        // Получаем категорию товара (связь через продукт)
        $category = $attribute->product->category;

        // Передаем данные в представление
        return view('admin.product_attributes.show', compact('attribute', 'categoryAttribute', 'category'));
    }


    public function edit($id)
    {
        // Получаем нужное значение характеристики товара
        $productCategoryAttribute = ProductCategoryAttribute::find($id);

        // Проверяем, если не нашли характеристику
        if (!$productCategoryAttribute) {
            return redirect()->route('admin.product-attributes.index')->with('error', 'Характеристика не найдена.');
        }

        // Получаем связанные товары и характеристики
        $products = Product::all();  // Можете передавать список всех товаров
        $categoryAttributes = CategoryAttribute::all();  // Можете передавать все категории атрибутов

        // Передаем данные в представление
        return view('admin.product_attributes.edit', compact('productCategoryAttribute', 'products', 'categoryAttributes'));
    }




    public function update(Request $request, ProductCategoryAttribute $productAttribute)
    {
        $request->validate([
            'value' => 'required|string|max:255',
        ]);

        $productAttribute->update([
            'value' => $request->value,
        ]);

        return redirect()->route('admin.product-attributes.index')->with('success', 'Значение характеристики обновлено');
    }

    public function destroy(ProductCategoryAttribute $productAttribute)
    {
        $productAttribute->delete();
        return redirect()->route('admin.product-attributes.index')->with('success', 'Значение характеристики удалено');
    }
}
