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
                <tr>
                    @foreach($products as $product)
                    <td class="value-cell col-md-{{ 12 / count($products) }} text-center" style="background-color: transparent;">
                        {{ $product['name'] }}
                    </td>
                    @endforeach
                </tr>
                <!-- Характеристика "Процессор" -->
                <tr>
                    <th class="attribute-header" colspan="{{ 1 + count($products) }}">
                        Процессор
                    </th>
                </tr>
                <tr>
                    @foreach($products as $product)
                    <td class="value-cell col-md-{{ 12 / count($products) }} text-center" style="background-color: transparent;">
                        {{ $product['processor'] }}
                    </td>
                    @endforeach
                </tr>
                <!-- Характеристика "ОЗУ" -->
                <tr>
                    <th class="attribute-header" colspan="{{ 1 + count($products) }}">
                        ОЗУ
                    </th>
                </tr>
                <tr>
                    @foreach($products as $product)
                    <td class="value-cell col-md-{{ 12 / count($products) }} text-center" style="background-color: transparent;">
                        {{ $product['ram'] }}
                    </td>
                    @endforeach
                </tr>
                <!-- Характеристика "Память" -->
                <tr>
                    <th class="attribute-header" colspan="{{ 1 + count($products) }}">
                        Память
                    </th>
                </tr>
                <tr>
                    @foreach($products as $product)
                    <td class="value-cell col-md-{{ 12 / count($products) }} text-center" style="background-color: transparent;">
                        {{ $product['storage'] }}
                    </td>
                    @endforeach
                </tr>
                <!-- Характеристика "Цена" -->
                <tr>
                    <th class="attribute-header" colspan="{{ 1 + count($products) }}">
                        Цена
                    </th>
                </tr>
                <tr>
                    @foreach($products as $product)
                    <td class="value-cell col-md-{{ 12 / count($products) }} text-center" style="background-color: transparent;">
                        {{ $product['price'] }}
                    </td>
                    @endforeach
                </tr>

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

        /* border: 1px solid #f5f5f5; */
        /* Добавим границу для выделения */
    }
</style>
@endsection