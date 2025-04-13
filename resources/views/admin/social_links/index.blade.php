@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Социальные ссылки</h2>
    <a href="{{ route('admin.social-links.create') }}" class="btn btn-primary">Добавить новую</a>
    <hr>

    @include('admin.partials.messages')

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>URL</th>
                <th>Иконка</th>
                <th>Тип</th>
                <th>Позиция</th>
                <th>Новая вкладка</th>
                <th>Активна</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($socialLinks as $link)
            <tr>
                <td>{{ $link->id }}</td>
                <td>{{ $link->name }}</td>
                <td><a href="{{ $link->url }}" target="_blank">{{ $link->url }}</a></td>
                <td><i class="{{ $link->icon_class }}"></i> {{ $link->icon_class }}</td>
                <td>{{ $link->type }}</td>
                <td>{{ $link->position }}</td>
                <td>{{ $link->open_in_new_tab ? 'Да' : 'Нет' }}</td>
                <td>{{ $link->active ? 'Да' : 'Нет' }}</td>
                <td>
                    <a href="{{ route('admin.social-links.edit', $link->id) }}" class="btn btn-xs btn-warning">Редактировать</a>
                    <form action="{{ route('admin.social-links.destroy', $link->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-xs btn-danger" onclick="return confirm('Удалить?')">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection