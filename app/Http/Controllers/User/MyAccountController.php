<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MyAccountController extends Controller
{
    public function index()
    {
        return view('user.my-account', ['activePage' => 'dashboard']);
    }

    public function show($page)
    {
        $validPages = ['dashboard', 'wishlist', 'orders'];

        if (!in_array($page, $validPages)) {
            abort(404);
        }

        return view('user.my-account', ['activePage' => $page]);
    }
}
