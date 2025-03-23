<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Показать корзину
    public function index()
    {
        // Временно подставляем user_id = 1
        // $cartItems = Cart::where('user_id', 1)->get();

        // Получение товаров в корзине для текущего пользователя
        // $cartItems = Cart::where('user_id', Auth::id())->get();
        $userId = Auth::id();
        // $cartItems = Session::get('cart', []);
        $cartItems = Cart::where('user_id', $userId)->get();


        // return view('web.cart.index', compact('cartItems'));
        return view('user.cart.index', compact('cartItems',));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $userId = Auth::id();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Товар не найден'], 404);
        }

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Если товар уже есть в корзине, увеличиваем количество
            $cartItem->quantity += $quantity;
            $cartItem->price_x_quantity = $cartItem->quantity * $product->price;
            $cartItem->save();
        } else {
            // Если товара нет в корзине, добавляем новый
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
                'price_x_quantity' => $quantity * $product->price,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Товар добавлен в корзину']);
    }

    public function remove($productId)
    {
        $userId = Auth::id();
        // $cart = Session::get('cart', []);
        $cart = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

        /* if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
        } */
        if ($cart) {
            $cart->delete();
            // return redirect()->route('user.cart.index')->with('success', 'Товар удален из корзины');
            return response()->json(['success' => true, 'message' => 'Товар удален из корзины']);
        }
        // return redirect()->route('user.cart.index')->with('success', 'Товар удален из корзины');
        return response()->json(['success' => false, 'message' => 'Товар не найден в корзине']);
    }


    public function getCartData()
    {
        $userId = Auth::id();
        // $cartItems = Session::get('cart', []);
        $cartItems = Cart::where('user_id', $userId)->get();
        // $cartCount = count($cartItems);
        $cartCount = $cartItems->count();
        // $subtotal = array_sum(array_column($cartItems, 'price'));
        $subtotal = $cartItems->sum('price_x_quantity');

        // return view('components.main-header', compact('cartItems', 'cartCount', 'subtotal'));
        return response()->json([
            'cartItems' => $cartItems,
            'cartCount' => $cartCount,
            'subtotal' => $subtotal
        ]);
    }
}
