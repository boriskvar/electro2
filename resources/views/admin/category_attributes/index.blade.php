@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Список характеристик</h1>
    <a href="{{ route('admin.category-attributes.create') }}" class="btn btn-primary">Добавить характеристику</a>

    <!-- Отображение сообщений об ошибках -->
    @if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif

    <!-- Отображение сообщений об успехе -->
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название характеристики</th>
                <th>Тип характеристики</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @if (!$categoryAttributes->isEmpty())
            @foreach ($categoryAttributes as $attribute)
            <tr>
                <td>{{ $attribute->id }}</td>
                <!-- Название характеристики как ссылка на страницу show -->
                <td>
                    <a href="{{ route('admin.category-attributes.show', $attribute->id) }}">
                        {{ $attribute->attribute_name }}
                    </a>
                </td>
                <td>{{ $attribute->attribute_type ?? 'Не указан' }}</td>
                <td>
                    <!-- Кнопка для редактирования характеристики -->
                    <a href="{{ route('admin.category-attributes.edit', $attribute->id) }}" class="btn btn-sm btn-primary">
                        Редактировать
                    </a>

                    <!-- Кнопка для удаления характеристики -->
                    <form action="{{ route('admin.category-attributes.destroy', $attribute->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту характеристику?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4" class="text-center">Нет доступных характеристик.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection