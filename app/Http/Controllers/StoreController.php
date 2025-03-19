<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        // Получаем параметры пагинации, сортировки и фильтрации
        $perPage = $request->input('per_page', 25);
        $page = $request->input('page', 1);

        // Допустимые параметры сортировки
        $allowedSortFields = ['position', 'rating'];
        $sort = in_array($request->input('sort'), $allowedSortFields) ? $request->input('sort') : 'position';
        $order = $request->input('order', 'asc');

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
        $query->orderBy($sort, $order);

        // Пагинируем результаты
        $products = $query->paginate($perPage, ['*'], 'page', $page);

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
}