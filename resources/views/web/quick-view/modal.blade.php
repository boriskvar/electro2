<div class="section">
    <div class="container">


        <!-- Заголовок -->
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h3 class="title">Быстрый просмотр товара</h3>
                </div>
            </div>
        </div>

        <!-- Категория -->
        <div class="row text-center mb-3">
            <div class="col-md-12">
                <h4 class="category-title mb-0">{{ $product->category->name }}</h4>
            </div>
        </div>

        <!-- Карточка товара -->
        <div class="row justify-content-center">
            <div class="col-6 col-sm-4 col-md-3 text-center">
                <div class="product-widget">
                    <!-- Картинка -->
                    <div class="product-img mb-2">
                        <img src="{{ $product->images[0] ? asset('storage/img/' . $product->images[0]) : asset('storage/img/default.png') }}" alt="{{ $product->name }}" class="img-responsive center-block" style="max-width: 80px;">
                    </div>

                    <!-- Название и цена -->
                    <div class="product-body">
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
        @foreach($attributes as $attributeName => $value)
        <div class="row">
            <div class="col-md-12 text-center">
                <h5 class="attribute-header">{{ $attributeName }}</h5>
            </div>
            <div class="col-md-12 text-center">
                <div class="value-cell">{{ $value }}</div>
            </div>
        </div>
        @endforeach

    </div>
</div>