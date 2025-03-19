@extends('layouts.admin')

@section('content')
  <div class="container">
    <h1>Редактировать бренд: {{ $brand->name }}</h1>

    @if (session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.brands.update', $brand->id) }}"
          method="POST"
          enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="name">Название бренда:</label>
        <input type="text"
               class="form-control"
               id="name"
               name="name"
               value="{{ old('name', $brand->name) }}"
               required>
      </div>
      <div class="form-group">
        <label for="slug">Slug:</label>
        <input type="text"
               class="form-control"
               id="slug"
               name="slug"
               value="{{ old('slug', $brand->slug) }}"
               required>
      </div>

      <div class="form-group">
        <label for="logo">Логотип:</label>
        <input type="file"
               class="form-control"
               id="logo"
               name="logo"
               accept="image/*">

        @if ($brand->logo)
          <div class="mt-2">
            <p>Текущий логотип:</p>
            @php
              $logoPath = 'storage/logos/' . basename($brand->logo);
            @endphp
            @if (file_exists(public_path($logoPath)))
              <img src="{{ asset($logoPath) }}"
                   alt="{{ $brand->name }} Logo"
                   class="brand-logo"
                   style="max-width: 200px;">
            @else
              <p>Логотип отсутствует.</p>
            @endif
          </div>
        @else
          <p>Логотип отсутствует.</p>
        @endif
      </div>

      <div class="form-group">
        <label for="popularity">Популярность:</label>
        <input type="number"
               class="form-control"
               id="popularity"
               name="popularity"
               value="{{ old('popularity', $brand->popularity) }}"
               min="0">
      </div>
      <div class="form-group">
        <label for="description">Описание:</label>
        <textarea class="form-control"
                  id="description"
                  name="description"
                  rows="4">{{ old('description', $brand->description) }}</textarea>
      </div>
      <button type="submit" class="btn btn-primary">Сохранить изменения</button>
      <a href="{{ route('admin.brands.index') }}" class="btn btn-warning">Назад к списку брендов</a>
    </form>
  </div>
@endsection
