<?php

namespace App\Http\Controllers\Admin;

use App\Models\Cart;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminCartController extends Controller
{
    // Показать корзину
    public function index()
    {
        $userId = Auth::id();

        // Получаем корзину из сессии или создаем пустой массив, если корзина не существует
        // $cart = session()->get('cart', []);
        $cart = Cart::where('user_id', $userId)->get();

        // Получаем все товары для формы добавления (если это нужно)
        $products = Product::all();

        $cartIsEmpty = empty($cart);

        // dd($cart);
        // dd($products->toArray());
        return view('admin.cart.index', compact('cart', 'products', 'cartIsEmpty'));
    }

    // Страница добавления товара в корзину
    public function create()
    {
        $products = Product::all(); // Получим все товары
        return view('admin.cart.create', compact('products'));
    }


    // Добавить товар в корзину (сохранение)
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Вызов метода add() для добавления товара в корзину
        return $this->add($request->input('product_id'), $request);
    }

    // Добавить товар в корзину
    public function add(Request $request)
    {
        $productId = $request->input('product_id'); // Получаем ID товара
        $quantity = $request->input('quantity', 1); // Получаем количество товара из формы

        $product = Product::find($productId);

        if (!$product) {
            return redirect()->route('admin.cart.index')->with('error', 'Товар не найден.');
        }

        $price = (float) $product->price; // Преобразуем цену в число

        // $cart = session()->get('cart', []);
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->where('product_id', $productId)->first();
        // dd($cart);
        // Если товар уже есть в корзине, увеличиваем количество
        /*  if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            $cart[$productId]['price_x_quantity'] = $cart[$productId]['quantity'] * $price;
        } else {
            // Если товара нет в корзине, добавляем его
            $cart[$productId] = [
                'product_id' => $productId,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $quantity,
                'price_x_quantity' => $quantity * $price,
            ];
        } */


        // session()->put('cart', $cart);


        if ($cart) {
            $cart->quantity += $quantity;
            $cart->price_x_quantity = $cart->quantity * $price;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $price,
                'price_x_quantity' => $quantity * $price,
            ]);
        }
        // dd($cart);
        return redirect()->route('admin.cart.index')->with('success', 'Товар добавлен в корзину!');
    }


    // Страница редактирования корзины
    /* public function edit($productId)
    {
        $cart = session()->get('cart', []);
        $product = $cart[$productId] ?? null;
        
        if (!$product) {
            return redirect()->route('admin.cart.index')->with('error', 'Товар не найден в корзине.');
        }

        return view('admin.cart.edit', compact('product', 'productId'));
    } */

    // Обновить товар в корзине
    /* public function update(Request $request, $productId)
    {
        $cart = session()->get('cart', []);
        $product = $cart[$productId] ?? null;

        if (!$product) {
            return redirect()->route('admin.cart.index')->with('error', 'Товар не найден в корзине.');
        }

        $newQty = $request->qty; // Новое количество товара
        $cart[$productId]['qty'] = $newQty;
        $cart[$productId]['total'] = $newQty * $cart[$productId]['price']; // Перерасчет общей стоимости

        session()->put('cart', $cart);

        return redirect()->route('admin.cart.index')->with('success', 'Количество товара обновлено!');
    } */

    // Удалить товар из корзины
    public function remove($productId)
    {
        $userId = Auth::id();

        // $cart = session()->get('cart', []);
        $cart = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

        /*  if (isset($cart[$productId])) {
            unset($cart[$productId]); // Удаляем товар из корзины
            session()->put('cart', $cart);
            return redirect()->route('admin.cart.index')->with('success', 'Товар удален из корзины.');
        } */

        if ($cart) {
            $cart->delete();
            return redirect()->route('admin.cart.index')->with('success', 'Товар удален из корзины.');
        }

        return redirect()->route('admin.cart.index')->with('error', 'Товар не найден в корзине.');
    }

    public function checkout(Request $request)
    {
        // Временно используем ID пользователя для тестирования
        // $userId = 1; // В продакшн-версии используйте Auth::id()

        // Проверка, авторизован ли пользователь
        if (!Auth::check()) {
            // Если не авторизован, перенаправляем на страницу логина с сообщением
            return redirect()->route('login')->with('error', 'Пожалуйста, войдите в систему для оформления заказа.');
        }

        // Получаем ID авторизованного пользователя
        $userId = Auth::id();

        // Получаем товары из корзины
        // $cart = session()->get('cart', []);
        $cart = Cart::where('user_id', $userId)->get();

        // Если корзина пуста, перенаправляем на страницу с ошибкой
        /* if (empty($cart)) {
            return redirect()->route('admin.cart.index')->with('error', 'Корзина пуста.');
        } */
        if ($cart->isEmpty()) {
            return redirect()->route('admin.cart.index')->with('error', 'Корзина пуста.');
        }

        // Создаем новый заказ
        $order = Order::create([
            'user_id' => $userId,
            'total_price' => 0, // Мы обновим общую цену позже
            'order_number' => 'ORD' . strtoupper(Str::random(8)), // Генерация случайного номера заказа
            'status' => 'pending',
            'order_date' => now(),
        ]);

        // Общая стоимость для обновления заказа
        $totalPrice = 0;

        // Добавляем товары в таблицу order_items и считаем общую стоимость
        /*  foreach ($cart as $productId => $item) {
            $totalPrice += $item['price_x_quantity'];  // Суммируем все товары в корзине

            // Добавляем товар в order_items
            $order->orderItems()->create([
                'product_id' => $productId,
                'cart_quantity' => $item['quantity'],
                'price_x_quantity' => $item['price_x_quantity'],
            ]);
        } */

        // Добавляем товары в таблицу order_items и считаем общую стоимость
        foreach ($cart as $item) {
            $totalPrice += $item->price_x_quantity;
            $order->orderItems()->create([
                'product_id' => $item->product_id,
                'cart_quantity' => $item->quantity,
                'price_x_quantity' => $item->price_x_quantity,
            ]);
            $item->delete();
        }

        // Обновляем поле total_price в заказе
        $order->update(['total_price' => $totalPrice]);

        // Очищаем корзину
        // session()->forget('cart');

        // Перенаправляем пользователя на страницу с сообщением об успешном заказе
        return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно оформлен!');
    }
}
