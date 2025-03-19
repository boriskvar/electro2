@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Информация о товаре</h1>

    <div class="order-item-details">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="image" style="font-weight: normal; margin-right: 10px;"></label>
            <div>
                @if ($orderItem->product && $orderItem->product->images)
                {{-- @php $images = json_decode($orderItem->product->images, true); @endphp --}}
                @php $images = $orderItem->product->images; @endphp
                @if (!empty($images) && isset($images[0]) && Storage::disk('public')->exists('img/' . basename($images[0])))
                <img src="{{ asset('storage/img/' . basename($images[0])) }}" alt="{{ $orderItem->product->name }}" class="img-fluid" style="max-width: 50px;">
                @else
                <span class="text-muted">Нет изображения</span>
                @endif
                @else
                <span class="text-muted">Нет изображения</span>
                @endif
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="product_name" style="font-weight: normal; margin-right: 10px;">Название:</label>
            <div style="background-color: rgb(162, 237, 204); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $orderItem->product->name }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="order_number" style="font-weight: normal; margin-right: 10px;">Номер заказа:</label>
            <div style="background-color: rgb(162, 237, 204); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $orderItem->order->order_number }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="quantity" style="font-weight: normal; margin-right: 10px;">Количество:</label>
            <div style="background-color: rgb(162, 237, 204); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $orderItem->quantity }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="price_x_quantity" style="font-weight: normal; margin-right: 10px;">Цена (x) кол-во:</label>
            <div style="background-color: rgb(162, 237, 204); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ number_format($orderItem->price_x_quantity, 2, '.', '') }} ₴
            </div>
        </div>


    </div>

    <a href="{{ route('admin.order-items.index', ['order' => $order->id]) }}" class="btn btn-warning">Назад</a>
</div>
@endsection