@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>{{ $product->name }}</h1> <!-- Название товара -->

    <h3>Характеристики</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Тип</th>
                <th>Значение</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categoryAttributes as $attribute)
            <tr>
                <td>{{ $attribute->attribute_name }}</td>
                <td>{{ $attribute->attribute_type }}</td>
                <td>{{ $attribute->pivot->attribute_value ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Назад</a>
</div>
@endsection