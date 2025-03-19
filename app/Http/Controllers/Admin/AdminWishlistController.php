<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminWishlistController extends Controller
{

    public function index()
    {
        // Получаем все товары из wishlist'ов с привязкой к пользователям и товарам
        $wishlists = Wishlist::with(['user', 'product'])->get();
        $users = User::all(); // Получаем всех пользователей
        $products = Product::all(); // Получаем все товары

        return view('admin.wishlists.index', compact('wishlists', 'users', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        // Проверяем, есть ли уже этот товар в wishlist пользователя
        $exists = Wishlist::where('user_id', $request->user_id)
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Этот товар уже есть в списке желаемого');
        }

        // Добавляем товар в wishlist
        Wishlist::create([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
        ]);

        return redirect()->back()->with('success', 'Товар добавлен в wishlist');
    }


    public function destroy($id)
    {
        $wishlist = Wishlist::find($id);

        if (!$wishlist) {
            return redirect()->back()->with('error', 'Товар в списке желаемого не найден');
        }

        $wishlist->delete();

        return redirect()->back()->with('success', 'Товар удален из списка желаемого');
    }
}
