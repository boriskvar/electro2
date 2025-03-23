{{-- resources/views/admin/checkout/list.blade.php --}}

@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Список всех оформлений заказов</h1>

    <div class="mb-3">
        <a href="{{ route('admin.checkout.create') }}" class="btn btn-primary">Создать новое оформление заказа</a>
    </div>

    @if ($orders->count())
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя пользователя</th>
                <th>Дата заказа</th>
                <th>Общая сумма</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user ? $order->user->name : 'Не указано' }}</td>
                <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                <td>{{ number_format($order->total_price, 2, ',', ' ') }} руб.</td>
                <td>{{ $order->status }}</td>
                <td>
                    <a href="{{ route('admin.checkout.show', $order->id) }}" class="btn btn-info">Просмотр</a>
                    <a href="{{ route('admin.checkout.edit', $order->id) }}" class="btn btn-warning">Редактировать</a>
                    <form action="{{ route('admin.checkout.destroy', $order->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот заказ?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orders->links() }} <!-- Пагинация -->
    @else
    <p>Нет заказов для отображения.</p>
    @endif
</div>
<a href="{{ route('admin.checkout.index') }}" class="btn btn-primary">Назад к оформлению заказа</a>
@endsection
