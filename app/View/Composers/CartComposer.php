<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Cart; // Импортируйте вашу модель корзины
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartComposer
{
    public function compose(View $view)
    {
        $userId = Auth::id();
        // Получите данные из таблицы корзины
        // $cartItems = Cart::all(); // Измените запрос в зависимости от ваших нужд
        $cartItems = Cart::where('user_id', $userId)->get();
        // dd($cartItems);
        // dd($cartItems->toArray());

        // Добавьте первую картинку из продукта к каждому элементу корзины
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);
            if ($product && $product->images) {
                $item->image = $product->images[0] ?? null; // Берем первую картинку
            } else {
                $item->image = null;
            }
        }

        // dd($cartItems);
        // dd($cartItems->toArray());

        // Передайте данные в представление
        $view->with('cartItems', $cartItems);
    }
}
