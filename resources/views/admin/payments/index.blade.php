@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Список Платежей</h1>
    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">Добавить Платеж</a>

    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Метод Оплаты</th>
                <th>Сумма</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->payment_method }}</td>
                <td>{{ $payment->amount }}</td>
                <td>{{ $payment->status }}</td>
                <td>
                    <a href="{{ route('admin.payments.show', $payment->id) }}" class="btn btn-info">Посмотреть</a>
                    <a href="{{ route('admin.payments.edit', $payment->id) }}" class="btn btn-warning">Редактировать</a>
                    <form action="{{ route('admin.payments.destroy', $payment->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
