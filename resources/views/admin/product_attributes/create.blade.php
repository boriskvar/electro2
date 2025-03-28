@extends('layouts.admin')

@section('content')

<div class="container">
    <h1>Создать характеристику категории</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.product-attributes.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="product_id">Товар:</label>
            <select name="product_id" id="product_id" required>
                @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="category_attribute_id">Характеристика:</label>
            <select name="category_attribute_id" id="category_attribute_id" required>
                @foreach ($categoryAttributes as $attribute)
                <option value="{{ $attribute->id }}">{{ $attribute->attribute_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="value">Значение:</label>
            <input type="text" name="value" id="value" required>
        </div>

        <button type="submit">Сохранить</button>
    </form>

</div>

@endsection