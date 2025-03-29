<?php

namespace App\Http\Controllers\User;

use App\Models\Cart;
use App\Models\Wishlist;
use App\Models\Comparison;
use Illuminate\Http\Request;
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
        dd($products);

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
        // Получаем товары, добавленные в корзину текущего пользователя
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();

        // Передаем в представление
        return view('user.cart.index', [
            'activePage' => 'cart',
            'cartItems' => $cartItems,
        ]);
    }


    // Страница "Orders"
    public function orders()
    {
        return view('user.my-account', ['activePage' => 'orders']);
    }



    // Страница "Compare"
    public function compare()
    {
        $compare = Comparison::where('user_id', Auth::id())->with('products')->get();


        return view('user.my-account', [
            'activePage' => 'wishlist',
            'compare' => $compare
        ]);
    }

    public function addToCompare(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $comparison = Comparison::firstOrCreate([
            // 'user_id' => auth()->id(),
            'user_id' => Auth::id(),
        ]);

        $comparison->products()->syncWithoutDetaching([$request->product_id]);

        return redirect()->back()->with('success', 'Товар добавлен в сравнение.');
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
        return view('user.my-account', ['activePage' => 'reviews']);
    }
}
