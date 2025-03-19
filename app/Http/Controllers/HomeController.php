<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // Активные категории, отсортированные по порядку
        $categories = Category::where('active', 1)
            ->orderBy('display_order')
            ->get();

        // Активные пункты меню
        $menus = Menu::where('is_active', 1)->orderBy('position')->get();

        // Категории с распродажей
        $saleCategories = Category::where('active', 1)
            ->where('is_sale', 1)
            ->orderBy('display_order')
            ->get();

        // Последние 10 товаров
        $products = Product::latest()->take(10)->get();

        // Новые товары
        $newProducts = Product::where('is_new', 1)
            ->latest()
            ->take(10)
            ->get();

        // Топ-продажи
        $topSellingProducts = Product::where('is_top_selling', 1)
            ->latest()
            ->take(10)
            ->get();

        // Горящие скидки (Hot Deals)
        $hotDeals = Product::whereNotNull('discount_percentage')
            ->where('discount_percentage', '>', 50)
            ->latest()
            ->take(10)
            ->get();

        // Продукты по категориям
        $productsByCategory = $categories->mapWithKeys(function ($category) {
            return [$category->id => $category->products()->latest()->take(10)->get()];
        });

        // Определенные категории
        $categoryNames = ['Laptops', 'Smartphones', 'Accessories', 'Cameras'];
        $categoriesByName = Category::whereIn('name', $categoryNames)->get()->keyBy('name');

        // Товары в этих категориях
        $laptopsProducts = $this->getProductsForCategory($categoriesByName, 'Laptops');
        $smartphonesProducts = $this->getProductsForCategory($categoriesByName, 'Smartphones');
        $accessoriesProducts = $this->getProductsForCategory($categoriesByName, 'Accessories');
        $camerasProducts = $this->getProductsForCategory($categoriesByName, 'Cameras');

        // Генерация ссылок на hot-deals
        $categoryHotDealLinks = $categoriesByName->mapWithKeys(function ($category) {
            return [$category->name => route('menus.page.show', [
                'slug' => 'hot-deals',
                'page_slug' => 'hot-deals',
                'category' => $category->slug
            ])];
        });

        // Получаем количество товаров в списке желаемого (wishlist)
        $wishlistCount = Auth::check() ? Auth::user()->wishlist()->count() : 0;
        // dd($wishlistCount);

        return view('web.home', compact(
            'categories',
            'saleCategories',
            'newProducts',
            'topSellingProducts',
            'products',
            'productsByCategory',
            'laptopsProducts',
            'smartphonesProducts',
            'accessoriesProducts',
            'hotDeals',
            'menus',
            'categoryHotDealLinks', // Передаем ссылки в шаблон
            'wishlistCount'
        ));
    }

    private function getProductsForCategory($categoriesByName, $categoryName)
    {
        return $categoriesByName->has($categoryName)
            ? Product::where('category_id', $categoriesByName[$categoryName]->id)
            ->latest()
            ->take(10)
            ->get()
            : collect();
    }
}
