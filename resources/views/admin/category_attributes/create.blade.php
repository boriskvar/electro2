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

    <form action="{{ route('admin.category-attributes.store') }}" method="POST">
        @csrf

        <!-- Поле для выбора категории -->
        <div class="form-group">
            <label for="category_id">Категория:</label>
            <select class="form-control @error('category_id') is-invalid @enderror" name="category_id" id="category_id" required>
                <option value="">Выберите категорию</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
                @endforeach
            </select>
            @error('category_id')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Название характеристики категории -->
        <div class="form-group">
            <label for="attribute_name">Название характеристики:</label>
            <input class="form-control @error('attribute_name') is-invalid @enderror" type="text" id="attribute_name" name="attribute_name" value="{{ old('attribute_name') }}" required>
            @error('attribute_name')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Тип характеристики -->
        <div class="form-group">
            <label for="attribute_type">Тип характеристики:</label>
            <select class="form-control @error('attribute_type') is-invalid @enderror" name="attribute_type" id="attribute_type" required>
                <option value="">Выберите тип</option>
                <option value="text" {{ old('attribute_type') == 'text' ? 'selected' : '' }}>Текст</option>
                <option value="number" {{ old('attribute_type') == 'number' ? 'selected' : '' }}>Число</option>
                <option value="boolean" {{ old('attribute_type') == 'boolean' ? 'selected' : '' }}>Да/Нет</option>
                <option value="select" {{ old('attribute_type') == 'select' ? 'selected' : '' }}>Выбор</option>
            </select>
            @error('attribute_type')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Создать характеристику</button>
        <a href="{{ route('admin.category-attributes.index') }}" class="btn btn-warning">Назад к списку характеристик</a>
    </form>
</div>

@endsection