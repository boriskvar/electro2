<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\Category;

class AdminPageController extends Controller
{
    public function index()
    {
        $pages = Page::all(); // Получаем все страницы
        return view('admin.pages.index', compact('pages'));
    }

    public function create()
    {
        $categories = Category::all(); // Получаем все категории
        // dd($categories);
        return view('admin.pages.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            // 'category_id' => 'nullable|exists:categories,id', // Валидация категории, может быть null
            'slug' => 'required|string|max:255|unique:pages',
        ]);
        // dd($request);
        // Создаём новую страницу
        Page::create($request->all());
        return redirect()->route('admin.pages.index')->with('success', 'Страница успешно создана!'); // Добавляем сообщение об успехе
    }

    public function show(Page $page)
    {
        return view('admin.pages.show', compact('page'));
    }

    public function edit(Page $page)
    {
        $categories = Category::all(); // Получаем все категории
        return view('admin.pages.edit', compact('page', 'categories'));
    }

    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            // 'category_id' => 'nullable|exists:categories,id', // Валидация категории
            'slug' => 'required|string|max:255|unique:pages,slug,' . $page->id,
        ]);

        $page->update($request->all());
        return redirect()->route('admin.pages.index');
    }

    public function destroy(Page $page)
    {
        $page->delete();
        return redirect()->route('admin.pages.index');
    }
}