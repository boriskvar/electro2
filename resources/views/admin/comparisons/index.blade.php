@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Сравнение товаров</h1>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Форма добавления товара в сравнение -->
    <div class="card mb-3">
        <div class="card-header">Добавить товар в сравнение</div>
        <div class="card-body">
            <form action="{{ route('admin.comparisons.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="user_id" class="form-label">Пользователь:</label>
                    <select name="user_id" class="form-select" required>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="product_id" class="form-label">Товар:</label>
                    <select name="product_id" class="form-select" required>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Добавить</button>
            </form>
        </div>
    </div>

    <!-- Таблица сравнений -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Кол-во товаров</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($comparisons as $comparison)
            <tr>
                <td>{{ $comparison->id }}</td>
                <td>{{ $comparison->user->name ?? 'Гость' }}</td>
                <td>{{ $comparison->products->count() }}</td>
                <td>
                    <form action="{{ route('admin.comparisons.destroy', $comparison->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить это сравнение?');">
                            Удалить
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection