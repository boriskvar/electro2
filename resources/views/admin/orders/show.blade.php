@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Заказ №{{ $order->order_number }}</h1>
    <!-- Товары в заказе -->
    <h2 class="mt-4">Товары в заказе</h2>
    @if ($order->orderItems->isNotEmpty())
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Изображение</th>
                <th>Наименование</th>
                <th>Цена(за ед.)</th>
                <th>Количество</th>
                <th>Цена(x)кол-во</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->orderItems as $orderItem)
            <tr>
                <td>
                    @php
                    // $imagePath = json_decode($orderItem->product->images, true);
                    $imagePath = $orderItem->product->images;
                    @endphp

                    @if (!empty($imagePath) && isset($imagePath[0]))
                    <img src="{{ asset('storage/img/' . basename($imagePath[0])) }}" alt="{{ $orderItem->product->name }}" style="width: 50px; height: auto;">
                    @else
                    <img src="{{ asset('storage/default.png') }}" alt="No image" style="width: 50px; height: auto;">
                    <!-- Заглушка -->
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.products.show', $orderItem->product_id) }}">{{ $orderItem->product->name }}</a>
                </td>
                <td>{{ number_format($orderItem->product->price, 2) }} грн.</td>
                <td>{{ $orderItem->quantity }} шт.</td>
                <td>{{ number_format($orderItem->product->price * $orderItem->quantity, 2) }} грн.</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Нет товаров в заказе.</p>
    @endif

    <!-- Основная информация о заказе -->
    <div class="order-details">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="user" style="font-weight: normal; margin-right: 10px;">Пользователь:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->user->name }} ({{ $order->user->email }})
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="total_price" style="font-weight: normal; margin-right: 10px;">Общая стоимость:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ number_format($order->total_price, 2) }} грн.
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="status" style="font-weight: normal; margin-right: 10px;">Статус заказа:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->status }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="order_date" style="font-weight: normal; margin-right: 10px;">Дата заказа:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->order_date }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="payment_method" style="font-weight: normal; margin-right: 10px;">Метод оплаты:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->payment_method }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="payment_description" style="font-weight: normal; margin-right: 10px;">Описание для оплаты:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->payment_description }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="payment_status" style="font-weight: normal; margin-right: 10px;">Статус оплаты:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->payment_status }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="notes" style="font-weight: normal; margin-right: 10px;">Заметки:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->order_notes }}
            </div>
        </div>
    </div>

    <!-- Биллинг -->
    <div class="billing-details mt-4">
        <h3>Биллинг</h3>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="name" style="font-weight: normal; margin-right: 10px;">Имя:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->first_name }} {{ $order->last_name }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="email" style="font-weight: normal; margin-right: 10px;">Email:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->email }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="city" style="font-weight: normal; margin-right: 10px;">Город:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->city }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="country" style="font-weight: normal; margin-right: 10px;">Страна:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->country }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="zip_code" style="font-weight: normal; margin-right: 10px;">Почтовый индекс:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->zip_code }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="tel" style="font-weight: normal; margin-right: 10px;">Телефон:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->tel }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="address" style="font-weight: normal; margin-right: 10px;">Адрес:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->address }}
            </div>
        </div>
    </div>
    <!-- Доставка -->
    <div class="shipping-details mt-4">
        <h3>Доставка</h3>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="dif_first_name" style="font-weight: normal; margin-right: 10px;">Имя 2:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->dif_first_name }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="dif_last_name" style="font-weight: normal; margin-right: 10px;">Фамилия 2:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->dif_last_name }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="dif_email" style="font-weight: normal; margin-right: 10px;">Email 2:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->dif_email }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="shipping_address_line_1" style="font-weight: normal; margin-right: 10px;">Адрес 2:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->dif_address }}
            </div>
        </div>


        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="dif_city" style="font-weight: normal; margin-right: 10px;">Город 2:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->dif_city }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="dif_country" style="font-weight: normal; margin-right: 10px;">Страна 2:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->dif_country }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="dif_zip_code" style="font-weight: normal; margin-right: 10px;">Почтовый индекс 2:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->dif_zip_code }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="dif_tel" style="font-weight: normal; margin-right: 10px;">Телефон 2:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->dif_tel }}
            </div>
        </div>



        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="delivery_date" style="font-weight: normal; margin-right: 10px;">Дата доставки:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->delivery_date }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="shipping_status" style="font-weight: normal; margin-right: 10px;">Варианты доставки:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $order->shipping_status }}
            </div>
        </div>
    </div>


    <a href="{{ route('admin.orders.index') }}" class="btn btn-warning">Назад к списку заказов</a>
</div>
@endsection