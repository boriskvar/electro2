@extends('layouts.admin')

@section('content')
  <div class="container">
    <h1>{{ $menu->name }}</h1>

    <!-- Название -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="name" style="font-weight: normal; margin-right: 10px;">Название:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->name }}
      </div>
    </div>

    <!-- Слаг -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="slug" style="font-weight: normal; margin-right: 10px;">Слаг:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->slug }}
      </div>
    </div>

    <!-- Тип меню -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="menu_type" style="font-weight: normal; margin-right: 10px;">Тип меню:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->menu_type == 'main' ? 'Верхнее меню' : 'Нижнее меню' }}
      </div>
    </div>

    <!-- Поле выбора типа нижнего меню -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="menu_type" style="font-weight: normal; margin-right: 10px;">Тип нижнего меню:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->type }}
      </div>
    </div>

    <!-- Категория -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="category" style="font-weight: normal; margin-right: 10px;">Категория:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->category ? $menu->category->name : 'Нет категории' }}
      </div>
    </div>

    <!-- Стандартная страница -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="page_id" style="font-weight: normal; margin-right: 10px;">Стандартная страница:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->page_id ? $menu->page->title : 'Нет' }}
      </div>
    </div>

    <!-- Главная страница -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="is_home" style="font-weight: normal; margin-right: 10px;">Выбранная Стандартная страница -
        Главная:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->is_home ? 'Да' : 'Нет' }}
      </div>
    </div>

    <!-- Произвольный URL -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="url" style="font-weight: normal; margin-right: 10px;">Произвольный URL:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->custom_url ? $menu->custom_url : 'Нет' }}
      </div>
    </div>

    <!-- Позиция -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="position" style="font-weight: normal; margin-right: 10px;">Позиция:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->position }}
      </div>
    </div>

    <!-- Родительское меню -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="parent" style="font-weight: normal; margin-right: 10px;">Родительское меню:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->parent_id ? \App\Models\Menu::find($menu->parent_id)->name : 'Нет' }}
      </div>
    </div>

    <!-- Активен -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
      <label for="is_active" style="font-weight: normal; margin-right: 10px;">Активен:</label>
      <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
        {{ $menu->is_active ? 'Да' : 'Нет' }}
      </div>
    </div>

    <a href="{{ route('admin.menus.index') }}" class="btn btn-warning">Назад к меню</a>
  </div>
@endsection
