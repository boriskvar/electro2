<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminOrderItemController extends Controller
{
    public function index(Order $order)
    {
        // Получаем элементы заказа с связанными продуктами
        // $orderItems = $order->orderItems()->with('product')->get();
        $orderItems = \App\Models\OrderItem::with(['order', 'product'])->get();

        // dd($orderItems);

        return view('admin.order_items.index', compact('order', 'orderItems'));
    }

    public function create(Order $order)
    {
        $products = Product::all(); // Получаем все продукты
        $orders = Order::all(); // Получаем все заказы для выбора

        return view('admin.order_items.create', compact('products', 'orders', 'order'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id', // Валидация для заказа
            'product_id' => 'required|exists:products,id', // Проверка существования продукта
            'quantity' => 'required|integer|min:1',
        ]);


        // Находим продукт и получаем его изображение
        $product = Product::findOrFail($request->product_id);
        $data = $request->all();
        $data['price_x_quantity'] = $product->price * $data['quantity']; // Рассчитываем итоговую цену
        $data['image'] = $product->images;  // Поле для изображения из модели Product

        // Создаем элемент заказа
        OrderItem::create($data);

        // Перенаправляем на индекс элементов заказа с указанием ID заказа
        return redirect()->route('admin.order-items.index', ['order' => $data['order_id']])
            ->with('success', 'Элемент заказа создан успешно.');
    }

    public function show(OrderItem $orderItem)
    {
        // Получаем родительский заказ
        $order = $orderItem->order; // Предполагается, что у вас есть отношение 'order' в модели OrderItem

        // Получаем продукт, связанный с элементом заказа
        $product = $orderItem->product; // Получаем продукт через связь

        return view('admin.order_items.show', compact('orderItem', 'order', 'product'));
    }

    public function edit(OrderItem $orderItem)
    {
        // Получаем все заказы для выпадающего списка
        $orders = Order::all();

        // Получаем все продукты для выпадающего списка
        $products = Product::all();

        // Передаем $orders и $products в представление, а также сам элемент заказа
        return view('admin.order_items.edit', compact('orderItem', 'orders', 'products'));
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        // Валидация входящих данных
        $request->validate([
            'order_id' => 'required|exists:orders,id', // Валидация для заказа
            'product_id' => 'required|exists:products,id', // Проверка существования продукта
            'quantity' => 'required|integer|min:1',
        ]);

        // Получаем заказ до обновления
        // $order = Order::findOrFail($orderItem->order_id);

        // Находим продукт, чтобы получить его цену
        $product = Product::findOrFail($request->product_id);

        // Расчет новой цены для элемента заказа
        $orderItem->update([
            'order_id' => $request->order_id,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'price_x_quantity' => $product->price * $request->quantity,
        ]);

        // Пересчет общей стоимости заказа
        // $this->recalculateTotalPrice($order);
        // 🔹 Пересчет `total_price`
        $orderItem->order->update([
            'total_price' => $orderItem->order->orderItems()->sum('price_x_quantity') + $orderItem->order->shipping_price
        ]);

        // Перенаправление на страницу списка элементов заказа
        return redirect()->route('admin.order-items.index', ['order' => $orderItem->order_id])->with('success', 'Элемент заказа обновлен успешно.');
    }

    // Функция пересчета total_price
    protected function recalculateTotalPrice(Order $order)
    {
        // Пересчитываем общую сумму товаров
        $totalProductPrice = $order->orderItems()->sum('price_x_quantity');

        // Берем стоимость доставки
        $shippingPrice = (float) $order->shipping_price;

        // Пересчитываем `total_price`
        $order->total_price = $totalProductPrice + $shippingPrice;

        // Отладка, если `total_price` не меняется
        // dd($totalProductPrice, $shippingPrice, $order->total_price);

        $order->save();
    }


    public function destroy(OrderItem $orderItem)
    {
        // Получаем заказ, к которому относится элемент
        $orderId = $orderItem->order_id; // Инициализируем переменную $orderId

        try {
            // Удаление элемента заказа
            $orderItem->delete();

            // Перенаправляем обратно на страницу всех элементов этого заказа
            return redirect()->route('admin.order-items.index', ['order' => $orderId])->with('success', 'Элемент заказа успешно удален.');
        } catch (\Exception $e) {
            return redirect()->route('admin.order-items.index', ['order' => $orderId])->with('error', 'Ошибка при удалении элемента заказа: ' . $e->getMessage());
        }
    }
}
