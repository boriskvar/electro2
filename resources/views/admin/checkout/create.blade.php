@extends('layouts.admin')

@section('content')
<div class="container">
    <a href="{{ route('admin.checkout.list') }}" class="btn btn-primary">Назад к списку оформлений заказов</a>
    <h1>Оформление нового заказа</h1>

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('admin.checkout.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="user_id">Пользователь</label>
            <select name="user_id" id="user_id" class="form-control" required>
                <option value="">Выберите пользователя</option>
                @if ($users->isNotEmpty())
                @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
                @else
                <option value="">Нет доступных пользователей</option>
                @endif
            </select>
            @error('user_id')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="shipping_address">Адрес доставки</label>
            <input type="text" name="shipping_address" id="shipping_address" class="form-control" required>
            @error('shipping_address')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="billing_first_name">Имя плательщика</label>
            <input type="text" name="billing_first_name" id="billing_first_name" class="form-control" required>
            @error('billing_first_name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="billing_last_name">Фамилия плательщика</label>
            <input type="text" name="billing_last_name" id="billing_last_name" class="form-control" required>
            @error('billing_last_name')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="billing_email">Email плательщика</label>
            <input type="email" name="billing_email" id="billing_email" class="form-control" required>
            @error('billing_email')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="billing_tel">Телефон плательщика</label>
            <input type="tel" name="billing_tel" id="billing_tel" class="form-control" required>
            @error('billing_tel')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="billing_address_line_1">Адрес плательщика (строка 1)</label>
            <input type="text" name="billing_address_line_1" id="billing_address_line_1" class="form-control" required>
            @error('billing_address_line_1')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="billing_address_line_2">Адрес плательщика (строка 2)</label>
            <input type="text" name="billing_address_line_2" id="billing_address_line_2" class="form-control">
            @error('billing_address_line_2')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="billing_city">Город плательщика</label>
            <input type="text" name="billing_city" id="billing_city" class="form-control" required>
            @error('billing_city')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="billing_country">Страна плательщика</label>
            <input type="text" name="billing_country" id="billing_country" class="form-control" required>
            @error('billing_country')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="billing_zip_code">Почтовый индекс плательщика</label>
            <input type="text" name="billing_zip_code" id="billing_zip_code" class="form-control" required>
            @error('billing_zip_code')
            <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="order_notes">Примечания к заказу</label>
            <textarea name="order_notes" id="order_notes" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Оформить заказ</button>
    </form>


    @if ($cartItems->isEmpty())
    <div class="alert alert-warning mt-3">
        Ваша корзина пуста. <a href="{{ route('admin.cart.index') }}">Перейти в корзину</a>
    </div>
    @else
    <table class="table mt-3">
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

    <!-- Итоговая сумма -->
    <div class="text-right mb-3">
        <strong class="text-success" style="font-size: 1.5em;">Итого к оплате: {{ number_format($totalAmount, 2, ',', ' ') }} грн.</strong>
    </div>
    @endif
</div>
@endsection
