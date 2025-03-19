@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Список желаемого</h1>

    @if (session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- Форма добавления в Wishlist -->
    <div class="card mb-3">
        <div class="card-header">Добавить в Wishlist</div>
        <div class="card-body">
            <form action="{{ route('admin.wishlists.store') }}" method="POST">
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

    <!-- Таблица с Wishlist -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Пользователь</th>
                <th>Товар</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wishlists as $wishlist)
            <tr>
                <td>{{ $wishlist->id }}</td>
                <td>{{ $wishlist->user->name ?? 'Неизвестный' }}</td>
                <td>{{ $wishlist->product->name ?? 'Товар удален' }}</td>
                <td>
                    <form action="{{ route('admin.wishlists.destroy', $wishlist->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Удалить этот товар из списка желаемого?');">
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