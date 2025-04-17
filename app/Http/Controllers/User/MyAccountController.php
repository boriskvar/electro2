<?php

namespace App\Http\Controllers\User;

use App\Models\Cart;
use App\Models\Review;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Comparison;
use Illuminate\Http\Request;
use App\Models\CategoryAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductCategoryAttribute;

class MyAccountController extends Controller
{
    // Личный кабинет по умолчанию
    public function index()
    {
        return view('user.my-account', ['activePage' => 'dashboard']);
    }

    // Страница "Wishlist"
    public function wishlist(Request $request)
    {
        // dd('Шаблон загружается через wishlist()');
        $userId = Auth::id();

        // Загружаем товары из Wishlist с привязанными продуктами
        $wishlists = Wishlist::where('user_id', $userId)->with('product')->get();
        // dd($wishlists);

        $wishlistCount = $wishlists->count();

        // Собираем список продуктов
        $products = $wishlists->pluck('product');
        // dd($products);

        // Возвращаем JSON при AJAX-запросе
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'wishlists' => $wishlists,
                'wishlistCount' => $wishlistCount,
                'products' => $products,
            ]);
        }

        // Возвращаем HTML-шаблон с сайдбаром
        return view('user.my-account', [
            'activePage' => 'wishlist',
            'wishlists' => $wishlists,
            'wishlistCount' => $wishlistCount,
            'products' => $products,
        ]);
    }

    public function storeWishlist(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::firstOrCreate([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Товар добавлен в Wishlist',
        ]);
    }

    public function removeFromWishlist($id)
    {
        $wishlist = Wishlist::where('user_id', Auth::id())->where('product_id', $id)->first();

        if (!$wishlist) {
            return response()->json(['success' => false, 'message' => 'Товар не найден в Wishlist.'], 404);
        }

        $wishlist->delete();
        return response()->json(['success' => true, 'message' => 'Товар удалён из Wishlist.']);
    }

    // Страница "Cart"
    public function cart()
    {
        // Получаем товары, добавленные в корзину текущего пользователя
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        // Передаем в представление
        /* return view('user.cart.index', [
            'activePage' => 'cart',
            'cartItems' => $cartItems,
        ]); */
        return view('user.my-account', [
            'activePage' => 'cart',
            'cartItems' => $cartItems,
            'products' => $cartItems->pluck('product') // если ваш Vue-компонент ожидает products
        ]);
    }

    // Страница "Orders"
    public function orders()
    {
        return view('user.my-account', ['activePage' => 'orders']);
    }

    public function compare()
    {
        $comparison = Comparison::where('user_id', Auth::id())->first();

        // Если нет записей сравнения или товаров - возвращаем пустые данные
        if (!$comparison || $comparison->products()->count() === 0) {
            return view('user.my-account', [
                'products' => collect(),
                'categoryAttributes' => collect(),
                'productData' => [],
                'activePage' => 'compare'
            ]);
        }

        $products = $comparison->products()->with('attributes', 'category')->get();
        $category = $products->first()->category;
        $categoryAttributes = $category->attributes;

        $productData = [];

        foreach ($products as $product) {
            $productAttributes = $product->attributes->keyBy('category_attribute_id');

            $productData[] = [
                'product' => $product,
                'attributes' => $categoryAttributes->mapWithKeys(function ($attribute) use ($productAttributes) {
                    return [$attribute->attribute_name => $productAttributes[$attribute->id]->value ?? '—'];
                })->toArray()
            ];
        }

        return view('user.my-account', [
            'products' => $products,
            'categoryAttributes' => $categoryAttributes,
            'productData' => $productData,
            'activePage' => 'compare',
            'category' => $category
        ]);
    }

    public function addToCompare(Request $request)
    {
        // Валидация, чтобы продукт существовал
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Если пользователь не авторизован
        if (!Auth::check()) {
            // Можно сохранить продукт в сессии для неавторизованных пользователей
            session()->push('compare_products', $request->product_id);

            return response()->json([
                'success' => true,
                'message' => 'Товар добавлен в сравнение, но для окончательного сохранения нужно авторизоваться.',
                'redirect' => route('login') // Добавляем ссылку на страницу логина
            ]);
        }

        // Если пользователь авторизован, работаем с Comparison
        $comparison = Comparison::firstOrCreate(
            ['user_id' => Auth::id()],
            ['created_at' => now(), 'updated_at' => now()]  // Устанавливаем даты
        );



        // Добавляем товар в сравнение (если он еще не был добавлен)
        $comparison->products()->syncWithoutDetaching([$request->product_id]);

        return response()->json([
            'success' => true,
            'message' => 'Товар добавлен в сравнение.'
        ]);
    }

    public function removeFromCompare(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $comparison = Comparison::where('user_id', Auth::id())->first();

        if ($comparison) {
            $comparison->products()->detach($request->product_id);
        }

        return redirect()->back()->with('success', 'Товар удален из сравнения.');
    }

    public function clearCompare()
    {
        $comparison = Comparison::where('user_id', Auth::id())->first();

        if ($comparison) {
            $comparison->products()->detach();
            $comparison->delete();
        }

        return redirect()->back()->with('success', 'Список сравнения очищен.');
    }


    // Страница "Viewed Products"
    public function products()
    {
        return view('user.my-account', ['activePage' => 'products']);
    }

    // Страница "Reviews"
    public function reviews()
    {
        $user = Auth::user();

        // Получаем все отзывы текущего пользователя, включая мягко удалённые товары
        /* $reviews = Review::with('product') // Загружаем продукты вместе с отзывами
            ->where('user_id', $user->id)
            ->get(); */
        $reviews = Review::with('product')->latest()->get();


        return view('user.my-account', [
            'activePage' => 'reviews',
            'reviews' => $reviews,
        ]);
    }
}
