@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Редактирование количества и привязок</h1>
    <form action="{{ route('admin.order-items.update', $orderItem->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="images">{{-- Изображение: --}}</label>
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

        <div class="form-group">
            <label for="order_id">Выберите заказ:</label>
            <select class="form-control" id="order_id" name="order_id" required>
                <option value="">Выберите заказ</option>
                @foreach ($orders as $order)
                <option value="{{ $order->id }}" {{ $orderItem->order_id == $order->id ? 'selected' : '' }}>
                    {{ $order->order_number }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="product_id">Товар:</label>
            <select class="form-control" id="product_id" name="product_id" required>
                <option value="">Выберите продукт</option>
                @foreach ($products as $product)
                <option value="{{ $product->id }}" {{ $orderItem->product_id == $product->id ? 'selected' : '' }}>
                    {{ $product->name }} (Цена: {{ $product->price }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Количество:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $orderItem->quantity }}" required>
        </div>


        {{-- <div class="form-group">
        <label for="price_x_quantity">Цена (х) кол-во.:</label>
        <input type="number"
               class="form-control"
               id="price_x_quantity"
               name="price_x_quantity"
               value="{{ $orderItem->price_x_quantity }}"
        readonly>
</div> --}}



<button type="submit" class="btn btn-primary">Сохранить</button>

<a href="{{ route('admin.order-items.index', ['order' => $order->id]) }}" class="btn btn-warning">Назад</a>
</form>
</div>
@endsection