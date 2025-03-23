@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Детали Платежа</h1>

    <table class="table">
        <tr>
            <th>ID</th>
            <td>{{ $payment->id }}</td>
        </tr>
        <tr>
            <th>Метод Оплаты</th>
            <td>{{ $payment->payment_method }}</td>
        </tr>
        <tr>
            <th>Сумма</th>
            <td>{{ $payment->amount }}</td>
        </tr>
        <tr>
            <th>Статус</th>
            <td>{{ $payment->status }}</td>
        </tr>
    </table>

    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">Назад к списку платежей</a>
</div>
@endsection
