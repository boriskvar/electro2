<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    // Вывод списка заказов
    public function index()
    {
        $orders = Order::with('user')->get(); // Получаем заказы вместе с пользователями
        return view('orders.index', compact('orders'));
    }

    // Показать форму для создания нового заказа
    public function create()
    {
        // Генерация номера заказа
        $orderNumber = 'ORD-' . date('Ymd-His'); // Пример: ORD-20231019-153045
        $users = User::all(); // Получение всех пользователей
        $products = Product::all(); // Получение всех товаров

        // Передаем данные в представление
        return view('orders.create', compact('orderNumber', 'users', 'products'));
    }


    // Сохранить новый заказ
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            'user_id' => 'required|exists:users,id', // Проверка существования пользователя
            'total_price' => 'required|numeric',
            'status' => 'required|string',
            'order_date' => 'required|date',
            'shipping_address' => 'nullable|string',
            'delivery_date' => 'nullable|date',
            'shipping_status' => 'required|string|in:курьером,самовывоз,почтой', // Обязательное поле с ограничением значений
            'payment_method' => 'required|string',
            'discount' => 'nullable|numeric|min:0|max:100',
            // Валидация для нового поля order_notes
            'order_notes' => 'nullable|string|max:255', // Убедитесь, что длина не превышает 255 символов
            // Валидация для элементов заказа
            'order_items' => 'required|array', // Проверяем, что order_items присутствует
            'order_items.*.product_id' => 'required|exists:products,id',
            'order_items.*.quantity' => 'required|integer|min:1',
            'order_items.*.price' => 'required|numeric',
        ]);
        //dd($request->all());
        $orderNumber = $request->order_number;

        // Проверяем, существует ли уже такой номер заказа
        if (Order::where('order_number', $orderNumber)->exists()) {
            return redirect()->back()->withErrors(['order_number' => 'Этот номер заказа уже существует.']);
        }

        // Создание заказа
        $order = Order::create($request->only([
            'user_id',
            'order_number',
            'total_price',
            'status',
            'order_date',
            'shipping_address',
            'delivery_date',
            'shipping_status',
            'payment_method',
            'discount',
            'order_notes'
        ]));
        //dd($order->toArray());

        // Привязка товаров к заказу
        foreach ($request->order_items as $item) {
            $orderItem = new OrderItem([
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);

            // Привязка к заказу
            $orderItem->order_id = $order->id; // Явно указать ID заказа
            $orderItem->save(); // Сохранение
        }

        //dd($order->toArray());
        //dd($orderItem->toArray());
        return redirect()->route('orders.index')->with('success', 'Заказ создан успешно.');
    }

    /*protected function generateOrderNumber()
    {
        // Генерация уникального номера заказа
        return 'ORD-' . date('Ymd-His') . '-' . substr(uniqid(), -6);
    }*/



    // Показать один заказ
    public function show(Order $order)
    {
        // Получаем всех пользователей для выпадающего списка
        $users = User::all();

        return view('orders.show', compact('order', 'users'));
    }

    // Показать форму для редактирования заказа
    public function edit(Order $order)
    {
        // Получаем всех пользователей для выпадающего списка
        $users = User::all();
        $products = Product::all(); // Получаем все продукты

        // Извлекаем продукты в заказе через OrderItem
        $orderItems = $order->orderItems; // Получаем связанные товары для заказа

        return view('orders.edit', compact('order', 'users', 'products', 'orderItems'));
    }

    // Обновить существующий заказ
    public function update(Request $request, Order $order)
    {
        //dd($request->all());
        $request->validate([
            'user_id' => 'required|exists:users,id', // Проверка существования пользователя
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string',
            'shipping_address' => 'nullable|string|max:255',
            'order_date' => 'required|date',
            'delivery_date' => 'nullable|date',
            'payment_method' => 'required|string',
            'discount' => 'nullable|numeric|min:0|max:100',
            'shipping_status' => 'required|string|in:курьером,самовывоз,почтой', // Обязательное поле с ограничением значений
            'products' => 'required|array', // Валидация для массива товаров
            'products.*' => 'exists:products,id' // Проверка существования каждого товара
        ]);
        //dd($request->all());

        // Обновление заказа
        $order->update([
            'user_id' => $request->user_id,
            'total_price' => $request->total_price,
            'status' => $request->status,
            'shipping_address' => $request->shipping_address,
            'order_date' => $request->order_date, // Используем дату заказа из запроса
            'delivery_date' => $request->delivery_date,
            'payment_method' => $request->payment_method,
            'shipping_status' => $request->shipping_status,
            'discount' => $request->discount,
        ]);
        //dd($order->toArray());
        // Удаляем старые товары из заказа
        $order->orderItems()->delete();
        //dd($order->toArray());
        // Привязка новых товаров к заказу с количеством и ценой
        foreach ($request->products as $productId) {
            $product = Product::find($productId); // Получаем продукт для получения его цены
            $quantity = $request->quantities[$productId] ?? 1; // Получаем количество из формы, если не указано - 1

            $order->orderItems()->create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price, // Сохраняем цену на момент заказа
            ]);
        }
        //dd($order->toArray());
        return redirect()->route('orders.index')->with('success', 'Заказ успешно обновлен');
    }


    // Удалить заказ
    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return redirect()->route('orders.index')->with('success', 'Заказ успешно удален.');
        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Ошибка при удалении заказа: ' . $e->getMessage());
        }
    }
}