@extends('layouts.admin')

@section('content')

<div class="container">
    <h1>Редактировать характеристику: {{ $categoryAttribute->attribute_name }} для категории: {{ $category->name }}</h1>

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

    <form action="{{ route('admin.category-attributes.update', $categoryAttribute->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Поле Название характеристики категории -->
        <div class="form-group">
            <label for="attribute_name">Название характеристики:</label>
            <input class="form-control @error('attribute_name') is-invalid @enderror" type="text" id="attribute_name" name="attribute_name" value="{{ old('attribute_name', $categoryAttribute->attribute_name) }}" required>
            @error('attribute_name')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Тип характеристики -->
        <div class="form-group">
            <label for="attribute_type">Тип характеристики:</label>
            <select class="form-control @error('attribute_type') is-invalid @enderror" name="attribute_type" id="attribute_type" required>
                <option value="text" {{ old('attribute_type', $categoryAttribute->attribute_type) == 'text' ? 'selected' : '' }}>Текст</option>
                <option value="number" {{ old('attribute_type', $categoryAttribute->attribute_type) == 'number' ? 'selected' : '' }}>Число</option>
                <option value="boolean" {{ old('attribute_type', $categoryAttribute->attribute_type) == 'boolean' ? 'selected' : '' }}>Да/Нет</option>
                <option value="select" {{ old('attribute_type', $categoryAttribute->attribute_type) == 'select' ? 'selected' : '' }}>Выбор</option>
            </select>
            @error('attribute_type')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
        <a href="{{ route('admin.category-attributes.index') }}" class="btn btn-warning">Назад к списку характеристик</a>
    </form>
</div>

@endsection