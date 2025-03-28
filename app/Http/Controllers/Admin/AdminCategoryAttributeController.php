<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;  // Подключаем модель Category
use App\Models\CategoryAttribute;  // Подключаем модель CategoryAttribute

class AdminCategoryAttributeController extends Controller
{
    public function index()
    {
        // Получаем все атрибуты категорий
        $categoryAttributes = CategoryAttribute::all();
        return view('admin.category_attributes.index', compact('categoryAttributes'));
    }

    public function create()
    {
        // Получаем все категории
        $categories = Category::all();
        return view('admin.category_attributes.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'category_id' => 'required|exists:categories,id', // Проверяем, что category_id существует в таблице категорий
            'attribute_name' => 'required|string|max:255',  // Название характеристики
            'attribute_type' => 'required|string|max:255', // Значение характеристики
        ]);

        // Сохраняем характеристику категории
        $categoryAttribute = CategoryAttribute::create([
            'category_id' => $request->category_id,
            'attribute_name' => $request->attribute_name,
            'attribute_type' => $request->attribute_type,
        ]);

        // Проверка успешного создания записи
        if ($categoryAttribute) {
            return redirect()->route('admin.category-attributes.index')->with('success', 'Характеристика категории добавлена!');
        }

        // В случае неудачи можно вернуть ошибку
        return back()->with('error', 'Ошибка при добавлении характеристики категории!');
    }

    public function show(CategoryAttribute $categoryAttribute)
    {
        // Получаем категорию атрибута
        $category = $categoryAttribute->category;

        // Получаем товар, если есть
        $product = $category ? $category->product : null;

        // Передаем все данные в представление
        return view('admin.category_attributes.show', compact('categoryAttribute', 'category', 'product'));
    }




    public function edit(CategoryAttribute $categoryAttribute)
    {
        // Получаем категорию, связанную с характеристикой
        $category = $categoryAttribute->category; // Предполагается, что у тебя есть связь в модели CategoryAttribute

        return view('admin.category_attributes.edit', compact('categoryAttribute', 'category'));
    }

    public function update(Request $request, CategoryAttribute $categoryAttribute)
    {
        // Валидация данных
        $request->validate([
            'attribute_name' => 'required|string|max:255', // Название характеристики категории
            'attribute_type' => 'required|string|max:255', // Значение характеристики категории
        ]);

        // Обновляем только необходимые поля
        $categoryAttribute->update([
            'attribute_name' => $request->attribute_name,
            'attribute_type' => $request->attribute_type,
        ]);

        // Проверка успешного обновления
        return redirect()->route('admin.category-attributes.index')->with('success', 'Характеристика категории обновлена!');
    }

    public function destroy(CategoryAttribute $categoryAttribute)
    {
        // Удаление атрибута категории
        $categoryAttribute->delete();
        return redirect()->route('admin.category-attributes.index')->with('success', 'Характеристика категории удалена!');
    }
}
