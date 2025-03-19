@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Редактировать категорию: {{ $category->name }}</h1>

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

    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Название категории:</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
            @error('name')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="slug">Slug:</label>
            <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->slug) }}" required>
            @error('slug')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
            @error('description')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="parent_id">Родительская категория:</label>
            <select class="form-control @error('parent_id') is-invalid @enderror" id="parent_id" name="parent_id">
                <option value="">Нет родительской категории</option>
                @foreach($categories as $cat)
                @if ($cat->id !== $category->id)
                <option value="{{ $cat->id }}" {{ $category->parent_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endif
                @endforeach
            </select>
            @error('parent_id')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Для отображения текущего изображения -->
        @if (!empty($category->image[0]))
        <div class="form-group">
            <label>Текущее изображение категории:</label>
            <div>
                <img src="{{ asset('storage/categories/' . $category->image) }}" alt="Изображение категории" class="img-thumbnail" style="max-width: 200px;">
            </div>
        </div>
        @else
        <p>Изображение не загружено.</p>
        @endif

        <!-- Для загрузки нового изображения -->
        <div class="form-group">
            <label for="image">Загрузить новое изображение:</label>
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
                <option value="1" {{ old('active', $category->active) == '1' ? 'selected' : '' }}>Да</option>
                <option value="0" {{ old('active', $category->active) == '0' ? 'selected' : '' }}>Нет</option>
            </select>
            @error('active')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Для ввода порядка отображения -->
        <div class="form-group">
            <label for="display_order">Порядок отображения:</label>
            <input type="number" class="form-control @error('display_order') is-invalid @enderror" id="display_order" name="display_order" value="{{ old('display_order', $category->display_order) }}">
            @error('display_order')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Для выбора категории с распродажей -->
        <div class="form-group">
            <label for="is_sale">Скидка (распродажа):</label>
            <select class="form-control @error('is_sale') is-invalid @enderror" id="is_sale" name="is_sale">
                <option value="1" {{ old('is_sale', $category->is_sale) == '1' ? 'selected' : '' }}>Да</option>
                <option value="0" {{ old('is_sale', $category->is_sale) == '0' ? 'selected' : '' }}>Нет</option>
            </select>
            @error('is_sale')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
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