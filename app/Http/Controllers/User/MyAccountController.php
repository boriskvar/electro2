<?php

namespace App\Http\Controllers\User;

use App\Models\Cart;
use App\Models\Wishlist;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyAccountController extends Controller
{
    // Личный кабинет по умолчанию
    public function index()
    {
        return view('user.my-account', ['activePage' => 'dashboard']);
    }

    // Страница "Wishlist"
    // Желания (Wishlist)
    public function wishlist()
    {
        $userId = Auth::id();
        $wishlists = Wishlist::where('user_id', $userId)->with('product')->get();
        $wishlistCount = $wishlists->count();

        // Преобразуем коллекцию Wishlist в массив товаров
        $products = $wishlists->map(function ($wishlist) {
            return $wishlist->product;
        });

        return view('user.my-account', [
            'activePage' => 'wishlist',
            'wishlists' => $wishlists,
            'wishlistCount' => $wishlistCount,
            'products' => $products, // Передаем товары в шаблон
        ]);
    }


    // Страница "Cart"
    public function cart()
    {
        $userId = Auth::id();
        // dd($userId); //3
        // Получаем все записи корзины пользователя с товарами
        $cartItems = Cart::where('user_id', $userId)->with('product')->get();
        // dd($cartItems);
        // Преобразуем коллекцию Cart в массив товаров
        $products = $cartItems->map(function ($cartItem) {
            return $cartItem->product;
        });

        return view('user.my-account', compact('products'))
            ->with('activePage', 'cart'); // Добавляем activePage
    }


    // Страница "Orders"
    public function orders()
    {
        return view('user.my-account', ['activePage' => 'orders']);
    }



    // Страница "Compare"
    public function compare()
    {
        return view('user.my-account', ['activePage' => 'compare']);
    }

    // Страница "Viewed Products"
    public function products()
    {
        return view('user.my-account', ['activePage' => 'products']);
    }

    // Страница "Reviews"
    public function reviews()
    {
        return view('user.my-account', ['activePage' => 'reviews']);
    }
}
