@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Список товаров в сравнении #{{ $comparison->id }}</h2>

    {{-- <a href="{{ route('admin.comparisons.index') }}" class="btn btn-secondary mb-3">Назад к списку сравнений</a> --}}
    @if(isset($comparison))
    <a href="{{ route('admin.comparison_products.index', ['comparison' => $comparison->id]) }}">
        Все товары в сравнении
    </a>
    @else
    <span class="text-muted">comparison_product (выберите сравнение)</span>
    @endif

    @if ($products->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price, 2) }} грн</td>
                <td>
                    <form action="{{ route('admin.comparison_products.destroy', ['comparison' => $comparison->id, 'product' => $product->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>В этом сравнении пока нет товаров.</p>
    @endif

    <form action="{{ route('admin.comparisons.clear', $comparison) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-warning">Очистить сравнение</button>
    </form>
</div>
@endsection