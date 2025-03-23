<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ApiCategoryController extends Controller
{
    // Вывод списка категорий
    public function index()
    {
        // Получаем только родительские категории
        $categories = Category::whereNull('parent_id')->with('children')->get();

        // Возвращаем JSON-ответ
        return response()->json([
            'success' => true, //Указывает, что запрос выполнен успешно.
            'data' => $categories //Содержит данные категорий
        ], 200);
    }

    // Показать форму для создания новой категории
    //В API обычно не возвращают HTML-формы, их нужно реализовывать на фронтенде.
    /* public function create()
    {
        $categories = Category::all(); // Получаем все категории
        return view('categories.create', compact('categories')); // Передаем переменную в представление
    } */

    // Сохранить новую категорию
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        $category = Category::create($request->all()); // Сохранение новой категории

        // Возвращаем данные о созданной категории
        return response()->json([
            'success' => true,
            'message' => 'Категория создана успешно!',
            'data' => $category
        ], 201);
    }

    // Показать одну категорию
    public function show(Category $category)
    {
        //dd($category->toArray());
        // Возвращаем JSON-ответ с данными категории
        return response()->json([
            'success' => true,
            'data' => $category
        ], 200);
    }

    // Показать форму для редактирования категории
    //В API обычно не возвращают HTML-формы, их нужно реализовывать на фронтенде.
    /* public function edit(Category $category)
    {
        $categories = Category::all(); // Получаем все категории для выпадающего списка
        return view('categories.edit', compact('category', 'categories'));
    } */

    // Обновить существующую категорию
    public function update(Request $request, Category $category)
    {
        // Валидация данных запроса
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
        ]);

        // Обновление категории только проверенными данными
        $category->update($validatedData);

        // Возвращаем JSON-ответ об успешном обновлении
        return response()->json([
            'success' => true,
            'message' => 'Категория обновлена успешно!',
            'data' => $category
        ], 200);
    }

    // Удалить категорию
    public function destroy(Category $category)
    {
        try {
            // Удаление категории
            $category->delete();
            
            // Возвращаем успешный ответ
            return response()->json([
                'success' => true,
                'message' => 'Категория удалена успешно!'
            ], 200);
        } catch (\Exception $e) {
            // Возвращаем сообщение об ошибке
            return response()->json([
                'success' => false,
                'message' => 'Произошла ошибка при удалении категории.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
