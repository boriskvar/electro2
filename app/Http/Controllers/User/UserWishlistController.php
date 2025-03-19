<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserWishlistController extends Controller
{
    /* public function __construct()
    {
        $this->middleware('auth'); // Доступ только для авторизованных пользователей
    } */

    public function index()
    {
        // $userId = Auth::id(); // Получаем ID авторизованного пользователя
        // $products = Wishlist::where('user_id', $userId)
        //     ->with('product') // Получаем связанные товары
        //     ->get()
        //     ->pluck('product'); // Извлекаем товары

        // return view('user.wishlist.index', compact('products'));
        $userId = Auth::id(); // Получаем ID авторизованного пользователя
        $wishlists = Wishlist::where('user_id', $userId)->with('product')->get();

        // if ($wishlists->isEmpty()) {
        //     return response()->json(['message' => 'Нет товаров в списке желаемого'], 404);  // Возвращаем JSON-ответ, если нет товаров
        // }
        // Получаем количество товаров в списке желаемого (wishlist)
        $wishlistCount = Auth::check() ? Auth::user()->wishlist()->count() : 0;
        // dd($wishlistCount);

        // return response()->json(['data' => $wishlists], 200);  // Возвращаем список желаемых товаров в JSON формате
        return view('user.wishlist.index', compact('wishlists', 'wishlistCount'));
    }

    public function store(Request $request)
    {
        // Проверка, авторизован ли пользователь
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Пользователь не авторизован'], 401);
        }

        // Валидируем входные данные
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();

        // Проверяем, не добавлен ли товар в список желаемого
        if (Wishlist::where('user_id', $userId)->where('product_id', $request->product_id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Товар уже в списке желаемого'], 400);
        }

        // Добавляем товар в список желаемого
        Wishlist::create([
            'user_id' => $userId,
            'product_id' => $request->product_id,
        ]);

        return response()->json(['success' => true, 'message' => 'Товар добавлен в список желаемого']);
    }

    public function getWishlistCount()
    {
        $userId = Auth::id();
        $wishlistCount = Wishlist::where('user_id', $userId)->count(); // Получаем количество товаров в wishlist
        dd($wishlistCount);

        return response()->json(['count' => $wishlistCount]);
    }


    public function destroy(Wishlist $wishlist)
    {
        $userId = Auth::id();

        if ($wishlist->user_id !== $userId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $wishlist->delete();

        return response()->json(['success' => true, 'message' => 'Товар удален из wishlist']);
    }
}
