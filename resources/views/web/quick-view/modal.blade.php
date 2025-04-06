<div class="section">
    <!-- Карточка товара -->
    <div class="row justify-content-center">
        <div class="col-6 col-sm-4 col-md-3 text-center">
            <div class="product-widget">
                <!-- Название и цена -->
                <div class="product-body">
                    <img src="{{ $product->images[0] ? asset('storage/img/' . $product->images[0]) : asset('storage/img/default.png') }}" alt="{{ $product->name }}" class="img-responsive center-block" style="max-width: 80px;">
                    <h3 class="product-name">
                        <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                    </h3>
                    <h4 class="product-price">
                        @if ($product->old_price)
                        <del class="product-old-price">${{ $product->old_price }}</del>
                        @endif
                        ${{ $product->price }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Характеристики -->
    <div class="row justify-content-center">
        <div class="col-6 col-sm-4 col-md-3">
            <div class="text-center">
                @foreach($attributes as $attributeName => $value)
                <div class="attribute-item mb-2">
                    <h5 class="attribute-header">{{ $attributeName }}</h5>
                    <div class="value-cell">{{ $value }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>

<style>
    .product-widget .product-body {
        padding-left: 0 !important;
    }

    /* Стиль для заголовков характеристик */
    .attribute-header {
        font-weight: 700;
        line-height: 1;
        background-color: #f5f5f5;
        /* Цвет фона для заголовка */
        color: #333;
        /* Цвет текста */
        padding: 12px 0;
        /* Отступы сверху и снизу */
        margin-top: 10px;
        /* Отступ сверху от предыдущего элемента */
        text-align: center;
        /* Центрирование текста */
        border-radius: 5px;
        /* Скругленные углы */
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        /* Легкая тень */
    }
</style>