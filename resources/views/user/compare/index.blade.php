@extends('layouts.main')

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h3 class="title">Сравнение товаров</h3>
                </div>
            </div>
        </div>

        <div class="row">
            @if($products->isEmpty())
            <div class="col-md-12 text-center">
                <p>Вы еще не добавили товары для сравнения.</p>
            </div>
            @else
            @foreach($products as $product)
            <div class="col-md-{{ 12 / count($products) }} text-center">
                <div class="product-widget">
                    <!-- Изображение товара сверху -->
                    <div class="product-img mb-2">
                        <img src="{{ asset('storage/img/' .$product->images[0]) }}" alt="{{ $product->name }}" class="product-img" style="max-width: 120px; margin-bottom: 10px;">
                    </div>

                    <!-- Иконка удаления -->
                    <form action="{{ route('compare.remove', $product->id) }}" method="POST" class="position-relative" style="top: -20px; right: -5px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0">
                            <i class="fa fa-trash text-danger"></i>
                        </button>
                    </form>

                    <!-- Название товара -->
                    <h3 class="product-name">
                        <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                    </h3>

                    <!-- Цена товара -->
                    <h4 class="product-price text-bold text-danger">
                        ${{ $product->price }}
                        @if ($product->old_price)
                        <del class="product-old-price">${{ $product->old_price }}</del>
                        @endif
                    </h4>
                </div>
            </div>
            @endforeach
            @endif
        </div>

        <!-- Динамический вывод атрибутов -->
        @foreach($categoryAttributes as $attribute)
        <div class="row">
            <!-- Заголовок для каждой характеристики -->
            <div class="col-md-12 text-center">
                <h5 class="attribute-header">{{ $attribute->attribute_name }}</h5>
            </div>
            @foreach($products as $product)
            <div class="col-md-{{ 12 / count($products) }} text-center">
                @php
                // Ищем значение атрибута по его ID
                $attributeValue = $product->attributes->firstWhere('category_attribute_id', $attribute->id);
                @endphp
                <div class="value-cell" style="background-color: transparent;">
                    {{ $attributeValue->value ?? '—' }}
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
</div>

<style>
    /* Стиль для заголовков характеристик */
    .attribute-header {
        font-weight: 700;
        line-height: 1;
        background-color: #f5f5f5;
        color: #333;
        padding: 12px 0;
        margin-top: 10px;
        text-align: center;
    }

    /* Стиль для ячеек значений */
    .value-cell {
        padding: 10px;
        font-size: 14px;
        color: #333;
        border-top: 1px solid #f5f5f5;
    }
</style>

@endsection