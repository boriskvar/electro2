<!-- resources/views/wishlist/create.blade.php -->
@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Добавить товар в список желаемого</h1>

    <form action="{{ route('wishlist.store', $product->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="product">Выберите продукт</label>
            <select name="product_id" id="product" class="form-control">
                <option value="">-- Выберите продукт --</option>
                @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Добавить в список желаемого</button>
    </form>
</div>
@endsection
