@extends('layouts.admin')

@section('content')

<div class="container">
    <h1>{{ $category->name }}</h1>

    <!-- Имя категории -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
        <label for="product_name" style="font-weight: normal; margin-right: 10px;">Название категории:</label>
        <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
            {{ $category->name }}
        </div>
    </div>

    <!-- Изображение категории -->
    <div style="display: flex; align-items: center; margin-bottom: 10px;">
        <label for="category_image" style="font-weight: normal; margin-right: 10px;">Изображение:</label>
        @if (!empty($category->image))
        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}" class="img-fluid" style="max-width: 150px;">
        @else
        <p>Изображение категории не указано.</p>
        @endif
    </div>

    <!-- Основная информация -->
    <div style="margin-bottom: 20px;">
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="slug" style="font-weight: normal; margin-right: 10px;">Slug:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $category->slug }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="description" style="font-weight: normal; margin-right: 10px;">Описание:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $category->description }}
            </div>
        </div>

        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="parent_id" style="font-weight: normal; margin-right: 10px;">Родительская категория:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $category->parent_id ? $category->parent->name : 'Нет родительской категории' }}
            </div>
        </div>

        <!-- Статус активности -->
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="active" style="font-weight: normal; margin-right: 10px;">Активна:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $category->active ? 'Да' : 'Нет' }}
            </div>
        </div>

        <!-- Статус распродажи -->
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="is_sale" style="font-weight: normal; margin-right: 10px;">На распродаже:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $category->is_sale ? 'Да' : 'Нет' }}
            </div>
        </div>

        <!-- Порядок отображения -->
        <div style="display: flex; align-items: center; margin-bottom: 10px;">
            <label for="display_order" style="font-weight: normal; margin-right: 10px;">Порядок отображения:</label>
            <div style="background-color: var(--background-color); padding: 5px; border-radius: 5px; font-weight: bold;">
                {{ $category->display_order }}
            </div>
        </div>

    </div>

    <!-- Дочерние категории -->
    <h2 style="margin-top: 20px; margin-bottom: 10px;">Дочерние категории</h2>
    @if($category->children->isNotEmpty())
    <ul style="list-style-type: none; padding-left: 0;">
        @foreach($category->children as $child)
        <li style="margin-bottom: 5px;">
            <a href="{{ route('admin.categories.show', $child->id) }}" style="text-decoration: none; color: #007bff;">
                {{ $child->name }}
            </a>
        </li>
        @endforeach
    </ul>
    @else
    <p style="color: #888;">Нет дочерних категорий.</p>
    @endif

    <!-- Кнопка назад -->
    <a href="{{ route('admin.categories.index') }}" class="btn btn-warning" style="margin-top: 20px;">Назад к списку категорий</a>
</div>

@endsection