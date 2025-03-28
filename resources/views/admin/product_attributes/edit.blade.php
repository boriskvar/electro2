@extends('layouts.admin')

@section('content')

<div class="container">
    <h1>Редактировать значение характеристики для товара: {{ $productCategoryAttribute->product->name }} ({{ $productCategoryAttribute->categoryAttribute->attribute_name }})</h1>

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

    <form action="{{ route('admin.product-attributes.update', $productCategoryAttribute->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Поле для товара -->
        <div class="form-group">
            <label for="product_id">Товар:</label>
            <select name="product_id" id="product_id" required>
                @foreach ($products as $product)
                <option value="{{ $product->id }}" {{ $productCategoryAttribute->product_id == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Поле для характеристики -->
        <div class="form-group">
            <label for="category_attribute_id">Характеристика:</label>
            <select name="category_attribute_id" id="category_attribute_id" required>
                @foreach ($categoryAttributes as $attribute)
                <option value="{{ $attribute->id }}" {{ $productCategoryAttribute->category_attribute_id == $attribute->id ? 'selected' : '' }}>
                    {{ $attribute->attribute_name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Поле для значения характеристики -->
        <div class="form-group">
            <label for="value">Значение:</label>
            <input class="form-control @error('value') is-invalid @enderror" type="text" id="value" name="value" value="{{ old('value', $productCategoryAttribute->value) }}" required>
            @error('value')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <a href="{{ route('admin.product-attributes.index') }}" class="btn btn-warning">Назад к списку характеристик</a>
    </form>

</div>

@endsection