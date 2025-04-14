<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Вывод списка продуктов
    /*  public function index(Request $request)
    {
        // Определяем допустимые поля для сортировки
        $allowedSortFields = ['popular', 'position', 'created_at', 'name']; // Добавьте другие поля по необходимости

        // Получаем параметры сортировки и пагинации из запроса
        $sort = $request->input('sort', 'position'); // Поле для сортировки, по умолчанию 'position'
        $order = $request->input('order', 'asc'); // Порядок сортировки, по умолчанию 'asc'
        $perPage = $request->input('per_page', 25); // Количество элементов на страницу, по умолчанию 25
        $page = $request->input('page', 1); // Текущая страница

        // Проверяем, является ли поле сортировки допустимым
        if (!in_array($sort, $allowedSortFields)) {
            $sort = 'position'; // По умолчанию сортируем по 'position' если поле недопустимо
        }

        // $product = Product::withCount('reviews')->find(11); // Замените 1 на актуальный ID продукта
        // $product->reviews_count = $product->reviews()->count();
        // $product->save();
        // dd($product->toArray());
        // dd($product->reviews_count); // Проверяем количество отзывов
        // dd($product->reviews); // Проверяем все отзывы, связанные с продуктом



        // Выполняем запрос к базе данных с учетом отзывов и оценок
        $products = Product::withCount('reviews') // Подсчитываем количество отзывов
            ->withAvg('reviews', 'rating') // Вычисляем среднюю оценку
            ->when($sort === 'popular', function ($query) use ($order) {
                // Сортировка по популярности (количество отзывов и средняя оценка)
                return $query->orderBy('reviews_count', $order)
                    ->orderBy('reviews_avg_rating', $order);
            }, function ($query) use ($sort, $order) {
                // Сортировка по другим полям
                return $query->orderBy($sort, $order);
            })
            ->paginate($perPage, ['*'], 'page', $page);

        // Доступ к количеству отзывов для каждого продукта
         foreach ($products as $product) {
            dd($product->reviews_count); // Здесь вы получите количество отзывов для каждого продукта
        } 
        $categories = Category::all();
        $brands = Brand::all();

        // Возвращаем представление с переданными данными
        return view('web.products.index', compact('categories', 'brands',  'products'));
    }
 */

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
    public function show($id)
    {
        // Попробуем найти продукт по ID, если не найден - вернется 404 ошибка
        $product = Product::findOrFail($id);
        // $product = Product::with(['reviews', 'details'])->findOrFail($id); // Подгружаем связанные данные

        // dd($product->toArray());

        // Получаем все изображения из этого продукта
        $allImages = $product->images; // Это уже массив, если в модели задано кастование для images

        // Используем ID текущего пользователя (для продакшн-версии)
        // $userId = auth()->id() ?: 1; // В случае отсутствия пользователя, используем ID 1 для теста
        $userId = 1;
        // Получаем количество товара в корзине для текущего пользователя из сессии
        $currentQuantity = session()->get("cart.{$userId}.{$product->id}", 0); // Если нет, возвращаем 0

        // Проверим данные перед тем, как передавать их в представление
        // dd($product, $product->sizes, $product->colors);
        // dd($product->toArray());

        // Получение товаров из той же категории, исключая текущий товар
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder() // Сортируем случайным образом
            ->limit(4) // Ограничиваем выборку 4 товарами
            ->get()
            ->map(function ($product) {
                $product->first_image = $product->images[0] ?? null; // Берем первый элемент или null
                return $product;
            });

        // dd($relatedProducts);

        // Получаем количество товаров в списке желаемого (wishlist)
        $wishlistCount = Auth::check() ? Auth::user()->wishlist()->count() : 0;
        // dd($wishlistCount);

        $productSocialLinks = SocialLink::where('active', true)
            ->where('type', 'product') // 👈 важный фильтр
            ->orderBy('position')
            ->get();

        // Передаем продукт и текущее количество в представление, а также размеры
        return view('web.product', [
            'product' => $product,
            'allImages' => $allImages,  // Добавляем все изображения
            'relatedProducts' => $relatedProducts,
            'wishlistCount' => $wishlistCount,
            'productSocialLinks' => $productSocialLinks,
        ]);
    }



    /*  public function search(Request $request)
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
