<!-- resources/views/wishlist/show.blade.php -->
@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Детали товара</h1>

    <div class="card">
        <div class="card-body">
            <h2 class="card-title">{{ $product->name }}</h2>
            <p class="card-text">Цена: ${{ $product->price }}</p>

            @if (!empty($product->images))
            @php
            $images = json_decode($product->images);
            @endphp
            <img src="{{ asset('storage/img/' . basename($images[0])) }}" alt="{{ $product->name }}" class="img-fluid" style="max-width: 100px;">
            @endif

            <p class="card-text">{{ $product->description ?? 'Описание отсутствует' }}</p>

            <a href="{{ route('wishlist.index') }}" class="btn btn-warning mt-3">Назад к списку желаемого</a>
        </div>
    </div>
</div>
@endsection
