@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Список товаров в заказе</h1>

    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <div class="mb-3">
        <a href="{{ route('admin.order-items.create', ['order' => $order->id]) }}" class="btn btn-primary">Добавить товар</a>
    </div>

    @if ($orderItems->isEmpty())
    <div class="alert alert-warning">Нет товаров в заказе.</div>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Номер заказа</th>
                <th>Название продукта</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Цена_x_кол-во</th>
                <th>Изображение</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orderItems as $orderItem)
            <tr>
                <td>{{ $orderItem->id }}</td>
                <td>{{ $orderItem->order->order_number }}</td>
                {{-- <td><a href="{{ route('admin.categories.show', $category->id) }}">{{ $category->name }}</a></td> --}}
                {{-- <td><a href="{{ route('admin.order-items.show', $orderItem->id) }}">{{ $orderItem->order->order_number }}</a></td> --}}

                {{-- <td>{{ $orderItem->product->name }}</td> --}}
                <td><a href="{{ route('admin.order-items.show', $orderItem->id) }}">{{ $orderItem->product->name }}</a></td>
                <td>{{ $orderItem->product->price }}</td>
                <td>{{ $orderItem->quantity }}</td>
                <td>{{ number_format($orderItem->price_x_quantity, 2) }} ₴</td>
                <td>
                    @if ($orderItem->product && $orderItem->product->images)
                    {{-- @php $images = json_decode($orderItem->product->images, true); @endphp --}}
                    @php $images =$orderItem->product->images; @endphp
                    @if (!empty($images) && isset($images[0]) && Storage::disk('public')->exists('img/' . basename($images[0])))
                    <img src="{{ asset('storage/img/' . basename($images[0])) }}" alt="{{ $orderItem->product->name }}" class="img-fluid" style="max-width: 50px;">
                    @else
                    <span class="text-muted">Нет изображения</span>
                    @endif
                    @else
                    <span class="text-muted">Нет изображения</span>
                    @endif
                </td>
                <td>
                    {{-- <a href="{{ route('admin.order-items.show', $orderItem->id) }}" class="btn btn-info btn-sm">Посмотреть</a> --}}
                    <a href="{{ route('admin.order-items.edit', $orderItem->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                    <form action="{{ route('admin.order-items.destroy', $orderItem->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Вы уверены?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection