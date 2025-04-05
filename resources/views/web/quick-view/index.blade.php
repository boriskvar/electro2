@extends('layouts.main')

@section('content')
<div class="container mt-4">
    <h2>Быстрый просмотр товара</h2>

    <div class="row mt-4">
        <div class="col-md-4">
            <img src="{{ $product->images[0] ? asset('storage/img/' . $product->images[0]) : asset('storage/img/default.png') }}" alt="{{ $product->name }}" class="img-fluid">
        </div>

        <div class="col-md-8">
            <h3>{{ $product->name }}</h3>
            <p><strong>Категория:</strong> {{ $product->category->name }}</p>
            <p><strong>Цена:</strong> ${{ number_format($product->price, 2) }}</p>
            @if($product->old_price)
            <p><del>Старая цена: ${{ number_format($product->old_price, 2) }}</del></p>
            @endif
            <p><strong>Рейтинг:</strong> {{ $product->rating ?? '—' }} ⭐</p>
        </div>
    </div>

    <hr>

    <h4>Характеристики</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Название характеристики</th>
                <th>Значение</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productData['attributes'] as $attributeName => $value)
            <tr>
                <td>{{ $attributeName }}</td>
                <td>{{ $value }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">Назад</a>
</div>
@endsection