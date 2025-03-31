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
            <table class="comparison-table table-bordered text-center">
                <!-- Проверка на наличие товаров -->
                <tr>
                    @if(empty($products))
                    <td colspan="5" class="text-center">Вы еще не добавили товары для сравнения.</td>
                    @else
                    @foreach($products as $product)
                    <td class="value-cell col-md-{{ 12 / count($products) }} text-center" style="background-color: transparent;">
                        {{ $product['name'] }}
                    </td>
                    @endforeach
                    @endif
                </tr>

                <!-- Динамический вывод атрибутов -->
                @foreach($categoryAttributes as $attribute)
                <tr>
                    <!-- Заголовок для каждой характеристики -->
                    <th class="attribute-header" colspan="{{ 1 + count($products) }}">
                        {{ $attribute->attribute_name }}
                    </th>
                </tr>


                <tr>
                    @foreach($products as $product)
                    @php
                    // Ищем значение атрибута по его ID
                    $attributeValue = $product->attributes->firstWhere('category_attribute_id', $attribute->id);
                    @endphp
                    <td class="value-cell col-md-{{ 12 / count($products) }} text-center" style="background-color: transparent;">
                        {{ $attributeValue->value ?? '—' }}
                    </td>
                    @endforeach
                </tr>


                @endforeach
            </table>
        </div>
    </div>
</div>

<style>
    /* Стиль для заголовков характеристик */
    .attribute-header {
        position: relative;
        padding: 12px 0;
        text-align: center;
        font-weight: 700;
        line-height: 1;
        background-color: #f5f5f5;
        color: #333;
    }
</style>
@endsection