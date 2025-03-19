@extends('layouts.admin')

@section('content')
  <div class="container">
    <h1>Редактировать Меню</h1>

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

    <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="name">Название</label>
        <input type="text"
               class="form-control @error('name') is-invalid @enderror"
               id="name"
               name="name"
               value="{{ old('name', $menu->name) }}"
               required>
        @error('name')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="slug">Слаг</label>
        <input type="text"
               class="form-control @error('slug') is-invalid @enderror"
               id="slug"
               name="slug"
               value="{{ old('slug', $menu->slug) }}">
        @error('slug')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <!-- Поле выбора типа меню -->
      <div class="form-group">
        <label for="menu_type">Тип меню</label>
        <select class="form-control @error('menu_type') is-invalid @enderror"
                id="menu_type"
                name="menu_type">
          <option value="main" {{ old('menu_type', $menu->menu_type) == 'main' ? 'selected' : '' }}>Верхнее меню
          </option>
          <option value="footer" {{ old('menu_type', $menu->menu_type) == 'footer' ? 'selected' : '' }}>Нижнее меню
          </option>
        </select>
        <small class="form-text text-muted">Выберите, где будет отображаться этот пункт меню.</small>
        @error('menu_type')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <!-- Поле выбора типа нижнего меню -->
      <div class="form-group"
           id="footer_type_wrapper"
           style="{{ $menu->menu_type === 'footer' ? 'display:block' : 'display:none' }}">
        <label for="type">Тип нижнего меню</label>
        <select class="form-control @error('type') is-invalid @enderror"
                id="type"
                name="type">
          <option value="about" {{ old('type', $menu->type) == 'about' ? 'selected' : '' }}>About Us</option>
          <option value="categories" {{ old('type', $menu->type) == 'categories' ? 'selected' : '' }}>Categories</option>
          <option value="information" {{ old('type', $menu->type) == 'information' ? 'selected' : '' }}>Information
          </option>
          <option value="service" {{ old('type', $menu->type) == 'service' ? 'selected' : '' }}>Service</option>
        </select>
        <small class="form-text text-muted">Выберите тип пункта меню для нижнего меню.</small>
        @error('type')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="category_id">Категория</label>
        <select class="form-control @error('category_id') is-invalid @enderror"
                id="category_id"
                name="category_id"
                onchange="toggleCustomUrl()">
          <option value="">Выберите категорию</option>
          @foreach ($categories as $category)
            <option value="{{ $category->id }}"
                    {{ old('category_id', $menu->category_id) == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
        <small class="form-text text-muted">Если выбрана категория, произвольный URL не работает.</small>
        @error('category_id')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="page_id">Стандартная страница</label>
        <select class="form-control @error('page_id') is-invalid @enderror"
                id="page_id"
                name="page_id"
                onchange="toggleCustomUrl()">
          <option value="">Выберите страницу</option>
          @foreach ($pages as $page)
            <option value="{{ $page->id }}" {{ old('page_id', $menu->page_id) == $page->id ? 'selected' : '' }}>
              {{ $page->title }}
            </option>
          @endforeach
        </select>
        <small class="form-text text-muted">Если выбрана стандартная страница, категория и URL не работают.</small>
        @error('page_id')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <!-- Поле для выбора главной страницы -->
      <div class="form-group">
        <label for="is_home">Сделать выбранную страницу Главной</label>
        <select class="form-control @error('is_home') is-invalid @enderror"
                id="is_home"
                name="is_home">
          <option value="0" {{ old('is_home', $menu->is_home) == '0' ? 'selected' : '' }}>Нет</option>
          <option value="1" {{ old('is_home', $menu->is_home) == '1' ? 'selected' : '' }}>Да</option>
        </select>
        <small class="form-text text-muted">Если этот пункт меню выбран как главная страница, все остальные потеряют этот
          статус.</small>
        @error('is_home')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="custom_url">Произвольный URL</label>
        <input type="text"
               class="form-control @error('custom_url') is-invalid @enderror"
               id="custom_url"
               name="custom_url"
               value="{{ old('custom_url', $menu->url) }}"
               {{ $menu->category_id || $menu->page_id ? 'disabled' : '' }}>
        <small class="form-text text-muted">Если не выбрана категория или страница, введите URL вручную.</small>
        @error('custom_url')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="position">Позиция</label>
        <input type="number"
               class="form-control @error('position') is-invalid @enderror"
               id="position"
               name="position"
               value="{{ old('position', $menu->position) }}"
               required
               min="1">
        @error('position')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="parent_id">Родительское меню</label>
        <select class="form-control @error('parent_id') is-invalid @enderror"
                id="parent_id"
                name="parent_id">
          <option value="">Нет родителя</option>
          @foreach ($parentMenus as $parentMenu)
            <option value="{{ $parentMenu->id }}"
                    {{ old('parent_id', $menu->parent_id) == $parentMenu->id ? 'selected' : '' }}>
              {{ $parentMenu->name }}</option>
          @endforeach
        </select>
        @error('parent_id')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <div class="form-group">
        <label for="is_active">Активен</label>
        <select class="form-control @error('is_active') is-invalid @enderror"
                id="is_active"
                name="is_active">
          <option value="1" {{ old('is_active', $menu->is_active) == 1 ? 'selected' : '' }}>Да</option>
          <option value="0" {{ old('is_active', $menu->is_active) == 0 ? 'selected' : '' }}>Нет</option>
        </select>
        @error('is_active')
          <div class="invalid-feedback text-danger">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">Обновить Меню</button>
      <a href="{{ route('admin.menus.index') }}" class="btn btn-warning">Назад</a>
    </form>
  </div>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      let pageId = document.getElementById('page_id');
      let isHome = document.getElementById('is_home');
      let categoryId = document.getElementById('category_id');
      let customUrl = document.getElementById('custom_url');

      function toggleHomeOption() {
        if (pageId.value) {
          isHome.disabled = false;
        } else {
          isHome.disabled = true;
          isHome.value = "0";
        }
      }

      function toggleCustomUrl() {
        if (categoryId.value || pageId.value) {
          customUrl.disabled = true;
          customUrl.value = '';
        } else {
          customUrl.disabled = false;
        }
      }

      pageId.addEventListener('change', toggleHomeOption);
      categoryId.addEventListener('change', toggleCustomUrl);
      toggleHomeOption();
      toggleCustomUrl(); // Проверить при загрузке
    });
  </script>

@endsection
