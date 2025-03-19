@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>{{ $product->name }}</h1>

    <!-- Имя товара -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
        <label for="product_name" style="font-weight: normal; margin-right: 10px;">Название товара:</label>
        <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
            {{ $product->name }}
        </div>
    </div>

    <!-- Изображение товара -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
        <label for="product_image" style="font-weight: normal; margin-right: 10px;">Изображение:</label>
        @if ($product->images && isset($product->images[0]) && Storage::disk('public')->exists('img/' . $product->images[0]))
        <img src="{{ asset('storage/img/' . $product->images[0]) }}" alt="{{ $product->name }}" class="img-fluid" style="max-width: 150px;">
        @else
        <p>Изображение недоступно</p>
        @endif
    </div>


    <!-- Основная информация -->
    <div style="margin-top: 20px;">

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="category" style="font-weight: normal; margin-right: 10px;">Категория:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->category ? $product->category->name : 'Категория не указана' }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="slug" style="font-weight: normal; margin-right: 10px;">Slug:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->slug }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="description" style="font-weight: normal; margin-right: 10px;">Описание:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->description }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="details" style="font-weight: normal; margin-right: 10px;">Детальная информация:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->details }}
            </div>
        </div>
    </div>

    <!-- Цены -->
    <div style="margin-top: 20px;">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="price" style="font-weight: normal; margin-right: 10px;">Цена:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                ${{ number_format($product->price, 2) }}
            </div>
        </div>

        @if ($product->old_price)
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="old_price" style="font-weight: normal; margin-right: 10px;">Старая цена:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                <s>${{ number_format($product->old_price, 2) }}</s>
            </div>
        </div>
        @endif
    </div>

    <!-- Наличие -->
    <div style="margin-top: 20px;">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="in_stock" style="font-weight: normal; margin-right: 10px;">В наличии:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->in_stock ? 'Да' : 'Нет' }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="colors" style="font-weight: normal; margin-right: 10px;">Цвета:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->colors ? implode(', ', $product->colors) : 'Нет' }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="sizes" style="font-weight: normal; margin-right: 10px;">Размеры:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->sizes ? implode(', ', $product->sizes) : 'Нет' }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="stock_quantity" style="font-weight: normal; margin-right: 10px;">Количество на складе:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->stock_quantity }}
            </div>
        </div>
    </div>

    <!-- Метаданные -->
    <div style="margin-top: 20px;">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="brand" style="font-weight: normal; margin-right: 10px;">Бренд:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->brand_id ? $product->brand->name : 'Не указано' }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="menu" style="font-weight: normal; margin-right: 10px;">Меню:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->menu_id ? $product->menu->name : 'Не указано' }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="rating" style="font-weight: normal; margin-right: 10px;">Рейтинг:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->rating }} / 5
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="reviews_count" style="font-weight: normal; margin-right: 10px;">Количество отзывов:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->reviews_count }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="views_count" style="font-weight: normal; margin-right: 10px;">Количество просмотров:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->views_count }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="discount_percentage" style="font-weight: normal; margin-right: 10px;">Скидка (%):</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->discount_percentage ? $product->discount_percentage . '%' : 'Нет' }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="position" style="font-weight: normal; margin-right: 10px;">Позиция:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->position }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="is_top_selling" style="font-weight: normal; margin-right: 10px;">Топ продаж:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->is_top_selling ? 'Да' : 'Нет' }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="is_new" style="font-weight: normal; margin-right: 10px;">Новый товар:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $product->is_new ? 'Да' : 'Нет' }}
            </div>
        </div>
    </div>

    <!-- Добавление в корзину -->
    <form action="{{ route('admin.cart.add') }}" method="POST" class="form-inline">
        @csrf

        <!-- Скрытое поле для передачи ID товара -->
        <input type="hidden" name="product_id" value="{{ $product->id }}">

        <div class="form-group mx-sm-3">
            <label for="quantity" class="sr-only">Количество:</label>
            <input type="number" name="quantity" id="quantity" class="form-control" placeholder="Количество" value="1" min="1" max="{{ $product->stock_quantity }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Добавить в корзину</button>
    </form>

    <h3>Количество в корзине</h3>
    <p>{{ $currentQuantity > 0 ? $currentQuantity : 'Товар не добавлен в корзину' }}</p>

    <a href="{{ route('admin.cart.index') }}" class="btn btn-success">Перейти в корзину</a>

    <h3>Отзывы</h3>
    @if ($product->reviews->isEmpty())
    <p>Нет отзывов для этого продукта.</p>
    @else
    <ul>
        @foreach ($product->reviews as $review)
        <div class="review">
            <strong>{{ $review->author_name }}</strong> ({{ $review->rating }} звёзд)
            <p>{{ $review->review_text }}</p>
            <small>Отзыв оставлен {{ $review->created_at->diffForHumans() }}</small>

            @if (auth()->id() === $review->user_id)
            <div>
                <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-warning">Редактировать</a>
                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </ul>
    @endif

    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Назад к списку товаров</a>

</div>
@endsection