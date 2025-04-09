<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart; // Убедитесь, что модель Cart импортирована

class UserCheckoutController extends Controller
{
    // Показать страницу оформления заказа
    public function index()
    {
        $userId = Auth::id(); // Получаем ID текущего пользователя
        // Временно используем временный ID пользователя для тестирования
        // $userId = 1; // Замените на Auth::id() для продакшн-версии

        $cartItems = Cart::where('user_id', $userId)->get(); // Получаем товары в корзине

        // Проверяем, пуста ли корзина
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Ваша корзина пуста.');
        }

        // Можно также посчитать общую сумму
        $totalAmount = $cartItems->sum('total');

        return view('user.checkout.index', compact('cartItems', 'totalAmount'));
    }

    // Обработать оформление заказа
    public function store(Request $request)
    {
        // Валидация входящих данных
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            // Добавьте другие необходимые поля
        ]);

        // Создаем новый заказ
        $order = new Order();
        //$order->user_id = Auth::id();
        // Устанавливаем временный user_id = 1 для разработки
        $userId = app()->environment('local') ? 1 : Auth::id();

        $order->user_id = $userId;
        $order->shipping_address = $request->input('shipping_address');
        $order->payment_method = $request->input('payment_method');

        // Заполняем остальные поля заказа (например, сумму, статус и т.д.)
        //$cartItems = Cart::where('user_id', Auth::id())->get();
        $cartItems = Cart::where('user_id', $userId)->get(); // Используем временный userId
        $order->total_price = $cartItems->sum('total'); // Общая сумма заказа
        $order->status = 'pending'; // Статус заказа (например, 'pending')

        // Генерация уникального номера заказа, если он не передан в запросе
        do {
            $order->order_number = 'ORD-' . date('Ymd') . '-' . random_int(1000, 9999);
        } while (Order::where('order_number', $order->order_number)->exists());


        // Сохраните заказ в базе данных
        $order->save();

        // Удаляем товары из корзины после оформления заказа
        //Cart::where('user_id', Auth::id())->delete();
        Cart::where('user_id', $userId)->delete(); // Используем временный userId

        return redirect()->route('checkout.index')->with('success', 'Заказ оформлен успешно.');
    }
}
