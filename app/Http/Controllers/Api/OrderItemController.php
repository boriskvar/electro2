<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderItemController extends Controller
{
    public function index(Order $order)
    {
        // Получаем элементы заказа с связанными продуктами
        $orderItems = $order->orderItems()->with('product')->get();

        return view('order_items.index', compact('order', 'orderItems'));
    }

    public function create()
    {
        $products = Product::all(); // Получаем все продукты
        $orders = Order::all(); // Получаем все заказы
        return view('order_items.create', compact('products', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id', // Валидация для заказа
            'product_id' => 'required|exists:products,id', // Проверка существования продукта
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        $data = $request->all();

        // Находим продукт и получаем его изображение
        $product = Product::findOrFail($data['product_id']);
        $data['image'] = $product->images; // Предполагается, что это поле с изображением в модели Product

        // Создаем элемент заказа
        OrderItem::create($data);

        // Перенаправляем на индекс элементов заказа с указанием ID заказа
        return redirect()->route('order-items.index', ['order' => $data['order_id']])
            ->with('success', 'Элемент заказа создан успешно.');
    }

    public function show(OrderItem $orderItem)
    {
        // Получаем родительский заказ
        $order = $orderItem->order; // Предполагается, что у вас есть отношение 'order' в модели OrderItem

        // Получаем продукт, связанный с элементом заказа
        $product = $orderItem->product; // Получаем продукт через связь

        return view('order_items.show', compact('orderItem', 'order', 'product'));
    }

    public function edit(OrderItem $orderItem)
    {
        // Получаем все заказы для выпадающего списка
        $orders = Order::all();

        // Получаем все продукты для выпадающего списка
        $products = Product::all();

        // Передаем $orders и $products в представление, а также сам элемент заказа
        return view('order_items.edit', compact('orderItem', 'orders', 'products'));
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        // Валидация входящих данных
        $request->validate([
            'order_id' => 'required|exists:orders,id', // Валидация для заказа
            'product_id' => 'required|exists:products,id', // Проверка существования продукта
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric',
        ]);

        // Обновление данных элемента заказа
        $data = $request->all();

        // Обновление элемента заказа
        $orderItem->update($data);


        // Перенаправление на страницу списка элементов заказа
        return redirect()->route('order-items.index', ['order' => $orderItem->order_id])->with('success', 'Элемент заказа обновлен успешно.');
    }

    public function destroy(OrderItem $orderItem)
    {
        // Получаем заказ, к которому относится элемент
        $orderId = $orderItem->order_id; // Инициализируем переменную $orderId

        try {
            // Удаление элемента заказа
            $orderItem->delete();

            // Перенаправляем обратно на страницу всех элементов этого заказа
            return redirect()->route('order-items.index', ['order' => $orderId])->with('success', 'Элемент заказа успешно удален.');
        } catch (\Exception $e) {
            return redirect()->route('order-items.index', ['order' => $orderId])->with('error', 'Ошибка при удалении элемента заказа: ' . $e->getMessage());
        }
    }
}