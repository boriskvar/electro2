<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Page;

class AdminCategoryController extends Controller
{
    // Вывод списка категорий
    public function index(Request $request)
    {
        // Получаем параметр сортировки из запроса, если он есть, по умолчанию сортируем по 'display_order'
        $sort = $request->input('sort', 'display_order'); 
    
        // Проверяем, является ли поле сортировки допустимым
        $allowedSortFields = ['name', 'slug', 'display_order', 'created_at'];
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'display_order'; // По умолчанию сортируем по 'display_order' если поле недопустимо
        }
    
        // Фильтрация по 'active' и 'is_sale'
        $active = $request->input('active');
        $isSale = $request->input('is_sale');
    
        $categories = Category::whereNull('parent_id')
            ->when($active !== null, function($query) use ($active) {
                $query->where('active', $active);
            })
            ->when($isSale !== null, function($query) use ($isSale) {
                $query->where('is_sale', $isSale);
            })
            ->orderBy($sort) // Применяем сортировку по выбранному полю
            ->with('children')
            ->get();
    
        return view('admin.categories.index', compact('categories'));
    }
    

    // Показать форму для создания новой категории
    public function create()
    {
        $categories = Category::all(); // Получаем все категории
        return view('admin.categories.create', compact('categories')); // Передаем переменную в представление
    }

    // Сохранить новую категорию
    public function store(Request $request)
    {
        // Валидация данных запроса
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Валидация изображения
            'active' => 'nullable|boolean', // Валидация для поля active
            'display_order' => 'nullable|integer', // Валидация для поля display_order
            'is_sale' => 'nullable|boolean', // Валидация для поля is_sale
        ]);
    
        // Обработка изображения, если оно загружено
        if ($request->hasFile('image')) {
            // Получаем файл изображения
            $image = $request->file('image');
            $imageName = $image->getClientOriginalName(); // Получаем оригинальное имя файла
            $image->storeAs('public/categories', $imageName); // Сохраняем с оригинальным именем в storage/app/public/categories
            $validatedData['image'] = $imageName; // Сохраняем имя файла в базе данных
        } else {
            $validatedData['image'] = null; // Если изображения нет, устанавливаем null
        }
    
        // Создание категории с новыми данными
        Category::create($validatedData);
    
        // Перенаправление с сообщением об успешном создании
        return redirect()->route('admin.categories.index')->with('success', 'Категория создана успешно!');
    }
    
    // Показать одну категорию
    public function show(Category $category)
    {
        //dd($category->toArray());
        return view('admin.categories.show', compact('category'));
    }

    // Показать форму для редактирования категории
    public function edit(Category $category)
    {
        $categories = Category::all(); // Получаем все категории для выпадающего списка
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    // Обновить существующую категорию
    public function update(Request $request, Category $category)
    {
        // Валидация данных запроса
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Валидация изображения
            'active' => 'nullable|boolean', // Валидация для поля active
            'display_order' => 'nullable|integer', // Валидация для поля display_order
            'is_sale' => 'nullable|boolean', // Валидация для поля is_sale
        ]);
    
        // Обработка изображения, если оно загружено
        if ($request->hasFile('image')) {
            // Удаление старого изображения, если оно существует
            if ($category->image) {
                $oldImagePath = public_path('storage/categories/' . $category->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath); // Удаление старого изображения
                }
            }
    
        // Сохранение нового изображения с оригинальным именем
        $image = $request->file('image');
        $imageName = $image->getClientOriginalName(); // Получаем оригинальное имя файла
        $image->storeAs('public/categories', $imageName); // Сохраняем с оригинальным именем в storage/app/public/categories
        $validatedData['image'] = $imageName; // Обновляем путь в базе данных (только имя файла)
    } else {
        // Если изображение не было загружено, не меняем поле image
        unset($validatedData['image']);
    }
    
        // Обновление категории с новыми данными
        $category->update($validatedData);
    
        // Перенаправление с сообщением об успехе
        return redirect()->route('admin.categories.index')->with('success', 'Категория обновлена успешно!');
    }
    

    // Удалить категорию
    public function destroy(Category $category)
    {
        try {
            // Удаление категории
            $category->delete();
            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            // Обработка ошибок при удалении
            return redirect()->route('admin.categories.index')->with('error', 'An error occurred while deleting the category.');
        }
    }
}