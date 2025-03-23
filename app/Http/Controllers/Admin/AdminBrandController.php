<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = Brand::all(); // Получаем все бренды из базы данных
        return view('admin.brands.index', compact('brands')); // Возвращаем представление с переданными данными
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $brands = Brand::all(); // Получаем все бренды
        return view('admin.brands.create', compact('brands')); // Передаем переменную в представление
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request->all()); // Отладка, чтобы проверить входящие данные
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name', // Уникальность по полю name
            'slug' => 'required|string|max:255|unique:brands,slug', // Изменено на 'brands'
            'description' => 'nullable|string',
            'popularity' => 'nullable|integer|min:0', // Добавлено поле популярности
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Валидация для логотипа
        ]);
        //dd($request->all());

        // Генерация slug, если не передано
        $slug = $request->slug ?? Str::slug($request->name);

        // Загрузка логотипа
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public'); // Сохранение файла в папку logos
        }

        // Создание нового бренда
        Brand::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description ?? '', // Если нет описания, сохраняем пустую строку
            'popularity' => $request->popularity ?? 0, // Если нет значения популярности, сохраняем 0
            'logo' => $logoPath ?? null, // Добавляем путь к логотипу
        ]);
        //dd($request->all());

        return redirect()->route('admin.brands.index')->with('success', 'Бренд создан успешно!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //dd($brand->logo);
        //dd($brand->name); //"test3"
        //dd($brand->description);
        return view('admin.brands.show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Brand $brand)
    {
        // Валидация входящих данных
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $brand->id, // Проверка уникальности с исключением текущего бренда
            'popularity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Проверка на изображение
        ]);

        // Сохраняем предыдущий логотип
        $previousLogo = $brand->logo;

        // Обновляем данные бренда
        $brand->name = $request->name;
        $brand->slug = $request->slug;
        $brand->popularity = $request->popularity;
        $brand->description = $request->description;

        // Если загружен новый логотип, то обновляем поле logo
        if ($request->hasFile('logo')) {
            // Удаление старого логотипа, если он существует
            if ($previousLogo && file_exists(public_path($previousLogo))) {
                unlink(public_path($previousLogo));
            }

            // Сохраняем новый логотип
            $logoPath = $request->file('logo')->store('logos', 'public');
            $brand->logo = 'storage/' . $logoPath; // Обновляем путь к логотипу в базе данных
        }

        // Сохраняем изменения в базе данных
        $brand->save();

        // Перенаправление на страницу индекса с сообщением об успехе
        return redirect()->route('admin.brands.index')->with('success', 'Бренд успешно обновлен!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        // Удаляем логотип, если он есть
        if ($brand->logo) {
            // Путь к логотипу
            $logoPath = 'logos/' . basename($brand->logo);
            // Удаляем файл из файловой системы
            Storage::disk('public')->delete($logoPath);
        }

        // Удаляем бренд
        $brand->delete();

        return redirect()->route('admin.brands.index')->with('success', 'Бренд успешно удалён.');
    }
}