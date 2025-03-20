<!-- resources/views/wishlist/edit.blade.php -->
@extends('layouts.layout')

@section('content')
<div class="container">
    <h1>Выбрать товар в списке желаемого</h1>

    <form action="{{ route('wishlist.update', $wishlist->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="product">Выберите продукт</label>
            <select name="product_id" id="product" class="form-control">
                @foreach ($products as $product)
                <option value="{{ $product->id }}" {{ $wishlist->product_id == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Сохранить изменения</button>
        <a href="{{ route('wishlist.index') }}" class="btn btn-warning mt-3">Отмена</a>
    </form>
</div>
@endsection
