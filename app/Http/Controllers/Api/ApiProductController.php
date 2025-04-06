<?php

namespace App\Http\Controllers\Api;


use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ApiProductController extends Controller
{
    // Вывод списка продуктов
    public function index(Request $request)
    {
        // dd('API работает'); // Проверка
        // dd($request);
        // Получаем параметры из запроса
        $category = $request->input('category');
        $perPage  = $request->input('perPage', 20); // Количество продуктов на странице
        $sort = $request->input('sort', 'position'); // Поле сортировки
        $order = $request->input('order', 'asc'); // Направление сортировки

        // Убедимся, что поле сортировки и направление корректны
        $validSortFields = ['position', 'price', 'name', 'rating'];  // Добавь все возможные поля для сортировки
        $validOrderDirections = ['asc', 'desc'];

        if (!in_array($sort, $validSortFields)) {
            $sort = 'position';  // Значение по умолчанию
        }
        if (!in_array($order, $validOrderDirections)) {
            $order = 'asc';  // Значение по умолчанию
        }
        /*  dd([
    'perPage' => $perPage,
    'sort' => $sort,
    'order' => $order
]); */

        $query = Product::query();  // Начинаем запрос
        // dd($query);

        // Фильтруем по категории, если параметр передан
        if ($category) {
            // Можно фильтровать и по ID категории, если необходимо
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('name', $category);  // Фильтруем по имени категории
                // Или по ID категории: $q->where('id', $category);
            });
        }

        // Загружаем продукты с пагинацией и сортировкой
        $products = $query->orderBy($sort, $order)->paginate($perPage);

        // Получаем продукты с пагинацией и сортировкой
        // $products = Product::orderBy($sort, $order)->paginate($perPage);
        // dd($products->toArray());
        // Логируем количество продуктов и страницу
        /*    dd([
        'total' => $products->total(),
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage(),
    ]); */

        /* 
    array:3 [▼ // app\Http\Controllers\Api\ApiProductController.php:36
  "total" => 5
  "current_page" => 1
  "last_page" => 1
]
    */
        // Преобразуем данные в нужный формат для Vue.js
        $productsData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->name ?? null, // Категория товара
                'price' => $product->price,
                'oldPrice' => $product->old_price,
                'image' => isset($product->images[0]) ? $product->images[0] : null, // Первое изображение
                'isOnSale' => $product->is_top_selling,
                'isNew' => $product->is_new,
                'rating' => $product->rating,
                'position' => $product->position,
                'discount' => round($product->discount_percentage ?? 0), // Скидка
            ];
        });
        // dd($productsData);


        // Возвращаем данные в формате JSON, включая пагинацию
        return response()->json([
            'data' => $productsData,
            'total' => $products->total(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'per_page' => $products->perPage(),
        ]);
    }

    //?  В контроллере
    public function getProductDetails($productId)
    {
        $product = Product::with(['category.attributes', 'attributes'])->findOrFail($productId);

        // Собираем характеристики по аналогии с сравнением товаров
        $attributes = $product->category->attributes->mapWithKeys(function ($categoryAttribute) use ($product) {
            $value = $product->attributes
                ->firstWhere('category_attribute_id', $categoryAttribute->id)
                ->value ?? '—'; // значение из pivot

            return [$categoryAttribute->attribute_name => $value];
        })->toArray();

        return response()->json([
            'product' => $product,
            'attributes' => $attributes,
        ]);
    }



    // Показать один продукт
    public function show(Product $product)
    {
        // Временно используем временный ID пользователя для тестирования
        $userId = 1; // Замените на Auth::id() для продакшн-версии

        // Получаем количество товара в корзине для текущего пользователя
        $cartItem = Cart::where('user_id', $userId)->where('product_id', $product->id)->first();
        $currentQuantity = $cartItem ? $cartItem->quantity : 0; // Если товар в корзине, получаем его количество, иначе 0

        // Передаем продукт и текущее количество в представление
        return view('products.show', compact('product', 'currentQuantity'));
    }

    // Получить связанные продукты
    public function related($id)
    {
        $product = Product::findOrFail($id);

        // Получение связанных товаров (например, товары той же категории)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->get();

        // Ограничим выборку только 4 товара (с помощью метода take(4))
        $relatedProducts = $relatedProducts->take(4);

        return response()->json($relatedProducts);
    }

    // Получить изображения продукта
    /* public function getProductImages($id)
    {
        $product = Product::findOrFail($id);
    
        // Предположим, что изображения хранятся в массиве
        return response()->json([
            'images' => $product->images,    // Возвращаем все изображения, или ограничиваем
        ]);
    } */
}
