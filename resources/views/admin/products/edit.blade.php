@extends('layouts.admin')

@section('content')

<div class="container">
    <h1>Редактировать товар: {{ $product->name }}</h1>

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

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Поле Название товара -->
        <div class="form-group">
            <label for="name">Название товара:</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}" required>
            @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Категория -->
        <div class="form-group">
            <label for="category_id">Категория:</label>
            <select class="form-control" id="category_id" name="category_id" required>
                <option value="">Выберите категорию</option>
                @if ($categories->isNotEmpty())
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>
                    {{ $category->name }}</option>
                @endforeach
                @else
                <option value="">Нет доступных категорий</option>
                @endif
            </select>
        </div>

        <!-- Поле Slug -->
        <div class="form-group">
            <label for="slug">Slug (уникальный идентификатор для SEO):</label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ $product->slug }}">
            @error('slug')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Описание -->
        <div class="form-group">
            <label for="description">Описание:</label>
            <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
            @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Детальная информация -->
        <div class="form-group">
            <label for="details">Детальная информация:</label>
            <textarea class="form-control" id="details" name="details" rows="4">{{ old('details', $product->details) }}</textarea>
            @error('details')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Цена -->
        <div class="form-group">
            <label for="price">Цена:</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}">
            @error('price')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Старая цена -->
        <div class="form-group">
            <label for="old_price">Старая цена:</label>
            <input type="number" class="form-control" id="old_price" name="old_price" value="{{ $product->old_price }}">
            @error('old_price')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле В наличии -->
        <div class="form-group">
            <label for="in_stock">В наличии:</label>
            <select class="form-control" id="in_stock" name="in_stock" required>
                <option value="1" {{ $product->in_stock ? 'selected' : '' }}>Да</option>
                <option value="0" {{ !$product->in_stock ? 'selected' : '' }}>Нет</option>
            </select>
        </div>

        <!-- Поле Загрузить изображения -->
        <div class="form-group">
            <label for="images">Загрузить изображения:</label>
            <input type="file" class="form-control @error('images') is-invalid @enderror" id="images" name="images[]" multiple onchange="previewImages()">
            @error('images')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Для диагностики -->
        {{-- <pre>
    @dd($product->images)
  </pre> --}}

        <!-- предварительный просмотр изображений -->
        <div id="image-preview" class="mb-3">
            @if (is_array($product->images) && count($product->images) > 0)
            @foreach ($product->images as $image)
            @if (is_string($image))
            <!-- Убедитесь, что это строка -->
            <img src="{{ asset('storage/img/' . $image) }}" class="img-thumbnail" style="max-width: 100px; max-height: 100px;">
            @else
            <!-- Логирование случая, когда в массиве не строка -->
            <p>Ошибка: Неверный формат изображения.</p>
            @endif
            @endforeach
            @endif
        </div>





        <!-- Поле Цвет -->
        <div class="form-group">
            <label for="colors">Цвет:</label>
            {{-- <input type="text" class="form-control @error('colors') is-invalid @enderror" name="colors[]" value="{{ old('colors', $product->colors ? json_decode($product->colors)[0] : '') }}" placeholder="Введите цвет"> --}}
            <input type="text" class="form-control @error('colors') is-invalid @enderror" name="colors[]" value="{{ old('colors', $product->colors[0] ?? '') }}" placeholder="Введите цвет">

            @error('colors')
            <div class="invalid-feedback" style="color: red; font-weight: bold; font-size: 1.1em; text-transform: uppercase;">
                {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Поле Размер -->
        <div class="form-group">
            <label for="sizes">Размер:</label>
            {{-- <input type="text" class="form-control @error('sizes') is-invalid @enderror" name="sizes[]" value="{{ old('sizes', $product->sizes ? json_decode($product->sizes)[0] : '') }}" placeholder="Введите размер"> --}}
            <input type="text" class="form-control @error('sizes') is-invalid @enderror" name="sizes[]" value="{{ old('sizes', $product->sizes[0] ?? '') }}" placeholder="Введите размер">

            @error('sizes')
            <div class="invalid-feedback" style="color: red; font-weight: bold; font-size: 1.1em; text-transform: uppercase;">
                {{ $message }}
            </div>
            @enderror
        </div>

        <!-- Поле Количество на складе -->
        <div class="form-group">
            <label for="stock_quantity">Количество на складе:</label>
            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" value="{{ $product->stock_quantity }}">
            @error('stock_quantity')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Бренд -->
        <div class="form-group">
            <label for="brand_id">Бренд (необязательно):</label>
            <select class="form-control" id="brand_id" name="brand_id">
                <option value="">Выберите бренд (необязательно)</option>
                @if ($brands->isNotEmpty())
                @foreach ($brands as $brand)
                <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>
                    {{ $brand->name }}</option>
                @endforeach
                @else
                <option value="">Нет доступных брендов</option>
                @endif
            </select>
        </div>

        <!-- Поле Меню -->
        <div class="form-group">
            <label for="menu_id">Меню (необязательно):</label>
            <select class="form-control" id="menu_id" name="menu_id">
                <option value="">Выберите меню (необязательно)</option>
                @if ($menus->isNotEmpty())
                @foreach ($menus as $menu)
                <option value="{{ $menu->id }}" {{ $menu->id == $product->menu_id ? 'selected' : '' }}>
                    {{ $menu->name }}</option>
                @endforeach
                @else
                <option value="">Нет доступных меню</option>
                @endif
            </select>
        </div>


        <!-- Поле рейтинг -->
        <div class="form-group">
            <label for="rating">Рейтинг:</label>
            <input type="number" class="form-control" id="rating" name="rating" step="0.1" min="0" max="5" value="{{ $product->rating }}">
            @error('rating')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Количество отзывов -->
        <div class="form-group">
            <label for="reviews_count">Количество отзывов:</label>
            <input type="number" class="form-control" id="reviews_count" name="reviews_count" min="0" value="{{ $product->reviews_count }}">
            @error('reviews_count')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Количество просмотров -->
        <div class="form-group">
            <label for="views_count">Количество просмотров:</label>
            <input type="number" class="form-control" id="views_count" name="views_count" min="0" value="{{ $product->views_count }}">
            @error('views_count')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Поле Скидка в процентах -->
        {{-- <div class="form-group">
            <label for="discount_percentage">Скидка (%):</label>
            <input type="number" class="form-control" id="discount_percentage" name="discount_percentage" step="0.01" min="0" max="100" placeholder="Введите процент скидки" value="{{ $product->discount_percentage }}">
        @error('discount_percentage')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
</div> --}}

<!-- Поле Позиция -->
<div class="form-group">
    <label for="position">Позиция:</label>
    <input type="number" class="form-control" id="position" name="position" value="{{ $product->position }}">
    @error('position')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<!-- Поле для "Is Top Selling" -->
<div class="form-group">
    <label for="is_top_selling">Топ продаж:</label>
    <select class="form-control" id="is_top_selling" name="is_top_selling" required>
        <option value="1" {{ $product->is_top_selling ? 'selected' : '' }}>Да</option>
        <option value="0" {{ !$product->is_top_selling ? 'selected' : '' }}>Нет</option>
    </select>
</div>

<!-- Поле для "Is New" -->
<div class="form-group">
    <label for="is_new">Новый товар:</label>
    <select class="form-control" id="is_new" name="is_new" required>
        <option value="1" {{ $product->is_new ? 'selected' : '' }}>Да</option>
        <option value="0" {{ !$product->is_new ? 'selected' : '' }}>Нет</option>
    </select>
</div>

<button type="submit" class="btn btn-primary">Сохранить изменения</button>
<a href="{{ route('admin.products.index') }}" class="btn btn-warning">Назад к списку товаров</a>
</form>
</div>


<!-- Сценарий для предпросмотра изображений -->
<script>
    function previewImages() {
        const preview = document.getElementById('image-preview');
        preview.innerHTML = ''; // Очистить предыдущее отображение

        const files = document.getElementById('images').files;
        if (files) {
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('img-thumbnail');
                    img.style.maxWidth = '100px';
                    img.style.maxHeight = '100px';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        }
    }
</script>

@endsection