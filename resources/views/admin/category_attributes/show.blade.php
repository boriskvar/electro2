@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Товар: {{ $product->name }}</h1>

    <!-- Название товара -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
        <label for="product_name" style="font-weight: normal; margin-right: 10px;">Название товара:</label>
        <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
            {{ $product->name }}
        </div>
    </div>

    <!-- Категория товара -->
    <div style="margin-bottom: 10px;">
        <label for="category" style="font-weight: normal;">Категория:</label>
        <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
            {{ $category->name }}
        </div>
    </div>

    <!-- Характеристики товара -->
    <h3>Характеристики товара</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Название характеристики</th>
                <th>Тип характеристики</th>
                <th>Значение</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryAttributes as $attribute)
            <tr>
                <td>{{ $attribute->attribute_name }}</td>
                <td>{{ $attribute->attribute_type }}</td>
                <td>{{ $attribute->value ?? 'Не указано' }}</td> <!-- Если значение отсутствует -->
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.products.index') }}" class="btn btn-warning">Назад к списку товаров</a>
</div>
@endsection