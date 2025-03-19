@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Список заказов</h1>

    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary mt-3 mb-3">Создать заказ</a>

    <!-- Форма фильтрации -->
    <form action="{{ route('admin.orders.index') }}" method="GET" class="form-inline mb-3">
        <div class="form-group mr-2">
            <label for="status">Статус:</label>
            <select name="status" id="status" class="form-control ml-2">
                <option value="">Все</option>
                {{-- <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>В процессе выполнения</option> --}}
                <option value="pending" {{ isset($status) && $status == 'pending' ? 'selected' : '' }}>В процессе выполнения
                </option>
                {{-- <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Завершён</option> --}}
                <option value="completed" {{ isset($status) && $status == 'completed' ? 'selected' : '' }}>Завершён</option>
                {{-- <option value="canceled" {{ $status == 'canceled' ? 'selected' : '' }}>Отменён</option> --}}
                <option value="canceled" {{ isset($status) && $status == 'canceled' ? 'selected' : '' }}>Отменён</option>
            </select>
        </div>
        <button type="submit" class="btn  btn-sm btn-info">Фильтр</button>
    </form>

    <!-- Таблица заказов -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Номер заказа</th>
                <th>Количество</th>
                {{-- <th>Пользователь</th> --}}
                {{-- <th>Статус</th> --}}
                <th>Цена доставки</th>
                <th>Общая стоимость</th>
                {{-- <th>Дата создания</th> --}}
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td><a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_number }}</a>
                </td>
                {{-- <td>{{ $order->user->name }}</td> --}}
                @foreach ($order->orderItems as $orderItem)
                <td>{{ $orderItem->quantity }}</td> <!-- Количество товара для каждого элемента заказа -->
                @endforeach
                {{-- <td>
              {{ $order->status == 'pending' ? 'В процессе выполнения' : ($order->status == 'completed' ? 'Завершён' : 'Отменён') }}
                </td> --}}
                <td>{{ number_format($order->shipping_price, 2) }} грн.</td>
                <td>{{ number_format($order->total_price, 2) }} грн.</td>
                {{-- <td>{{ $order->created_at->format('d.m.Y H:i') }}</td> --}}
                <td>
                    {{-- <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">Просмотр</a> --}}
                    <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn  btn-sm btn-primary">Редактировать</a>
                    <!-- Добавьте кнопку "Удалить" -->
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn  btn-sm btn-danger" onclick="return confirm('Вы уверены, что хотите удалить этот заказ?');">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orders->links() }} <!-- Пагинация -->
</div>
@endsection