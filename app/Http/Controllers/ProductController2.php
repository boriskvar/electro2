<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Cart;

class ProductController extends Controller
{
    // Вывод списка продуктов
    public function index(Request $request)
    {
        // dd($request->toArray());
        // Определяем допустимые поля для сортировки
        $allowedSortFields = ['popular', 'position', 'created_at', 'name'];
    
        // Получаем параметры сортировки и пагинации из запроса
        $sort = $request->input('sort', 'position'); // Поле для сортировки, по умолчанию 'position'
        $order = $request->input('order', 'asc'); // Порядок сортировки, по умолчанию 'asc'
        $perPage = $request->input('per_page', 25); // Количество элементов на страницу, по умолчанию 25
        $page = $request->input('page', 1); // Текущая страница
    
        // Проверяем, является ли поле сортировки допустимым
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'position'; // По умолчанию сортируем по 'position', если поле недопустимо
        }
    
        // Получаем минимальную и максимальную цену для фильтрации
        $minPrice = Product::min('price');
        $maxPrice = Product::max('price');
    
        // Загружаем категории с подсчетом количества товаров
        $categories = Category::withCount('products')->get();
    
        // Загружаем бренды с подсчетом количества товаров
        // $brands = Brand::withCount('products')->get();
        $brands = Brand::withCount('products')
               ->whereHas('products', function($query) {
                   $query->whereNotNull('brand_id');
               })
               ->get();
        
    // dd($brands->toArray());

        // Применяем фильтрацию по категориям, брендам и цене
        $query = Product::query();
    
        if ($request->has('category')) {
            $query->whereIn('category_id', $request->input('category'));
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
    
        // Выполняем запрос к базе данных с учетом фильтрации
        $products = $query->when($sort === 'popular', function ($query) use ($order) {
            // Сортировка по популярности (лидеры продаж)
            return $query->orderBy('is_top_selling', 'desc')
                         ->orderBy('reviews_count', $order)
                         ->orderBy('rating', $order);
        }, function ($query) use ($sort, $order) {
            // Сортировка по другому полю
            return $query->orderBy($sort, $order);
        })
        ->paginate($perPage, ['*'], 'page', $page);
    
        // Загружаем топ-3 самых популярных товара
        $topSellingProducts = Product::where('is_top_selling', true)
            ->orderBy('position', 'asc') // Сортировка по позиции
            ->take(3)
            ->get();
    
        // Возвращаем представление с переданными данными
        return view('web.products.index', compact(
            'products',
            'categories',
            'brands',
            'minPrice',
            'maxPrice',
            'topSellingProducts'
        ));
    }

    /* public function addToCart(Product $product, Request $request)
    {
        // $userId = Auth::id();
        $userId = 1; // Замените на Auth::id() для продакшн-версии
        $quantity = $request->input('quantity', 1); // Получаем количество из запроса
    
        // Получаем текущее количество товара в корзине из сессии
        $currentQuantity = session()->get("cart.{$userId}.{$product->id}", 0);
    
        // Обновляем количество товара в корзине
        session()->put("cart.{$userId}.{$product->id}", $currentQuantity + $quantity);
    
        return redirect()->route('web.products.show', $product->id);
    } */


    // Показать один продукт
    /* public function show(Product $product)
    {
        // Временно используем временный ID пользователя для тестирования
        $userId = 1; // Замените на Auth::id() для продакшн-версии

        // Получаем количество товара в корзине для текущего пользователя из сессии
        $currentQuantity = session()->get("cart.{$userId}.{$product->id}", 0); // Если нет, возвращаем 0

        // Передаем продукт и текущее количество в представление
        return view('web.products.show', compact('product', 'currentQuantity'));
    } */

    /* public function search(Request $request)
    {
        //dd($request->toArray()); // []
        $query = $request->input('query');
        // Если запрос пустой, можно вернуть пустую коллекцию или сообщение
        if (!$query) {
            return view('web.products.search', ['products' => collect(), 'query' => '']);
        }

        //dd($query); //null
        // Выполняем поиск по названию продукта
        $products = Product::where('name', 'LIKE', '%' . $query . '%')->get();
        //dd($products->toArray()); // [ 0 => array:23 [▶]  1 => array:23 [▶] ]
        return view('web.products.search', compact('products', 'query'));
    } */
}
