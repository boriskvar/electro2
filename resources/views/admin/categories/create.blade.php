@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Добавить новую категорию</h1>

    <!-- Сообщения об ошибках -->
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Сообщение об успехе -->
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="name">Название категории:</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="slug">Slug:</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug') }}" required>
            @error('slug')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="parent_id">Родительская категория:</label>
            <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                <option value="">Нет родительской категории</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('parent_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('parent_id')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Для загрузки нового изображения -->
        <div class="form-group">
            <label for="image">Загрузить новое изображение (макс. 600x400 px):</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" onchange="previewImage(event)">
            @error('image')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror

            <!-- Контейнер для предварительного просмотра изображения -->
            <div id="image-preview" style="margin-top: 10px;">
                @if(old('image'))
                <img id="imagePreview" src="{{ old('image') }}" alt="Предварительный просмотр" style="max-width: 200px; max-height: 150px; display: block;">
                @else
                <img id="imagePreview" src="#" alt="Предварительный просмотр" style="max-width: 200px; max-height: 150px; display: none;">
                @endif
            </div>
        </div>

        <!-- Для выбора, активна ли категория -->
        <div class="form-group">
            <label for="active">Активная категория:</label>
            <select class="form-control @error('active') is-invalid @enderror" id="active" name="active">
                <option value="1" {{ old('active') == '1' ? 'selected' : '' }}>Да</option>
                <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>Нет</option>
            </select>
            @error('active')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Для ввода порядка отображения -->
        <div class="form-group">
            <label for="display_order">Порядок отображения:</label>
            <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', 1) }}">
            @error('display_order')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Для выбора категории с распродажей -->
        <div class="form-group">
            <label for="is_sale">Скидка (распродажа):</label>
            <select class="form-control @error('is_sale') is-invalid @enderror" id="is_sale" name="is_sale">
                <option value="1" {{ old('is_sale') == '1' ? 'selected' : '' }}>Да</option>
                <option value="0" {{ old('is_sale') == '0' ? 'selected' : '' }}>Нет</option>
            </select>
            @error('is_sale')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Создать категорию</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-warning">Назад к списку категорий</a>
    </form>
</div>

<!-- Скрипт для отображения изображения перед загрузкой -->
<script>
    function previewImage(event) {
        var file = event.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            var imagePreview = document.getElementById('imagePreview');
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block'; // Показываем изображение
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

@endsection