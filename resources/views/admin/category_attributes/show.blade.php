@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Характеристика: {{ $categoryAttribute->attribute_name }}</h1>

    <!-- Название категории -->
    @if ($category)
    <p><strong>Категория:</strong> {{ $category->name }}</p>
    @else
    <p><strong>Категория не указана</strong></p>
    @endif

    <!-- Название товара -->
    {{-- @if ($product)
    <p><strong>Название товара:</strong> {{ $product->name }}</p>
    @else
    <p><strong>Товар не найден</strong></p>
    @endif --}}

    <!-- Значение характеристики -->
    <p><strong>Название характеристики:</strong> {{ $categoryAttribute->attribute_name }}</p>
    <p><strong>Тип характеристики:</strong> {{ $categoryAttribute->attribute_type }}</p>
    {{-- <p><strong>Значение:</strong> {{ $categoryAttribute->value }}</p> --}}

    <a href="{{ route('admin.category-attributes.index') }}" class="btn btn-warning">Назад к списку характеристик</a>
</div>
@endsection