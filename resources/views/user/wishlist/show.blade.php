@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>{{ $wishlist->product->name }}</h1>
    @if (!empty($wishlist->product->images))
    @php
    $images = json_decode($wishlist->product->images);
    @endphp
    <img src="{{ asset('storage/img/' . basename($images[0])) }}" alt="{{ $wishlist->product->name }}" class="img-fluid" style="max-width: 100px;">
    @endif
    <p>Цена: ${{ $wishlist->product->price }}</p>
    <p>Дата добавления: {{ $wishlist->created_at }}</p>
    <a href="{{ route('wishlist.index') }}" class="btn btn-primary">Вернуться к списку желаемого</a>
</div>
@endsection
