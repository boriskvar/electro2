@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Результаты поиска для "{{ $query }}"</h1>

    @if($products->isEmpty())
    <p>Товары не найдены.</p>
    @else
    <table class="table">
        <thead>
            <tr>
                <th>Изображение</th>
                <th>Название товара</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            {{-- {{ dd($products) }} --}}
            @foreach($products as $product)
            <tr>
                <td>
                    @php
                    $images = json_decode($product->images); // Декодируем JSON-строку в массив
                    @endphp

                    @if (!empty($images))
                    <img src="{{ asset('storage/img/' . $images[0]) }}" alt="{{ $product->name }}" class="img-fluid" style="max-width: 50px;">
                    @else
                    <img src="{{ asset('storage/img/default.png') }}" alt="default image" class="img-fluid" style="max-width: 50px;"> <!-- Для случая, если изображений нет -->
                    @endif
                </td>
                <td>{{ $product->name }}</td>
                <td>${{ $product->price }}</td>
                <td>
                    <a href="{{ route('admin.cart.add', $product->id) }}" class="btn btn-primary">Добавить в корзину</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection
