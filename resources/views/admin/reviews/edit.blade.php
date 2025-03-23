@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Редактировать отзыв</h3>

    <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="rating">Рейтинг:</label>
            <select name="rating" class="form-control" required>
                <option value="1" {{ $review->rating == 1 ? 'selected' : '' }}>1</option>
                <option value="2" {{ $review->rating == 2 ? 'selected' : '' }}>2</option>
                <option value="3" {{ $review->rating == 3 ? 'selected' : '' }}>3</option>
                <option value="4" {{ $review->rating == 4 ? 'selected' : '' }}>4</option>
                <option value="5" {{ $review->rating == 5 ? 'selected' : '' }}>5</option>
            </select>
        </div>
        <div class="form-group">
            <label for="review_text">Текст отзыва:</label>
            <textarea name="review_text" class="form-control" rows="4">{{ $review->review_text }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Обновить отзыв</button>
    </form>
</div>
@endsection
