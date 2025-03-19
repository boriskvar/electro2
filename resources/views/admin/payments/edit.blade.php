@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Редактировать Платеж</h1>

    <form action="{{ route('admin.payments.update', $payment->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="order_id" class="form-label">ID Заказа</label>
            <input type="number" name="order_id" id="order_id" class="form-control" value="{{ $payment->order_id }}" required>
        </div>
        <div class="mb-3">
            <label for="payment_method" class="form-label">Метод Оплаты</label>
            <input type="text" name="payment_method" id="payment_method" class="form-control" value="{{ $payment->payment_method }}" required>
        </div>
        <div class="mb-3">
            <label for="amount" class="form-label">Сумма</label>
            <input type="number" name="amount" id="amount" class="form-control" value="{{ $payment->amount }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <input type="text" name="status" id="status" class="form-control" value="{{ $payment->status }}" required>
        </div>
        <button type="submit" class="btn btn-warning">Обновить Платеж</button>
    </form>
</div>
@endsection
