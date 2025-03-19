<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();
        $category = null; // Гарантируем, что переменная существует
        // Фильтр по поисковому запросу
        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Фильтр по категории (если выбрана)
        if ($categorySlug = $request->input('category')) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Получаем бренды с количеством товаров
        $brands = Brand::withCount('products')->get();

        // Получаем топ-3 самых популярных товара
        $topSellingProducts = Product::where('is_top_selling', true)
            ->orderBy('position', 'asc')
            ->take(3)
            ->get();

        // Получаем продукты с пагинацией
        $products = $query->paginate(20);
        // Пример, как получить $menu
        // $menu = Menu::where('slug', $slug)->first();
        $menu = $category->menu ?? null;
        // Передаем бренды, популярные товары и продукты в представление
        return view('web.store', compact('products',  'category', 'brands', 'topSellingProducts', 'menu'));
    }
}