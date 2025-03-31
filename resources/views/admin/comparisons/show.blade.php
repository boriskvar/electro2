@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Сравнение #{{ $comparison->id }}</h2>
    <p>Пользователь: {{ $comparison->user_id }}</p>

    <h3>Товары в сравнении</h3>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comparison->products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>
                    <form action="{{ route('admin.comparison_products.destroy', [$comparison, $product]) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h3>Добавить товар в сравнение</h3>
    <form action="{{ route('admin.comparison_products.store', $comparison) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="product_id">Выберите товар:</label>
            <select name="product_id" id="product_id" class="form-control">
                @foreach($allProducts as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-success mt-2">Добавить</button>
    </form>

    <a href="{{ route('admin.comparisons.index') }}" class="btn btn-primary">Назад</a>
</div>
@endsection