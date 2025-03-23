@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Список страниц</h1>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">Добавить страницу</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Slug</th>
                {{-- <th>Категория</th> <!-- Добавлено для отображения категории --> --}}
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pages as $page)
            <tr>
                <td>{{ $page->id }}</td>
                <td><a href="{{ route('admin.pages.show', $page->id) }}">{{ $page->title }}</a></td>
                <td>{{ $page->slug }}</td>
                {{-- <td>{{ $page->category->name ?? 'Без категории' }}</td> --}}
                <!-- Отображение названия категории или "Без категории" -->
                <td>
                    <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                    <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- <a href="{{ route('admin.categories.index') }}" class="btn btn-sm btn-warning mb-3">Перейти к списку категорий</a> --}}

</div>
@endsection