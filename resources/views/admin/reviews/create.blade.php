@extends('layouts.admin')

@section('content')
<div class="container mt-4">
    <!-- Заголовок и кнопка на одной строке -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Добавить отзыв</h1>
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-warning">Назад к списку отзывов</a>
    </div>

    <!-- Форма для добавления отзыва -->
    <form action="{{ route('admin.reviews.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="product_id">Продукт</label>
            <select name="product_id" id="product_id" class="form-control" required>
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="author_name">Имя автора</label>
            <input type="text" name="author_name" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="rating">Рейтинг</label>
            <select name="rating" id="rating" class="form-control">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select>
        </div>

        <div class="form-group">
            <label for="review_text">Отзыв</label>
            <textarea name="review_text" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Добавить отзыв</button>
    </form>
</div>
@endsection
