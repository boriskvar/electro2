<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Cart;

class AdminOrderController extends Controller
{
    // Вывод списка заказов
    public function index(Request $request)
    {
        //dd($request->toArray());
        // Получаем параметр фильтрации статуса из запроса
        $status = $request->input('status', ''); // Устанавливаем значение по умолчанию, если статус не задан

        // Формируем запрос для получения заказов вместе с пользователями
        $query = Order::with('user');

        // Если статус выбран, добавляем условие фильтрации
        if (!empty($status)) {
            $query->where('status', $status);
        }

        // Пагинация для удобства отображения
        $orders = $query->paginate(10); // Показать по 10 заказов на странице

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function create()
    {
        $orderNumber = 'ORD-' . date('Ymd-His');
        return view('admin.orders.create', [
            'orderNumber' => $orderNumber,
            'users' => User::all(),
            'products' => Product::all(),
            'categories' => Category::all(),
            'brands' => [], // Пока оставляем пустой массив
            'totalPrice' => 0
        ]);
    }

    // Сохранить новый заказ
    public function store(Request $request)
    {
        // Валидация данных
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'payment_description' => 'nullable|string',
            'payment_status' => 'required|string',
            'shipping_price' => 'required|numeric|min:0', // Стоимость доставки

            // Поля для выставления счета
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'tel' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',

            // Поля для доставки
            'dif_first_name' => 'nullable|string|max:255',
            'dif_last_name' => 'nullable|string|max:255',
            'dif_email' => 'nullable|email|max:255',
            'dif_city' => 'nullable|string|max:255',
            'dif_country' => 'nullable|string|max:255',
            'dif_zip_code' => 'nullable|string|max:10',
            'dif_tel' => 'nullable|string|max:15',
            'dif_address' => 'nullable|string|max:255',

            'shipping_status' => 'required|string|in:courier,pickup,post',
            'order_notes' => 'nullable|string|max:255',

            'order_date' => 'nullable|date',
            'delivery_date' => 'nullable|date',

            'product_id' => 'required|array|min:1', // Обязательно, должен быть массив, минимум 1 элемент
            'product_id.*' => 'exists:products,id', // Каждый элемент массива должен быть существующим ID товара
            // 'quantity' => 'required|array', // Добавлено поле для количеств
            // 'quantity.*' => 'required|integer|min:1', // Валидация для количеств
        ]);

        // dd($request->toArray());

        // Генерация уникального номера заказа
        do {
            // $orderNumber = 'ORD-' . date('Ymd-His') . '-' . strtoupper(uniqid());
            $orderNumber = 'ORD-' . date('Ymd-His');
        } while (Order::where('order_number', $orderNumber)->exists());
        // Определение описания метода оплаты


        // Создание нового заказа
        $order = new Order([
            'user_id' => $request->user_id,
            'order_number' => $orderNumber,
            'status' => $request->status,
            'payment_method' => $request->payment_method,
            'payment_description' => $request->payment_description,
            'payment_status' => $request->payment_status,
            'shipping_price' => $request->shipping_price,

            // Поля для выставления счета
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'city' => $request->city,
            'country' => $request->country,
            'zip_code' => $request->zip_code,
            'tel' => $request->tel,
            'address' => $request->address,

            // Поля для доставки
            'dif_first_name' => $request->dif_first_name,
            'dif_last_name' => $request->dif_last_name,
            'dif_email' => $request->dif_email,
            'dif_city' => $request->dif_city,
            'dif_country' => $request->dif_country,
            'dif_zip_code' => $request->dif_zip_code,
            'dif_tel' => $request->dif_tel,
            'dif_address' => $request->dif_address,

            'shipping_status' => $request->shipping_status,
            'order_notes' => $request->order_notes,
            'order_date' => $request->order_date,
            'delivery_date' => $request->delivery_date
        ]);
        $order->save();

        // Получаем все товары из заказа
        $productIds = $request->input('product_id', []);
        // $quantities = $request->input('quantity', []);
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // Инициализация общей стоимости
        $totalPrice = 0;

        foreach ($productIds as $index => $productId) {
            if (isset($products[$productId])) {
                $product = $products[$productId];

                // По умолчанию количество равно 1, если не указано другое значение
                $quantity = 1;

                $orderItem = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price_x_quantity' => $product->price * $quantity
                ]);
                $orderItem->save();

                $totalPrice += $orderItem->price_x_quantity;
            }
        }

        // Добавляем стоимость доставки
        $shippingPrice = $request->input('shipping_price', 0);  // Получаем стоимость доставки из формы или 0 по умолчанию
        $totalPrice += $shippingPrice;  // Добавляем стоимость доставки к общей цене

        // Обновляем поле общей стоимости заказа
        $order->total_price = $totalPrice;
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Заказ создан успешно.');
    }



    // Показать один заказ
    public function show(Order $order)
    {
        // Получаем всех пользователей для выпадающего списка
        $users = User::all();

        return view('admin.orders.show', compact('order', 'users'));
    }

    // Показать форму для редактирования заказа
    public function edit(Order $order)
    {
        // dd($order);
        // Получаем всех пользователей для выпадающего списка
        $users = User::all();
        $products = Product::all(); // Получаем все продукты

        // Извлекаем продукты в заказе через OrderItem
        $orderItems = $order->orderItems; // Получаем связанные товары для заказа: function products()

        // Вычисляем общую цену для каждого элемента заказа
        // foreach ($orderItems as $orderItem) {
        //     // Пересчитываем price_x_quantity на основе связанного товара
        //     $orderItem->price_x_quantity = $orderItem->product->price * $orderItem->quantity;
        // }

        return view('admin.orders.edit', compact('order', 'users', 'products', 'orderItems'));
    }

    // Обновить существующий заказ
    public function update(Request $request, Order $order)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string',
            'payment_method' => 'required|string',
            'payment_description' => 'nullable|string',
            'payment_status' => 'required|string',
            'shipping_price' => 'required|numeric|min:0',

            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'city' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'tel' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',


            'dif_first_name' => 'nullable|string|max:255',
            'dif_last_name' => 'nullable|string|max:255',
            'dif_email' => 'nullable|email|max:255',
            'dif_city' => 'nullable|string|max:255',
            'dif_country' => 'nullable|string|max:255',
            'dif_zip_code' => 'nullable|string|max:10',
            'dif_tel' => 'nullable|string|max:15',
            'dif_address' => 'nullable|string|max:255',

            'shipping_status' => 'required|string|in:courier,pickup,post',
            'order_notes' => 'nullable|string|max:255',
            'order_date' => 'nullable|date',
            'delivery_date' => 'nullable|date',

            'product_id' => 'required|array|min:1',
            'product_id.*' => 'exists:products,id',

        ]);

        // Обновляем данные заказа
        $order->fill($validatedData);
        // Обновляем описание метода оплаты, если передано
        $order->payment_description = $request->input('payment_description', $order->payment_description);
        // Сохраняем заказ
        $order->save();

        // Получаем переданные ID товаров
        $incomingProductIds = collect($validatedData['product_id']);

        // Удаляем товары, которых нет в новом списке
        $order->orderItems()
            ->whereNotIn('product_id', $incomingProductIds)
            ->delete();

        // Обновляем или добавляем товары в заказ
        foreach ($validatedData['product_id'] as $index => $productId) {
            // Получаем товар, чтобы узнать его текущее количество в заказе
            $existingItem = $order->orderItems()->where('product_id', $productId)->first();


            // Если товар уже есть в заказе, берем его количество. Иначе предполагаем, что количество = 1.
            $quantity = $existingItem ? $existingItem->quantity : 1;

            // Получаем товар, чтобы обновить цену
            $product = Product::findOrFail($productId);
            $price_x_quantity = $product->price * $quantity;

            // Обновляем или создаем новый элемент в заказе
            $order->orderItems()->updateOrCreate(
                ['order_id' => $order->id, 'product_id' => $productId],
                ['quantity' => $quantity, 'price_x_quantity' => $price_x_quantity]
            );
        }

        // 🔹 Пересчитываем `total_price` после обновления товаров
        $totalProductPrice = $order->orderItems()->sum('price_x_quantity'); // Сумма товаров
        $shippingPrice = (float) $validatedData['shipping_price']; // Преобразуем в число
        $order->total_price = $totalProductPrice + $shippingPrice; // Общая сумма

        // dd($order->total_price, $order->shipping_price);
        // dd($order->total_price); //410
        // dd($order->shipping_price); //10
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно обновлен');
    }


    // Удалить заказ
    public function destroy(Order $order)
    {
        try {
            $order->delete();
            return redirect()->route('admin.orders.index')->with('success', 'Заказ успешно удален.');
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Ошибка при удалении заказа: ' . $e->getMessage());
        }
    }
}
