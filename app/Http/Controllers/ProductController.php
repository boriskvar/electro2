<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Brand;
use App\Models\Review;
use App\Models\Product;
use App\Models\Category;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Показать один продукт
    public function show($id)
    {
        // Попробуем найти продукт по ID, если не найден - вернется 404 ошибка
        // $product = Product::findOrFail($id);
        // $product = Product::with(['reviews', 'details'])->findOrFail($id); // Подгружаем связанные данные
        $product = Product::with(['reviews' => function ($query) {
            $query->latest();
        }])->findOrFail($id);
        // dd($product->toArray());

        $reviewsPerPage = 5;
        $currentPage = request()->get('page', 1);
        $totalReviews = $product->reviews->count();
        // dd($totalReviews); //3

        $totalPages = ceil($totalReviews / $reviewsPerPage);
        $start = ($currentPage - 1) * $reviewsPerPage;

        $paginatedReviews = $product->reviews->slice($start, $reviewsPerPage);

        // Получаем все изображения из этого продукта
        $allImages = $product->images; // Это уже массив, если в модели задано кастование для images

        // Используем ID текущего пользователя (для продакшн-версии)
        // $userId = auth()->id() ?: 1; // В случае отсутствия пользователя, используем ID 1 для теста
        // $userId = 1;
        $userId = Auth::id();
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
            'paginatedReviews' => $paginatedReviews,
            'totalReviews' => $totalReviews,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'author_name' => 'required|string|max:255',
            'email' => 'required|email',
            'review' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'product_id' => 'required|exists:products,id',
        ]);

        // Сохраняем отзыв
        // $review = new Review();
        // $review->product_id = $request->product_id; // Нужно передать ID продукта
        // $review->author_name = $request->author_name;
        // $review->email = $request->email;
        // $review->review = $request->review;
        // $review->rating = $request->rating;
        // $review->save();
        // dd($review);

        /* Review::create([
            'product_id' => $request->product_id,
            'author_name' => $request->author_name,
            'email' => $request->email,
            'review' => $request->review,
            'rating' => $request->rating,
            'user_id' => Auth::check() ? Auth::id() : null,
        ]); */

        // $product = Product::with('reviews')->find($request->product_id);
        // dd($product->reviews->pluck('review'));

        $review = Review::create([
            'product_id' => $request->product_id,
            'author_name' => $request->author_name,
            'email' => $request->email,
            'review' => $request->review,
            'rating' => $request->rating,
            'user_id' => Auth::check() ? Auth::id() : null,
        ]);
        // Проверим, связан ли отзыв с товаром
        // dd($review->product ? $review->product->name : 'нет связи');

        // Увеличиваем счётчик отзывов в продукте
        Product::where('id', $request->product_id)->increment('reviews_count');

        return redirect()->back()->with('success', 'Отзыв успешно добавлен!');
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
