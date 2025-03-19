@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Создать новый платеж</h1>

    <form action="{{ route('admin.payments.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="order_number">Номер заказа</label>
            <select name="order_id" id="order_number" class="form-control">
                @foreach ($orders as $order)
                <option value="{{ $order->id }}">{{ $order->order_number }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="payment_method">Метод оплаты</label>
            <select name="payment_method" id="payment_method" class="form-control">
                <option value="credit_card">Кредитная карта</option>
                <option value="paypal">PayPal</option>
                <option value="bank_transfer">Банковский перевод</option>
                <option value="cash">Наличные</option>
            </select>
        </div>

        <div class="form-group">
            <label for="amount">Сумма</label>
            <input type="number" name="amount" id="amount" class="form-control" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="status">Статус</label>
            <select name="status" id="status" class="form-control">
                <option value="pending">В ожидании</option>
                <option value="completed">Завершено</option>
                <option value="failed">Неудачно</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Сохранить</button>
    </form>
</div>
@endsection
