@extends('layouts.my-account')

@section('content')
<div class="container">
    <h3>Сравнение товаров</h3>

    @if($comparisons->isEmpty())
    <p>Вы еще не добавили товары для сравнения.</p>
    @else
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Товар</th>
                <th>Действие</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comparisons as $comparison)
            @foreach($comparison->products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>
                    <form action="{{ route('compare.remove') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button class="btn btn-warning">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>

    <form action="{{ route('compare.clear') }}" method="POST">
        @csrf
        @method('DELETE')
        <button class="btn btn-danger">Очистить сравнение</button>
    </form>
    @endif
</div>
@endsection