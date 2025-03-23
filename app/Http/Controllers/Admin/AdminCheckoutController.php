<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Cart; // Убедитесь, что модель Cart импортирована
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Checkout;
use App\Models\User;

class AdminCheckoutController extends Controller
{
    // Показать страницу оформления обработки заказа
    public function index()
    {
        // Временно используем временный ID пользователя для тестирования
        $userId = 1; // Замените на Auth::id() для продакшн-версии

        // Получаем товары в корзине
        $cartItems = Cart::where('user_id', $userId)->get();
        //dd($cartItems->toArray()); //[]
        // Проверяем, пуста ли корзина
        /*if ($cartItems->isEmpty()) {
            // Отображаем сообщение об ошибке без перенаправления
            session()->flash('error', 'Ваша корзина пуста.');
            return view('admin.checkout.create', compact('cartItems'));
        }*/
        //dd($cartItems->toArray());

        // Считаем общую сумму с учетом скидок
        $totalAmount = $cartItems->sum('total'); // Подразумевается, что в модели Cart есть поле 'total'

        // Получаем всех пользователей
        $users = User::all(); // Получаем всех пользователей

        // Возвращаем представление с корзиной и общей суммой
        return view('admin.checkout.index', compact('cartItems', 'totalAmount', 'users'));
    }


    // Показать список всех заказов
    public function list()
    {
        // Получаем все заказы
        $orders = Order::with('user')->paginate(10); // Можно использовать пагинацию
        return view('admin.checkout.list', compact('orders'));
    }

    public function create()
    {
        //$userId = Auth::id(); // Получаем ID текущего пользователя
        // Временно используем временный ID пользователя для тестирования
        $userId = 1; // Замените на Auth::id() для продакшн-версии
        $cartItems = Cart::where('user_id', $userId)->get(); // Получаем товары в корзине

        /*if ($cartItems->isEmpty()) {
            session()->flash('error', 'Ваша корзина пуста.');
            return view('admin.checkout.create', compact('cartItems'));
        }*/

        $totalAmount = $cartItems->sum('total');
        $users = User::all(); // Получаем всех пользователей
        //dd($users->toArray());
        return view('admin.checkout.create', compact('cartItems', 'totalAmount', 'users'));
    }



    // Показать форму редактирования заказа
    public function edit($id)
    {
        $order = Order::with('user')->findOrFail($id); // Получаем заказ по ID или 404
        return view('admin.checkout.edit', compact('order'));
    }


    // Обработать оформление заказа
    public function store(Request $request)
    {
        //dd($request->toArray());
        // Валидация входящих данных
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shipping_address' => 'required|string|max:255',
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_email' => 'required|email|max:255',
            'billing_tel' => 'required|string|max:15',
            'billing_address_line_1' => 'required|string|max:255',
            'billing_address_line_2' => 'nullable|string|max:255',
            'billing_city' => 'required|string|max:100',
            'billing_country' => 'required|string|max:100',
            'billing_zip_code' => 'required|string|max:10',
            'order_notes' => 'nullable|string|max:500',
        ]);
        //dd($request->toArray());
        //$userId = app()->environment('local') ? 1 : Auth::id();
        //$cartItems = Cart::where('user_id', $userId)->get();

        // Получаем товары в корзине
        $cartItems = Cart::where('user_id', $request->input('user_id'))->get();

        // Проверка на пустую корзину
        if ($cartItems->isEmpty()) {
            return redirect()->route('admin.checkout.create')->with('error', 'Ваша корзина пуста. Пожалуйста, добавьте товары для оформления заказа.');
        }

        // Создание заказа
        /*$order = new Order();
        $order->user_id = $userId;
        $order->shipping_address = $request->input('shipping_address');
        $order->payment_method = $request->input('payment_method');
        $order->total_price = $cartItems->sum('total');
        $order->status = 'pending';
        $order->order_notes = $request->input('order_notes', '');
        $order->save();*/

        // Сохранение информации в таблицу checkouts
        // Создаем новую обработку заказа
        $checkout = new Checkout();
        $checkout->user_id = $request->input('user_id');
        //$checkout->cart_items = json_encode(Cart::where('user_id', $request->input('user_id'))->get());
        $checkout->cart_items = json_encode($cartItems);
        //$checkout->total_price = Cart::where('user_id', $request->input('user_id'))->sum('total');
        $checkout->total_price = $cartItems->sum('total');
        $checkout->shipping_address = $request->input('shipping_address');
        $checkout->billing_first_name = $request->input('billing_first_name');
        $checkout->billing_last_name = $request->input('billing_last_name');
        $checkout->billing_email = $request->input('billing_email');
        $checkout->billing_tel = $request->input('billing_tel');
        $checkout->billing_address_line_1 = $request->input('billing_address_line_1');
        $checkout->billing_address_line_2 = $request->input('billing_address_line_2');
        $checkout->billing_city = $request->input('billing_city');
        $checkout->billing_country = $request->input('billing_country');
        $checkout->billing_zip_code = $request->input('billing_zip_code');
        $checkout->order_notes = $request->input('order_notes');

        // Сохраняем заказ в базе данных
        $checkout->save();

        // Очистка корзины (если потребуется)
        // Cart::where('user_id', $userId)->delete();
        // Cart::where('user_id', $request->input('user_id'))->delete();

        // Отправляем сообщение об успешном создании
        return redirect()->route('admin.checkout.list')->with('success', 'Обработка заказа оформлена успешно.');
    }



    // Показать конкретный заказ по ID
    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id); // Получаем заказ по ID или 404
        return view('admin.checkout.show', compact('order'));
    }

    // Обновить заказ
    public function update(Request $request, $id)
    {
        // Валидация входящих данных
        $request->validate([
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            // Добавьте другие необходимые поля
        ]);

        // Получаем заказ по ID или 404
        $order = Order::findOrFail($id);

        // Обновляем информацию о заказе
        $order->shipping_address = $request->input('shipping_address');
        $order->payment_method = $request->input('payment_method');
        // Обновите другие поля по мере необходимости

        // Сохраняем изменения
        $order->save();

        return redirect()->route('admin.checkout.list')->with('success', 'Заказ обновлён успешно.');
    }

    public function destroy($id)
    {
        // Получаем заказ по ID или 404
        $order = Order::findOrFail($id);

        // Удаляем заказ
        $order->delete();

        return redirect()->route('admin.checkout.list')->with('success', 'Заказ удалён успешно.');
    }
}
