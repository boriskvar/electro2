@extends('layouts.admin')

@section('content')

<div class="container">
    <h1>Создать заказ</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.orders.store') }}" method="POST">
        @csrf

        <!-- Поле Выбор пользователя -->
        <div class="form-group">
            <label for="user_id">Пользователь:</label>
            <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id" required>
                <option value="">Выберите пользователя</option>
                @foreach ($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id', $order->user_id ?? '') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
            @error('user_id')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле выбора товаров -->
        <div class="form-group">
            <label for="product_id">Выберите товар:</label>
            {{-- <select name="product_id" id="product_id" class="form-control"> --}}
            <select name="product_id[]" id="product_id" class="form-control" multiple>
                <option value="">-- Выберите товар --</option>

                @forelse ($products as $product)
                <option value="{{ $product->id }}">
                    <span class="text-success">• </span>{{ $product->name }} - {{ $product->price }} $
                </option>
                @empty
                <option disabled>Нет доступных товаров</option>
                @endforelse

            </select>
        </div>



        <!-- Стоимость доставки -->
        <div class="form-group">
            <label for="shipping_price">Стоимость доставки:</label>
            <select class="form-control @error('shipping_price') is-invalid @enderror" id="shipping_price" name="shipping_price" required>
                <option value="15.00" {{ old('shipping_price') == '15.00' ? 'selected' : '' }}>Курьер (15.00 $)</option>
                <option value="10.00" {{ old('shipping_price') == '10.00' ? 'selected' : '' }}>Почта (10.00 $)</option>
                <option value="0.00" {{ old('shipping_price') == '0.00' ? 'selected' : '' }}>Самовывоз (0.00 $)</option>
            </select>
            @error('shipping_price')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Общая цена (Только для чтения) -->
        <div class="form-group">
            <label for="total_price">Общая стоимость:</label>
            {{-- <input class="form-control" id="total_price" type="text" value="0" readonly> --}}
            <input type="text" class="form-control" id="total_price" name="total_price" value="{{ old('total_price', 0) }}" readonly>
        </div>

        <!-- Общая экономия (Только для чтения) -->
        <div class="form-group">
            <label for="total_savings">Общая экономия:</label>
            <input class="form-control" id="total_savings" type="text" value="0" readonly>
        </div>

        <!-- Поле Номер заказа -->
        <div class="form-group">
            <label for="order_number">Номер заказа:</label>
            <input class="form-control @error('order_number') is-invalid @enderror" id="order_number" name="order_number" type="text" value="{{ $orderNumber }}" readonly>
            @error('order_number')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Статус заказа -->
        <div class="form-group">
            <label for="status">Статус заказа:</label>
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="pending" {{ old('status', $order->status ?? '') == 'pending' ? 'selected' : '' }}>В процессе
                    выполнения</option>
                <option value="completed" {{ old('status', $order->status ?? '') == 'completed' ? 'selected' : '' }}>Выполнен
                </option>
                <option value="canceled" {{ old('status', $order->status ?? '') == 'canceled' ? 'selected' : '' }}>Отменен
                </option>
            </select>
            @error('status')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Метод оплаты -->
        <div class="form-group">
            <label for="payment_method">Метод оплаты:</label>
            <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Наличные</option>
                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Карта</option>
                <option value="online" {{ old('payment_method') == 'online' ? 'selected' : '' }}>Онлайн</option>
            </select>
            @error('payment_method')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>



        <!-- Поля для изменения описания методов оплаты -->
        <!-- Описание оплаты -->
        <div class="form-group">
            <label for="payment_description">Описание оплаты</label>
            <textarea name="payment_description" id="payment_description" class="form-control">{{ old('payment_description') }}</textarea>
            @error('payment_description')
            <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        {{-- <div class="form-group">
            <label for="payment_description_cash">Описание для Наличные:</label>
            <textarea class="form-control @error('payment_description_cash') is-invalid @enderror" id="payment_description_cash" name="payment_description_cash" required>{{ old('payment_description_cash', 'Оплата наличкой при получении.') }}</textarea>
        @error('payment_description_cash')
        <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
</div> --}}

{{-- <div class="form-group">
            <label for="payment_description_card">Описание для Карта:</label>
            <textarea class="form-control @error('payment_description_card') is-invalid @enderror" id="payment_description_card" name="payment_description_card" required>{{ old('payment_description_card', 'Оплата карточкой.') }}</textarea>
@error('payment_description_card')
<div class="invalid-feedback text-danger">{{ $message }}</div>
@enderror
</div> --}}

{{-- <div class="form-group">
            <label for="payment_description_online">Описание для Онлайн:</label>
            <textarea class="form-control @error('payment_description_online') is-invalid @enderror" id="payment_description_online" name="payment_description_online" required>{{ old('payment_description_online', 'Оплата через PayPal.') }}</textarea>
@error('payment_description_online')
<div class="invalid-feedback text-danger">{{ $message }}</div>
@enderror
</div> --}}

<!-- Поле Статус платежа -->
<div class="form-group">
    <label for="payment_status">Статус оплаты:</label>
    <select class="form-control @error('payment_method') is-invalid @enderror" id="payment_status" name="payment_status" required>
        <option value="pending" {{ old('payment_status') == 'pending' ? 'selected' : '' }}>В ожидании</option>
        <option value="completed" {{ old('payment_status') == 'completed' ? 'selected' : '' }}>Завершено</option>
        <option value="failed" {{ old('payment_status') == 'failed' ? 'selected' : '' }}>Неудачно</option>
    </select>
    @error('payment_status')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Поле Статус доставки -->
<div class="form-group">
    <label for="shipping_status">Статус доставки:</label>
    <select class="form-control @error('shipping_status') is-invalid @enderror" id="shipping_status" name="shipping_status" required>
        <option value="courier" {{ old('shipping_status') == 'courier' ? 'selected' : '' }}>Курьером</option>
        <option value="pickup" {{ old('shipping_status') == 'pickup' ? 'selected' : '' }}>Самовывоз</option>
        <option value="post" {{ old('shipping_status') == 'post' ? 'selected' : '' }}>Почтой</option>
    </select>
    @error('shipping_status')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Примечания к заказу -->
<div class="form-group">
    <label for="order_notes">Примечания к заказу</label>
    <textarea class="form-control @error('order_notes') is-invalid @enderror" id="order_notes" name="order_notes" rows="4">{{ old('order_notes') }}</textarea>
    @error('order_notes')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Дата создания заказа -->
<div class="form-group">
    <label for="order_date">Дата создания заказа:</label>
    <input class="form-control @error('order_date') is-invalid @enderror" id="order_date" name="order_date" type="datetime-local" value="{{ old('order_date') }}">
    @error('order_date')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Ожидаемая дата доставки -->
<div class="form-group">
    <label for="delivery_date">Ожидаемая дата доставки</label>
    <input class="form-control @error('delivery_date') is-invalid @enderror" id="delivery_date" name="delivery_date" type="datetime-local" value="{{ old('delivery_date') }}">
    @error('delivery_date')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Поля для выставления счета -->
<h5>Информация для выставления счета</h5>

<div class="form-group">
    <label for="first_name">Имя:</label>
    <input class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" type="text" value="{{ old('first_name') }}">
    @error('first_name')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="last_name">Фамилия:</label>
    <input class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" type="text" value="{{ old('last_name') }}">
    @error('last_name')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="email">Email:</label>
    <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}">
    @error('email')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="city">Город:</label>
    <input class="form-control @error('city') is-invalid @enderror" id="city" name="city" type="text" value="{{ old('city') }}">
    @error('city')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="country">Страна:</label>
    <input class="form-control @error('country') is-invalid @enderror" id="country" name="country" type="text" value="{{ old('country') }}">
    @error('country')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="zip_code">Почтовый индекс:</label>
    <input class="form-control @error('zip_code') is-invalid @enderror" id="zip_code" name="zip_code" type="text" value="{{ old('zip_code') }}">
    @error('zip_code')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="tel">Телефон:</label>
    <input class="form-control @error('tel') is-invalid @enderror" id="tel" name="tel" type="text" value="{{ old('tel') }}">
    @error('tel')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

{{-- Строка адреса для выставления счета --}}
<div class="form-group">
    <label for="address">Адрес:</label>
    <input class="form-control @error('address') is-invalid @enderror" id="address" name="address" type="text" value="{{ old('address', '') }}">
    @error('address')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>


<!-- Другой адрес доставки -->

<div class="form-group">
    <label for="dif_first_name">Имя 2:</label>
    <input class="form-control @error('dif_first_name') is-invalid @enderror" id="dif_first_name" name="dif_first_name" type="text" value="{{ old('dif_first_name') }}">
    @error('dif_first_name')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="dif_last_name">Фамилия 2:</label>
    <input class="form-control @error('dif_last_name') is-invalid @enderror" id="dif_last_name" name="dif_last_name" type="text" value="{{ old('dif_last_name') }}">
    @error('dif_last_name')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="dif_email">Email 2:</label>
    <input class="form-control @error('dif_email') is-invalid @enderror" id="dif_email" name="dif_email" type="email" value="{{ old('dif_email') }}">
    @error('dif_email')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="dif_address">Адрес 2:</label>
    <input class="form-control @error('dif_address') is-invalid @enderror" id="dif_address" name="dif_address" type="text" value="{{ old('dif_address') }}">
    @error('dif_address')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>


<div class="form-group">
    <label for="dif_city">Город 2:</label>
    <input class="form-control @error('dif_city') is-invalid @enderror" id="dif_city" name="dif_city" type="text" value="{{ old('dif_city') }}">
    @error('dif_city')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="dif_country">Страна 2:</label>
    <input class="form-control @error('dif_country') is-invalid @enderror" id="dif_country" name="dif_country" type="text" value="{{ old('dif_country') }}">
    @error('dif_country')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="dif_zip_code">Почтовый индекс 2:</label>
    <input class="form-control @error('dif_zip_code') is-invalid @enderror" id="dif_zip_code" name="dif_zip_code" type="text" value="{{ old('dif_zip_code') }}">
    @error('dif_zip_code')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="dif_tel">Телефон 2:</label>
    <input class="form-control @error('dif_tel') is-invalid @enderror" id="dif_tel" name="dif_tel" type="text" value="{{ old('dif_tel') }}">
    @error('dif_tel')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>


<button class="btn btn-primary" type="submit">Создать заказ</button>
<a class="btn btn-warning" href="{{ route('admin.orders.index') }}">Назад к списку заказов</a>
</form>
</div>


{{-- <script>
    // Функция для обновления общей стоимости и экономии
    function updateTotal() {
        let totalPrice = 0;
        let totalSavings = 0;
        let shippingPrice = parseFloat(document.getElementById('shipping_price').value) || 0; // Стоимость доставки

        // Для каждого выбранного товара
        document.querySelectorAll('.product-checkbox:checked').forEach(function(checkbox) {
            let productId = checkbox.value;
            // let quantityInput = document.getElementById('quantity_' + productId); // Количество товара
            // let quantity = parseInt(quantityInput.value) || 0; // Количество, по умолчанию 0 если не указано
            let price = parseFloat(checkbox.getAttribute('data-price')) || 0; // Цена товара
            let oldPrice = parseFloat(checkbox.getAttribute('data-old-price')) || price; // Старая цена товара

            // Добавляем стоимость товара в общую сумму
            totalPrice += price * quantity;

            // Если старая цена больше текущей, считаем экономию
            if (oldPrice > price) {
                totalSavings += (oldPrice - price) * quantity;
            }
        });

        // Добавляем стоимость доставки
        totalPrice += shippingPrice;

        // Обновляем поле с общей стоимостью
        document.getElementById('total_price').value = totalPrice.toFixed(2);

        // Обновляем поле с общей экономией
        document.getElementById('total_savings').value = totalSavings.toFixed(2);
    }

    // Инициализация при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        updateTotal(); // Обновляем при загрузке страницы
    });

    // Обновляем при изменении стоимости доставки
    document.getElementById('shipping_price').addEventListener('change', updateTotal);

    // Обновляем при изменении количества товаров
    document.querySelectorAll('.quantity').forEach(function(input) {
        input.addEventListener('input', updateTotal); // Используем 'input', чтобы обновить при изменении
    });

    // Обновляем при изменении чекбоксов товаров
    document.querySelectorAll('.product-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', updateTotal); // Используем 'change' для обновления при изменении
    });
</script>
 --}}


@endsection