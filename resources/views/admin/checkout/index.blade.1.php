@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Оформление заказа</h1>

    @if ($cartItems->isEmpty())
    <div class="alert alert-warning">
        Ваша корзина пуста. <a href="{{ route('admin.cart.index') }}">Перейти в корзину</a>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if (!$cartItems->isEmpty())
    <table class="table">
        <thead>
            <tr>
                <th>Товар</th>
                <th>Название</th>
                <th>Цена за ед.</th>
                <th>Количество</th>
                <th>Сумма</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cartItems as $item)
            <tr>
                <td>
                    @php
                    // Получаем изображение продукта из JSON
                    $images = json_decode($item->product->images, true);
                    $firstImage = $images[0] ?? null;
                    @endphp
                    @if ($firstImage && Storage::disk('public')->exists('img/' . basename($firstImage)))
                    <img src="{{ asset('storage/img/' . basename($firstImage)) }}" alt="{{ $item->product->name }}" width="50">
                    @else
                    <p>Изображение недоступно</p>
                    @endif
                </td>
                <td>{{ $item->product->name }} <br> Продавец: {{ $item->product->seller }}</td>
                <td>{{ number_format($item->price, 2, ',', ' ') }} грн.</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->total, 2, ',', ' ') }} грн.</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Добавляем сумму скидки -->
    @if ($discountPercentage = $cartItems->sum('discount_percentage'))
    <div class="text-right mb-3">
        <strong class="text-info" style="font-size: 1.2em;">Скидка: {{ number_format($discountPercentage) }} %</strong>
    </div>
    @endif

    <!-- Итоговая сумма -->
    <div class="text-right mb-3">
        <strong class="text-success" style="font-size: 1.5em;">Итого к оплате: {{ number_format($totalAmount - $discountPercentage, 2, ',', ' ') }} грн.</strong>
    </div>

    <form action="{{ route('admin.checkout.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="shipping_address">Адрес доставки</label>
            <input type="text" name="shipping_address" id="shipping_address" class="form-control" required>
            @error('shipping_address')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="payment_method">Метод оплаты</label>
            <select name="payment_method" id="payment_method" class="form-control" required>
                <option value="cash">Наличными</option>
                <option value="credit_card">Кредитной картой</option>
                <!-- Добавьте другие методы оплаты по необходимости -->
            </select>
        </div>

        <div class="form-group">
            <label for="order_notes">Примечания к заказу</label>
            <textarea name="order_notes" id="order_notes" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Подтвердить заказ</button>
    </form>
    @endif
</div>
@endsection
