<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class MyAccountController extends Controller
{
    // Главная страница (по умолчанию "dashboard")
    public function index()
    {
        return view('user.my-account', ['activePage' => 'dashboard']);
    }

    // Страница "Wishlist"
    public function wishlist()
    {
        return view('user.my-account', ['activePage' => 'wishlist']);
    }

    // Страница "Orders"
    public function orders()
    {
        return view('user.my-account', ['activePage' => 'orders']);
    }

    // Страница "Cart"
    public function cart()
    {
        return view('user.my-account', ['activePage' => 'cart']);
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
