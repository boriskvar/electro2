@extends('layouts.admin')

@section('content')
  <div class="container">
    <h1>Добавить новый бренд</h1>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.brands.store') }}"
          method="POST"
          enctype="multipart/form-data">
      @csrf
      <div class="form-group">
        <label for="name">Название бренда:</label>
        <input type="text"
               class="form-control"
               id="name"
               name="name"
               required>
      </div>
      <div class="form-group">
        <label for="slug">Slug:</label>
        <input type="text"
               class="form-control"
               id="slug"
               name="slug"
               required>
      </div>
      <div class="form-group">
        <label for="logo">Логотип:</label>
        <input type="file"
               class="form-control"
               id="logo"
               name="logo"
               accept="image/*">
      </div>
      <div class="form-group">
        <label for="popularity">Популярность:</label>
        <input type="number"
               class="form-control"
               id="popularity"
               name="popularity"
               min="0">
      </div>
      <div class="form-group">
        <label for="description">Описание:</label>
        <textarea class="form-control"
                  id="description"
                  name="description"
                  rows="4"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Создать бренд</button>
      <a href="{{ route('admin.brands.index') }}" class="btn btn-warning">Назад к списку брендов</a>
    </form>
  </div>
@endsection
