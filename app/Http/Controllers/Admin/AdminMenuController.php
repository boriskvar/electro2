<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;

use App\Models\Page;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminMenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Получаем все элементы меню, отсортированные по позиции
        $menus = Menu::orderBy('position')->get();


        // Возвращаем представление с меню
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        // Получаем родительские меню для выпадающего списка
        $parentMenus = Menu::all();
        $categories = Category::all(); // Получаем все категории
        $pages = Page::all(); // Получаем все стандартные страницы
        $isHome = false; // По умолчанию новое меню не является главной страницей

        // Возможные типы для футер-меню
        $footerTypes = [
            'information' => 'Information',
            'service' => 'Service',
            'categories' => 'Categories',
        ];

        return view('admin.menus.create', compact('parentMenus', 'categories', 'pages', 'isHome', 'footerTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'page_id' => 'nullable|exists:pages,id',
            'category_id' => 'nullable|required_without_all:page_id,custom_url|exists:categories,id',
            'custom_url' => 'nullable|required_without_all:page_id,category_id|string|url',
            'position' => 'required|integer',
            'parent_id' => 'nullable|exists:menus,id',
            'is_active' => 'required|boolean',
            'is_home' => 'sometimes|boolean',
            'menu_type' => 'required|in:main,footer',
            'type' => 'nullable|required_if:menu_type,footer|string|in:about,categories,information,service',
            'slug' => 'nullable|string|max:255',
        ]);

        // Подготовка данных для сохранения
        $data = [
            'name' => $request->name,
            'menu_type' => $request->menu_type,
            'position' => $request->position,
            'parent_id' => $request->parent_id,
            'is_active' => $request->is_active,
            'is_home' => $request->boolean('is_home'),
        ];

        // Добавляем type, если меню — footer
        if ($request->menu_type === 'footer' && $request->has('type')) {
            $data['type'] = $request->type;
        }

        // Генерация уникального slug, если не передан вручную
        if (!$request->filled('slug')) {
            $slug = Str::slug($request->name);
            $existingSlug = Menu::where('slug', $slug)->exists();
            $data['slug'] = $existingSlug ? $slug . '-' . time() : $slug;
        } else {
            $data['slug'] = $request->slug;
        }

        // Приоритет: страница -> категория -> URL
        if ($request->filled('page_id')) {
            $data['page_id'] = $request->page_id;
        } elseif ($request->filled('category_id')) {
            $data['category_id'] = $request->category_id;
        } elseif ($request->filled('custom_url')) {
            $data['url'] = $request->custom_url;
        }

        // Если установлена новая главная страница, сбрасываем старую
        if ($data['is_home']) {
            Menu::where('is_home', true)->update(['is_home' => false]);
        }

        // Создаём новое меню с полученными данными
        Menu::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Меню добавлено успешно.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        // Возвращаем представление с меню
        return view('admin.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        // Получаем родительские меню для выпадающего списка
        $parentMenus = Menu::all();
        // Получаем все категории
        $categories = Category::all();
        // Получаем все стандартные страницы
        $pages = Page::all();

        // Пример возможных типов для footer меню
        // $footerTypes = ['about us', 'categories', 'information', 'service',]; // Это можно сделать динамически, если нужно
        // Возможные типы для футер-меню
        $footerTypes = [
            'information' => 'Information',
            'service' => 'Service',
            'categories' => 'Categories',
        ];

        return view('admin.menus.edit', compact('menu', 'parentMenus', 'categories', 'pages', 'footerTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Валидация данных
        $request->validate([
            'name' => 'required|string|max:255',
            'menu_type' => 'required|in:main,footer',
            'category_id' => 'nullable|exists:categories,id',
            'page_id' => 'nullable|exists:pages,id',
            'custom_url' => 'nullable|string|url',
            'position' => 'required|integer',
            'parent_id' => 'nullable|exists:menus,id',
            'is_active' => 'required|boolean',
            'is_home' => 'nullable|boolean',
            'type' => 'nullable|required_if:menu_type,footer|string|in:about,categories,information,service',
        ]);

        // Находим меню по ID
        $menu = Menu::findOrFail($id);

        // Генерация нового слага, если его нет
        $newSlug = Str::slug($request->name);
        $existingSlugCount = Menu::where('slug', $newSlug)->where('id', '!=', $menu->id)->count();

        // Если слаг уже существует, добавляем уникальный постфикс
        if ($existingSlugCount > 0) {
            $newSlug = $newSlug . '-' . time();  // Можно использовать другой метод для уникальности
        }

        // Обновление данных меню
        $menu->name = $request->name;
        $menu->slug = $newSlug; // Устанавливаем уникальный слаг
        $menu->menu_type = $request->menu_type;
        $menu->position = $request->position;
        $menu->parent_id = $request->parent_id;
        $menu->is_active = $request->is_active;

        // Обновление поля is_home
        if ($request->has('is_home')) {
            $menu->is_home = $request->is_home;
        }

        // Обновление поля type для футер-меню
        if ($request->menu_type === 'footer' && $request->has('type')) {
            $menu->type = $request->type;
        }

        // Приоритет выбора: страница → категория → произвольный URL
        if ($request->page_id) {
            $menu->page_id = $request->page_id;
            $menu->category_id = null;  // Сбрасываем категорию, если выбрана страница
            $menu->url = null;          // Сбрасываем произвольный URL
        } elseif ($request->category_id) {
            $menu->category_id = $request->category_id;
            $menu->page_id = null;     // Сбрасываем страницу, если выбрана категория
            $menu->url = null;         // Сбрасываем произвольный URL
        } elseif ($request->custom_url) {
            $menu->url = $request->custom_url;
            $menu->category_id = null; // Сбрасываем категорию
            $menu->page_id = null;     // Сбрасываем страницу
        } else {
            $menu->category_id = null; // Если ничего не выбрано, сбрасываем категорию
            $menu->page_id = null;     // Если ничего не выбрано, сбрасываем страницу
            $menu->url = null;         // Если ничего не выбрано, сбрасываем URL
        }

        // Если установлена новая главная страница, сбрасываем старую
        if ($menu->is_home) {
            Menu::where('is_home', true)->where('id', '!=', $menu->id)->update(['is_home' => false]);
        }

        // Сохранение изменений
        $menu->save();

        return redirect()->route('admin.menus.index')->with('success', 'Меню обновлено успешно.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->route('admin.menus.index')->with('success', 'Меню удалено успешно!');
    }

    public function copy($id)
    {
        // Получаем меню по ID
        $menu = Menu::findOrFail($id);

        // Создаем новую запись, копируя данные существующего меню
        $newMenu = $menu->replicate(); // Копирует все поля меню
        $newMenu->name = $menu->name . ' (копия)'; // Даем новой записи имя с пометкой "копия"

        // Генерация нового слага
        $newSlug = Str::slug($newMenu->name);

        // Если слаг был задан вручную, используем его
        if ($newMenu->slug && $newMenu->slug !== $menu->slug) {
            $newSlug = $newMenu->slug; // Сохраняем вручную заданный слаг
        }

        $newMenu->slug = $newSlug; // Присваиваем новый слаг

        // Сохраняем новый объект
        $newMenu->save();

        return redirect()->route('admin.menus.index')->with('success', 'Меню скопировано успешно.');
    }
}
