<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        // Получаем товары из корзины текущего пользователя
        $cartItems = Cart::where('user_id', Auth::id())->get();

        // Рассчитываем общую сумму товаров
        $total = $cartItems->sum('price_x_quantity');

        // Получаем стоимость доставки из конфигурации
        $shippingMethods = config('shipping.methods');

        // Получаем выбранный метод доставки
        $selectedShippingMethod = $request->input('shipping_method', 'courier'); // Значение по умолчанию
        $shippingCost = $shippingMethods[$selectedShippingMethod]['cost'];

        // Рассчитываем общую сумму с учетом доставки
        $totalWithShipping = $total + $shippingCost;

        return view('web.checkout', compact('cartItems', 'total', 'shippingMethods', 'shippingCost', 'totalWithShipping', 'selectedShippingMethod'));
    }

    public function placeOrder(Request $request)
    {
        // dd($request->all());
        // 1. Получаем товары из таблицы cart
        $cartItems = Cart::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Корзина пуста');
        }
        // dd($cartItems);

        // Получаем стоимость доставки
        $shippingMethod = $request->input('shipping_method');
        $shippingCost = config("shipping.methods.$shippingMethod.cost");

        // Рассчитываем общую сумму заказа
        $cartItems = Cart::where('user_id', Auth::id())->get();
        $total = $cartItems->sum('price_x_quantity') + $shippingCost;

        // 2. Создаем заказ
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $total,
            'shipping_price' => $shippingCost, // Добавляем сюда стоимость доставки
            'order_number' => uniqid('OrderNumber-'),

            'status' => 'В процессе выполнения',
            'payment_method' => $request->input('payment_method'),
            'payment_status' => 'Не оплачен',

            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'city' => $request->input('city'),
            'country' => $request->input('country'),
            'zip_code' => $request->input('zip_code'),
            'tel' => $request->input('tel'),
            'address' => $request->input('address'),

            'dif_first_name' => $request->input('dif_first_name'),
            'dif_last_name' => $request->input('dif_last_name'),
            'dif_email' => $request->input('dif_email'),
            'dif_city' => $request->input('dif_city'),
            'dif_country' => $request->input('dif_country'),
            'dif_zip_code' => $request->input('dif_zip_code'),
            'dif_tel' => $request->input('dif_tel'),
            'dif_address' => $request->input('dif_address'),

            'shipping_status' => 'Не отправлен',
            'order_notes' => $request->input('order_notes', ''),
            'order_date' => now(),
            'delivery_date' => null,
        ]);

        // dd($order);

        // 3. Переносим товары в `order_items`
        foreach ($cartItems as $item) {
            // dd($item->quantity); // Проверь значение quantity=1
            $order->orderItems()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
                'price_x_quantity' => $item->quantity * $item->product->price,
            ]);
        }
        // dd($item);
        // 4. Очищаем корзину после оформления заказа
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('order.success', ['order' => $order->id]);
        /* return response()->json([
            'success' => true,
            'message' => 'Заказ успешно оформлен!',
            'redirect_url' => route('home'),
        ]); */
        // return redirect()->route('home')->with('message', 'Заказ успешно оформлен!');
    }
}
