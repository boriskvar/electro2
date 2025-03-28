@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Значение характеристики: {{ $categoryAttribute->attribute_name }}</h1>

    <!-- Название категории -->
    @if ($category)
    <p><strong>Категория:</strong> {{ $category->name }}</p>
    @else
    <p><strong>Категория не указана</strong></p>
    @endif

    <!-- Значение характеристики -->
    <p><strong>Значение:</strong> {{ $attribute->value }}</p>

    <p><strong>Характеристика:</strong> {{ $categoryAttribute->attribute_name }}</p>
    <p><strong>Тип характеристики:</strong> {{ $categoryAttribute->attribute_type }}</p>

    <a href="{{ route('admin.product-attributes.index') }}" class="btn btn-warning">Назад к списку характеристик</a>
</div>
@endsection