@extends('layouts.admin')

@section('content')
  <h1>Отзывы</h1>

  <!-- Кнопка для добавления нового отзыва -->
  <a href="{{ route('admin.reviews.create') }}" class="btn btn-primary mb-3">Добавить отзыв</a>



  @if (session('success'))
    <div class="alert alert-success">
      {{ session('success') }}
    </div>
  @endif

  @if ($reviews->isEmpty())
    <p>Нет отзывов для отображения.</p>
  @else
    <table class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Продукт</th>
          <th>Автор</th>
          <th>Email</th>
          <th>Рейтинг</th>
          <th>Текст отзыва</th>
          <th>Дата создания</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($reviews as $review)
          <tr>
            <td>{{ $review->id }}</td>
            <td>{{ $review->product->name ?? 'Продукт не найден' }}</td>
            <td>{{ $review->author_name }}</td>
            <td>{{ $review->email }}</td>
            <td>{{ $review->rating }}</td>
            <td>{{ $review->review_text }}</td>
            <td>{{ $review->created_at->format('d.m.Y H:i') }}</td>
            <td>
              <a href="{{ route('admin.reviews.edit', $review->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
              <form action="{{ route('admin.reviews.destroy', $review->id) }}"
                    method="POST"
                    style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Вы уверены?')">Удалить</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
  @endif


  <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-warning">Назад к списку товаров</a>
@endsection
