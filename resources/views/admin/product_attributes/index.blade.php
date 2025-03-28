@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Список характеристик товара</h1>
    <a href="{{ route('admin.product-attributes.create') }}" class="btn btn-primary">Добавить характеристику</a>

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
                <th>Продукт</th>
                <th>Характеристика</th>
                <th>Значение</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @if (!$productAttributes->isEmpty())
            @foreach ($productAttributes as $attribute)
            <tr>
                <td>{{ $attribute->id }}</td>
                <td>{{ $attribute->product->name ?? 'Не указан' }}</td>
                <td>{{ $attribute->categoryAttribute->attribute_name ?? 'Не указана' }}</td>
                <td>
                    {{-- {{ $attribute->value }} --}}
                    <a href="{{ route('admin.product-attributes.show', $attribute->id) }}">
                        {{ $attribute->value }}
                    </a>
                </td>
                <td>
                    <!-- Кнопка для редактирования -->
                    <a href="{{ route('admin.product-attributes.edit', $attribute->id) }}" class="btn btn-sm btn-primary">
                        Редактировать
                    </a>

                    <!-- Кнопка для удаления -->
                    <form action="{{ route('admin.product-attributes.destroy', $attribute->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту характеристику?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="5" class="text-center">Нет доступных характеристик.</td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
@endsection