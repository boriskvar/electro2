<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Вывод списка категорий
    /* public function index()
    {
        // Получаем только родительские категории
        $categories = Category::whereNull('parent_id')->with('children')->get();

        // Передаем коллекцию категорий в вид и компонент
        return view('web.categories.index', compact('categories'));
    } */

    public function index(Request $request)
    {
        // Получаем параметры пагинации, сортировки и фильтрации
        $perPage = $request->input('per_page', 25);
        $page = $request->input('page', 1);

        // Допустимые параметры сортировки
        /*  $allowedSortFields = ['position', 'rating'];
        $sort = in_array($request->input('sort'), $allowedSortFields) ? $request->input('sort') : 'position';
        $order = $request->input('order', 'asc');
        */
        // Загружаем минимальную и максимальную цену для фильтрации
        $minPrice = Product::min('price');
        $maxPrice = Product::max('price');

        // Загружаем категории и бренды с подсчетом количества товаров
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->whereHas('products')->get();

        // Строим запрос с фильтрами
        $query = Product::query();

        // Применяем фильтры
        if ($request->has('category')) {
            $categories = (array) $request->input('category');
            $query->whereIn('category_id', $categories);
        }

        if ($request->has('brand')) {
            $query->whereIn('brand_id', $request->input('brand'));
        }

        if ($request->has('price_min') && $request->has('price_max')) {
            $query->whereBetween('price', [
                $request->input('price_min'),
                $request->input('price_max')
            ]);
        }

        // Фильтр по скидкам (hot deals)
        if ($request->has('hot-deals')) {
            $query->where('discount_percentage', '>', 0);
        }

        // Применяем сортировку
        $sort = $request->input('sort', 'position');
        $order = $request->input('order', 'asc');
        $query->orderBy($sort, $order);

        // Пагинируем результаты
        // $products = $query->paginate($perPage, ['*'], 'page', $page);
        $products = $query->paginate($perPage);

        // Получаем топ-3 самых популярных товара
        $topSellingProducts = Product::where('is_top_selling', true)
            ->orderBy('position', 'asc')
            ->take(3)
            ->get();

        // Возвращаем представление с переданными данными
        return view('web.store', compact(
            'products',
            'categories',
            'brands',
            'minPrice',
            'maxPrice',
            'topSellingProducts'
        ));
    }

    public function filter($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)
            ->orderBy('position')
            ->paginate(25);

        return view('categories.filter', compact('category', 'products'));
    }

    //  Показывает товары внутри категории
    public function show($slug)
    {
        // Преобразуем slug в нижний регистр
        $slug = strtolower($slug);

        // Ищем категорию по slug
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = Product::where('category_id', $category->id)->paginate(20);
        // dd($category->toArray());

        return view('web.categories.show', compact('category', 'products'));
    }
}
