<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h3 class="title">Сравнение товаров</h3>
                </div>
            </div>
        </div>
        @if(!$products->isEmpty())
        <div class="row text-center" {{-- style="margin-bottom: 15px;" --}}>
            <div class="col-md-12">
                <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
                    {{-- категория --}}
                    <h4 class="category-title mb-0" {{-- style="display: inline-block; margin-right: 15px;" --}}>
                        {{ $products->first()->category->name }}
                    </h4>

                    <!-- Форма для удаления всех товаров из сравнения -->
                    <form action="{{ route('compare.clear') }}" method="POST" {{-- style="display: inline;" --}}>
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger p-0" onclick="return confirm('Вы уверены, что хотите очистить сравнение?')" {{-- style="font-size: 18px; vertical-align: middle; border: none; background: none;" --}}>
                            <i class="close-icon" style="font-size: 1.25rem;">x</i>
                        </button>
                    </form>

                </div>
            </div>
            @endif


            <div class="row" style="display: flex; flex-wrap: nowrap; overflow-x: auto;">
                @if($products->isEmpty())
                <div class="col-md-12 text-center">
                    <p>Вы еще не добавили товары для сравнения.</p>
                </div>
                @else
                @foreach($products as $product)
                {{-- <div class="col-md-{{ 12 / count($products) }} text-center"> --}}
                <div class="col-6 col-sm-4 col-md-3 col-lg-{{ 12 / min(count($products), 4) }} text-center">
                    {{-- <div class="value-cell" style="flex: 0 0 {{ 100/count($products) }}%; min-width: 150px;"> --}}

                    <div class="product-row" style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 10px;">
                        <!-- Картинка товара (теперь под крестиком) -->
                        <div class="product-img" style="margin-bottom: 10px;">
                            <img id="alttext-image" src="{{ asset('storage/img/' . $product->images[0]) }}" alt="{{ $product->name }}" class="img-responsive center-block">
                        </div>
                        <!-- Кнопка удаления (крестик) -->
                        <form action="{{ route('compare.remove') }}" method="POST" {{-- style="display: inline;" --}}>
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Удалить этот товар из сравнения?')" {{-- style="font-size: 18px; border: none; background: none;" --}}>
                                <i class="close-icon">x</i>
                            </button>
                        </form>
                    </div>
                    <div class="product-widget">

                        <!-- Название и цена товара (под картинкой) -->
                        <div class="product-body">
                            <h3 class="product-name">
                                <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                            </h3>
                            <!-- Цена товара -->
                            <h4 class="product-price">
                                @if ($product->old_price)
                                <del class="product-old-price">${{ $product->old_price }}</del>
                                @endif
                                ${{ $product->price }}
                            </h4>
                        </div>

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
        .section {
            width: 100%;
            overflow: hidden;
            /* Обрезает всё, что выходит за границы */
            position: relative;
            /* Для корректного позиционирования дочерних элементов */
        }

        .container {
            width: 100%;
            max-width: 1140px;
            /* Стандартная ширина Bootstrap */
            margin: 0 auto;
            /* Центрирование */
            padding: 0 15px;
            /* Отступы по краям */
            box-sizing: border-box;
            /* Чтобы padding не увеличивал ширину */
        }

        .row::after {
            content: "";
            display: table;
            clear: both;
        }

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
            /* padding: 10px; */
            font-size: 12px;
            color: #333;
            /* border: 2px solid #f5f5f5; */
            /* Сделает рамку у значений */
            /* background-color: #f5f5f5; */
            /* Цвет фона как у заголовков */

        }

        .close-icon {
            font-size: 20px;
            color: #ea0d0d;
            width: 20px;
            height: 20px;
            text-align: center;
            line-height: 20px;
            aspect-ratio: 1 / 1;
            transition: color 0.2s ease-in-out;
        }

        .product-img img {
            max-width: 80px;
            /* Фиксированная ширина */
            /* height: auto; */
        }
    </style>