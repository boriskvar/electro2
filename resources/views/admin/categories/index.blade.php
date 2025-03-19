@extends('layouts.admin')

@section('content')
{{-- {{ dd($categories->toArray()) }} --}}
<div class="container">
    <h1>Список категорий</h1>
    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Добавить категорию</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Влож категории</th>
                <th>Порядок</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
            <tr>
                <td>{{ $category->id }}</td>
                {{-- <td>{{ $category->name }}</td> --}}
                <td><a href="{{ route('admin.categories.show', $category->id) }}">{{ $category->name }}</a></td>
                <td>
                    @if ($category->children->count())
                    <ul>
                        @foreach ($category->children as $child)
                        <li class="ms-3">{{ $child->name }}</li>
                        @endforeach
                    </ul>
                    @endif
                </td>
                <td>{{ $category->display_order }}</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-primary">Редактировать</a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection