@extends('layouts.admin')

@section('content')
{{-- {{ dd($products->toArray()) }} --}}
<div class="container">
    <h1>Список товаров</h1>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">Добавить товар</a>
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
                <th>Название</th>
                <th>Отзывы</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @if ($products->isNotEmpty())
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>

                <td><a href="{{ route('admin.products.show', $product->id) }}">{{ $product->name }}</a></td>

                <td>
                    <!-- Кнопка для перехода к отзывам -->
                    <a href="{{ route('admin.reviews.index', $product->id) }}" class="btn btn-sm btn-info">Посм отзывы</a>
                </td>

                <td>
                    <!-- Кнопка для добавления товара в корзину -->
                    {{-- <form action="{{ route('admin.cart.add', ['productId' => $product->id]) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-success">Добавить в корзину</button>
                    </form> --}}
                    <!-- Кнопка для перехода к отзывам -->
                    {{-- <a href="{{ route('admin.reviews.index', $product->id) }}" class="btn btn-sm btn-info">Просмотреть
                    отзывы</a> --}}
                    <!-- Кнопка для редактирования товара -->
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">Редактировать</a>

                    <!-- Кнопка для удаления товара -->
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить этот товар?');" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @else
            <tr>
                <td colspan="4">Нет доступных товаров.</td>
            </tr>
            @endif
        </tbody>
    </table>

</div>
@endsection