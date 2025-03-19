@extends('layouts.admin')

@section('content')

<div class="container">
    <h1>Создать новый товар</h1>

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

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Поле Название товара -->
        <div class="form-group">
            <label for="name">Название товара:</label>
            <input class="form-control @error('name') is-invalid @enderror" type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Категория -->
        <div class="form-group">
            <label for="category_id">Категория:</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Выберите категорию</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Поле Меню -->
        <div class="form-group">
            <label for="menu_id">Меню:</label>
            <select class="form-control" id="menu_id" name="menu_id">
                <option value="">Выберите меню</option>
                @foreach ($menus as $menu)
                <option value="{{ $menu->id }}" {{ old('menu_id') == $menu->id ? 'selected' : '' }}>
                    {{ $menu->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Поле Slug -->
        <div class="form-group">
            <label for="slug">Slug (уникальный идентификатор для SEO):</label>
            <input class="form-control @error('slug') is-invalid @enderror" type="text" id="slug" name="slug" value="{{ old('slug') }}">
            @error('slug')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Описание -->
        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
            @error('description')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Детальная информация -->
        <div class="form-group">
            <label for="details">Детальная информация:</label>
            <textarea class="form-control" id="details" name="details" rows="4">{{ old('details') }}</textarea>
            @error('details')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Цена -->
        <div class="form-group">
            <label for="price">Цена:</label>
            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}">
            @error('price')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Старая цена -->
        <div class="form-group">
            <label for="old_price">Старая цена:</label>
            <input type="number" class="form-control @error('old_price') is-invalid @enderror" id="old_price" name="old_price" value="{{ old('old_price') }}">
            @error('old_price')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле В наличии -->
        <div class="form-group">
            <label for="in_stock">В наличии:</label>
            <select class="form-control" id="in_stock" name="in_stock" required>
                <option value="1" {{ old('in_stock') == 1 ? 'selected' : '' }}>Да</option>
                <option value="0" {{ old('in_stock') == 0 ? 'selected' : '' }}>Нет</option>
            </select>
        </div>

        <!-- Поле Загрузить изображения -->
        <div class="form-group">
            <label for="images">Загрузить изображения:</label>
            <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple onchange="previewImages()"><!-- Вызываем функцию previewImages() для предпросмотра -->
            @error('images')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>
        <!-- Контейнер для предпросмотра изображений -->
        <div id="preview" class="mt-3"></div>


        <!-- Поле Цвет -->
        <div class="form-group">
            <label for="colors">Цвет:</label>
            <input type="text" class="form-control @error('colors') is-invalid @enderror" name="colors" value="{{ is_array(old('colors')) ? implode(',', old('colors')) : old('colors') }}" placeholder="Введите цвета через запятую">
            @error('colors')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Размер -->
        <div class="form-group">
            <label for="sizes">Размер:</label>
            <input type="text" class="form-control @error('sizes') is-invalid @enderror" name="sizes" value="{{ is_array(old('sizes')) ? implode(',', old('sizes')) : old('sizes') }}" placeholder="Введите размеры через запятую">
            @error('sizes')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Количество на складе -->
        <div class="form-group">
            <label for="stock_quantity">Количество на складе:</label>
            <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity" name="stock_quantity" min="0" value="{{ old('stock_quantity') }}">
            @error('stock_quantity')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Бренд -->
        <div class="form-group">
            <label for="brand_id">Бренд (необязательно):</label>
            <select class="form-control" id="brand_id" name="brand_id">
                <option value="">Выберите бренд (необязательно)</option>
                @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                    {{ $brand->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Поле рейтинг -->
        <div class="form-group">
            <label for="rating">Рейтинг:</label>
            <input type="number" class="form-control @error('rating') is-invalid @enderror" id="rating" name="rating" value="{{ old('rating') }}" step="0.1" min="0" max="5">
            @error('rating')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Количество отзывов -->
        <div class="form-group">
            <label for="reviews_count">Количество отзывов:</label>
            <input type="number" class="form-control @error('reviews_count') is-invalid @enderror" id="reviews_count" name="reviews_count" value="{{ old('reviews_count') }}" min="0">
            @error('reviews_count')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Количество просмотров -->
        <div class="form-group">
            <label for="views_count">Количество просмотров:</label>
            <input type="number" class="form-control @error('views_count') is-invalid @enderror" id="views_count" name="views_count" value="{{ old('views_count') }}" min="0">
            @error('views_count')
            <div class="invalid-feedback text-danger">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Скидка в процентах -->
        {{-- <div class="form-group">
            <label for="discount_percentage">Скидка (%):</label>
            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" value="{{ old('discount_percentage') }}" min="0" max="100">
        @error('discount_percentage')
        <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
</div> --}}

<!-- Поле Позиция -->
<div class="form-group">
    <label for="position">Позиция:</label>
    <input type="number" class="form-control @error('position') is-invalid @enderror" id="position" name="position" value="{{ old('position') }}" min="0">
    @error('position')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Поле для "Is Top Selling" -->
<div class="form-group">
    <label for="is_top_selling">Топ продаж:</label>
    <select class="form-control @error('is_top_selling') is-invalid @enderror" id="is_top_selling" name="is_top_selling" required>
        <option value="1" {{ old('is_top_selling') == 1 ? 'selected' : '' }}>Да</option>
        <option value="0" {{ old('is_top_selling') == 0 ? 'selected' : '' }}>Нет</option>
    </select>
    @error('is_top_selling')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<!-- Поле для "Is New" -->
<div class="form-group">
    <label for="is_new">Новый товар:</label>
    <select class="form-control @error('is_new') is-invalid @enderror" id="is_new" name="is_new" required>
        <option value="1" {{ old('is_new') == 1 ? 'selected' : '' }}>Да</option>
        <option value="0" {{ old('is_new') == 0 ? 'selected' : '' }}>Нет</option>
    </select>
    @error('is_new')
    <div class="invalid-feedback text-danger">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">Создать товар</button>
<a href="{{ route('admin.products.index') }}" class="btn btn-warning">Назад к списку товаров</a>
</form>
</div>

<script>
    function previewImages() {
        const preview = document.getElementById('preview');
        preview.innerHTML = ''; // Очищаем контейнер перед добавлением новых изображений
        const files = document.getElementById('images').files; // Получаем все выбранные файлы

        // Для каждого выбранного изображения создаем элемент img и добавляем его в контейнер
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader(); // Создаем новый FileReader для чтения файла

            reader.onload = function(e) {
                const img = document.createElement('img'); // Создаем элемент img
                img.src = e.target.result; // Устанавливаем путь к изображению (которое мы только что считали)
                img.classList.add('img-thumbnail'); // Добавляем класс для стилизации
                img.style.maxWidth = '100px'; // Ограничиваем размер изображения
                img.style.maxHeight = '100px'; // Ограничиваем размер изображения
                img.style.margin = '5px'; // Добавляем отступы между изображениями
                preview.appendChild(img); // Добавляем изображение в контейнер для превью
            }
            reader.readAsDataURL(file); // Читаем файл как Data URL
        }
    }
</script>

@endsection