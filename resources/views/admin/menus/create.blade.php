@extends('layouts.admin')

@section('content')
  <div class="container">
    <h1>Добавить Меню</h1>

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

    <form action="{{ route('admin.menus.store') }}" method="POST">
      @csrf

      <!-- Название -->
      <div class="form-group">
        <label for="name">Название</label>
        <input type="text"
               class="form-control @error('name') is-invalid @enderror"
               id="name"
               name="name"
               placeholder="Введите название"
               value="{{ old('name') }}"
               required>
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Slug -->
      <div class="form-group">
        <label for="slug">Slug</label>
        <input type="text"
               class="form-control @error('slug') is-invalid @enderror"
               id="slug"
               name="slug"
               value="{{ old('slug') }}"
               readonly>
        @error('slug')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Раздел меню -->
      <div class="form-group">
        <label for="menu_type">Раздел меню</label>
        <select class="form-control @error('menu_type') is-invalid @enderror"
                id="menu_type"
                name="menu_type">
          <option value="main" {{ old('menu_type') == 'main' ? 'selected' : '' }}>Верхнее меню</option>
          <option value="footer" {{ old('menu_type') == 'footer' ? 'selected' : '' }}>Нижнее меню</option>
        </select>
        @error('menu_type')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Тип нижнего меню -->
      <div class="form-group" id="footer_type_wrapper">
        <label for="type">Тип нижнего меню</label>
        <select class="form-control @error('type') is-invalid @enderror"
                id="type"
                name="type">
          <option value="">Выберите тип</option>
          @foreach ($footerTypes as $key => $label)
            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}
            </option>
          @endforeach
        </select>
        @error('type')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Родительское меню -->
      <div class="form-group">
        <label for="parent_id">Родительское меню</label>
        <select class="form-control @error('parent_id') is-invalid @enderror"
                id="parent_id"
                name="parent_id">
          <option value="">Нет родителя</option>
          @foreach ($parentMenus as $parentMenu)
            <option value="{{ $parentMenu->id }}" {{ old('parent_id') == $parentMenu->id ? 'selected' : '' }}>
              {{ $parentMenu->name }}
            </option>
          @endforeach
        </select>
        @error('parent_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Категория -->
      <div class="form-group">
        <label for="category_id">Категория</label>
        <select class="form-control @error('category_id') is-invalid @enderror"
                id="category_id"
                name="category_id">
          <option value="">Выберите категорию</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
        @error('category_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Стандартная страница -->
      <div class="form-group">
        <label for="page_id">Стандартная страница</label>
        <select class="form-control @error('page_id') is-invalid @enderror"
                id="page_id"
                name="page_id">
          <option value="">Выберите страницу</option>
          @foreach ($pages as $page)
            <option value="{{ $page->id }}" {{ old('page_id') == $page->id ? 'selected' : '' }}>{{ $page->title }}
            </option>
          @endforeach
        </select>
        @error('page_id')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Главная страница -->
      <div class="form-group">
        <label for="is_home">Сделать Главной</label>
        <select class="form-control @error('is_home') is-invalid @enderror"
                id="is_home"
                name="is_home">
          <option value="0" {{ old('is_home') == '0' ? 'selected' : '' }}>Нет</option>
          <option value="1" {{ old('is_home') == '1' ? 'selected' : '' }}>Да</option>
        </select>
        @error('is_home')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Произвольный URL -->
      <div class="form-group">
        <label for="custom_url">Произвольный URL</label>
        <input type="text"
               class="form-control @error('custom_url') is-invalid @enderror"
               id="custom_url"
               name="custom_url"
               value="{{ old('custom_url') }}">
        @error('custom_url')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Позиция -->
      <div class="form-group">
        <label for="position">Позиция</label>
        <input type="number"
               class="form-control @error('position') is-invalid @enderror"
               id="position"
               name="position"
               value="{{ old('position') }}"
               required>
        @error('position')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Активность -->
      <div class="form-group">
        <label for="is_active">Активен</label>
        <select class="form-control @error('is_active') is-invalid @enderror"
                id="is_active"
                name="is_active">
          <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Да</option>
          <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Нет</option>
        </select>
        @error('is_active')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">Создать Меню</button>
      <a href="{{ route('admin.menus.index') }}" class="btn btn-warning">Назад</a>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.getElementById('menu_type').addEventListener('change', function() {
        document.getElementById('footer_type_wrapper').style.display = this.value === 'footer' ? 'block' : 'none';
      });
    });
  </script>

@endsection
