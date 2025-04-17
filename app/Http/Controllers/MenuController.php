<?php

namespace App\Http\Controllers;


use App\Models\Menu;
use App\Models\Page;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{

    // Показать все меню
    /* public function index()
    {
        // $homeMenu = Menu::where('is_home', true)->first();

        // Получаем верхнее меню (для главной страницы или общего меню)
        $mainMenu = Menu::where('menu_type', 'main') // Фильтруем по типу "main"
            ->where('is_active', true)  // Только активные меню
            ->orderBy('position')       // Сортируем по позиции
            ->get();

        // Получаем нижнее меню (например, футер)
        $footerMenu = Menu::where('menu_type', 'footer') // Фильтруем по типу "footer"
            ->where('is_active', true)   // Только активные меню
            ->orderBy('position')       // Сортируем по позиции
            ->get();

        return view('web.menus.index', compact('mainMenu', 'footerMenu'));  // Передаем в шаблон
    } */


    // Показать конкретное меню по slug
    public function show($slug)
    {
        $homeMenu = Menu::where('is_home', true)->first(); // Добавляем homeMenu

        // Получаем меню по slug, с категориями и страницами
        $menu = Menu::where('slug', $slug)->with(['category', 'page'])->firstOrFail();

        // Если меню привязано к категории — перенаправляем на menus.category.show
        if ($menu->category) {
            return redirect()->route('menus.category.show', [
                'slug' => $menu->slug,
                'category_slug' => $menu->category->slug
            ]);
        }

        // Если меню привязано к странице — перенаправляем  на маршрут страниц
        if ($menu->page) {
            return redirect()->route('menus.page.show', [
                'slug' => $menu->slug,
                'page_slug' => $menu->page->slug
            ]);
        }

        // Если задан кастомный URL — перенаправляем на него
        if (!empty($menu->custom_url)) {
            return redirect($menu->custom_url);
        }

        // Если меню не связано с категорией или страницей, и кастомный URL отсутствует,
        // отобразим страницу с товарами магазина, т.е. шаблон store
        return view('web.store', compact('homeMenu', 'menu'));
    }

    // Страница категории (если пункт меню ведет на категорию)
    public function category($slug, $categorySlug)
    {
        if (!$slug || !$categorySlug) {
            abort(404);
        }

        $homeMenu = Menu::where('is_home', true)->first();
        $menu = Menu::where('slug', $slug)->first();
        $category = Category::where('slug', $categorySlug)->first();
        // $categories = Category::all();
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->whereHas('products')->get();
        // dd($brands->toArray());
        // dd($categories);
        // Получаем топ-3 самых популярных товара
        $topSellingProducts = Product::where('is_top_selling', true)
            ->orderBy('position', 'asc')
            ->take(3)
            ->get();
        // dd($topSellingProducts);

        // Формируем запрос для товаров
        $productsQuery = Product::where('category_id', $category->id)->with('category');

        // Фильтр по поисковому запросу
        $search = request('search');
        if ($search) {
            $productsQuery->where('name', 'like', '%' . $search . '%');
        }


        // Фильтрация по цене
        $minPrice = request('min_price');
        $maxPrice = request('max_price');

        if ($minPrice !== null) {
            $productsQuery->where('price', '>=', (float)$minPrice);
        }
        if ($maxPrice !== null) {
            $productsQuery->where('price', '<=', (float)$maxPrice);
        }

        // Фильтрация по брендам
        if ($selectedBrands = request('brands')) {
            $productsQuery->whereIn('brand_id', $selectedBrands);
        }

        // Применяем сортировку
        $sort = request('sort', 'popular');
        if ($sort === 'position') {
            $productsQuery->orderBy('position');
        } else {
            $productsQuery->latest();
        }

        // Количество товаров на странице
        $perPage = request('per_page', 20);

        // Получаем товары с пагинацией
        // $products = $productsQuery->paginate($perPage);
        $products = $productsQuery->paginate($perPage)->withQueryString(); // withQueryString() сохраняет параметры фильтрации в пагинации
        // dd($products);

        // Получаем количество товаров в списке желаемого (wishlist)
        $wishlistCount = Auth::check() ? Auth::user()->wishlist()->count() : 0;
        // dd($wishlistCount);
        return view('web.store', compact('homeMenu', 'menu', 'category', 'categories', 'products', 'brands', 'topSellingProducts', 'wishlistCount'));
    }


    // Возвращаем представление для страницы
    public function page($slug, $page_slug)
    {
        $homeMenu = Menu::where('is_home', true)->first();
        $menu = Menu::where('slug', $slug)->firstOrFail();
        $page = Page::where('slug', $page_slug)->firstOrFail();
        // $categories = Category::all();
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->whereHas('products')->get();
        // dd($brands->toArray());

        // Получаем топ-3 самых популярных товара
        $topSellingProducts = Product::where('is_top_selling', true)
            ->orderBy('position', 'asc')
            ->take(3)
            ->get();
        // dd($topSellingProducts);

        $productsQuery = Product::with('category');

        // Фильтрация по категориям
        if ($selectedCategories = request('categories')) {
            $productsQuery->whereIn('category_id', $selectedCategories);
        }

        // Фильтрация по цене
        if ($minPrice = request('min_price')) {
            $productsQuery->where('price', '>=', (float)$minPrice);
        }
        if ($maxPrice = request('max_price')) {
            $productsQuery->where('price', '<=', (float)$maxPrice);
        }

        // Фильтрация по брендам
        if ($selectedBrands = request('brands')) {
            $productsQuery->whereIn('brand_id', $selectedBrands);
        }

        // Фильтр для "hot-deals"
        if ($page_slug === 'hot-deals') {
            $productsQuery->whereNotNull('discount_percentage')
                ->where('discount_percentage', '>', 50);
        }

        // Если это "categories", фильтруем по категории
        /*  if ($page_slug === 'categories' && request('category')) {
            $category = Category::where('slug', request('category'))->first();
            if ($category) {
                $productsQuery->where('category_id', $category->id);
            }
        } */

        // Если страница "categories", показываем все товары для этой категории
        if ($page_slug === 'categories') {
            // Получаем все товары, привязанные к категории, если она передана
            $categorySlug = request('category');
            if ($categorySlug) {
                $category = Category::where('slug', $categorySlug)->first();
                if ($category) {
                    $productsQuery->where('category_id', $category->id);
                }
            }
            $products = $productsQuery->latest()->get();
        }
        // Если страница "hot-deals", показываем товары со скидкой
        elseif ($page_slug === 'hot-deals') {
            // Получаем переданный параметр категории из URL
            $categorySlug = request('category');

            // Если передан параметр `category`, фильтруем по категории
            if ($categorySlug) {
                $category = Category::where('slug', $categorySlug)->first();
                if ($category) {
                    $productsQuery->where('category_id', $category->id);
                }
            }

            // Получаем товары с горячими скидками
            $products = $productsQuery->latest()->get();
        } else {
            // Для других страниц показываем все товары (или настраивайте фильтрацию по-другому)
            $products = $productsQuery->latest()->get();
        }


        // Применяем сортировку
        $sort = request('sort', 'popular'); // Значение по умолчанию
        if ($sort === 'position') {
            $productsQuery->orderBy('position');
        } else {
            $productsQuery->latest();
        }

        // Количество товаров на странице
        $perPage = request('per_page', 20);

        // Получаем товары с пагинацией + сохраняем параметры фильтрации
        $products = $productsQuery->paginate($perPage);
        // $products = $productsQuery->paginate($perPage)->withQueryString();

        // Если это Home, редиректим на главную страницу
        if ($menu->is_home) {
            return redirect()->route('home');
        }

        // Получаем количество товаров в списке желаемого (wishlist)
        $wishlistCount = Auth::check() ? Auth::user()->wishlist()->count() : 0;
        // dd($wishlistCount);
        return view('web.store', compact('homeMenu', 'page', 'menu', 'products', 'categories', 'brands', 'topSellingProducts', 'wishlistCount'));
    }
}
