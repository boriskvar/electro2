@extends('home')

@section('content')
<div class="container">
    <h1>Оформление заказа</h1>

    @if ($cartItems->isEmpty())
    <div class="alert alert-warning">
        Ваша корзина пуста. <a href="{{ route('cart.index') }}">Перейти в корзину</a>
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

    <p><strong>Итого: {{ number_format($totalAmount, 2, ',', ' ') }} ₽</strong></p>

    <form action="{{ route('checkout.store') }}" method="POST">
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

        <button type="submit" class="btn btn-primary">Подтвердить заказ</button>
    </form>

    <h2>Ваши товары</h2>
    <ul>
        @foreach ($cartItems as $item)
        <li>{{ $item->product->name }} - {{ $item->quantity }} шт. - {{ number_format($item->total, 2, ',', ' ') }} ₽</li>
        @endforeach
    </ul>
</div>
@endsection
