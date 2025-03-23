@extends('layouts.main')

@section('content')
<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <!-- ASIDE -->
            <div id="aside" class="col-md-2">

                <!-- Форма фильтрации товаров -->
                {{-- <form method="GET" action="{{ url()->current() }}"> --}}
                <form method="GET" action="
                  @if (isset($category)) {{ route('menus.category.show', ['slug' => $menu->slug ?? '', 'category_slug' => $category->slug]) }}
                    @elseif (isset($menu))
                        {{ route('menus.page.show', ['slug' => $menu->slug, 'page_slug' => $menu->slug]) }}
                    @else
                        {{ url()->current() }} @endif
            ">
                    <!-- Categories Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Categories</h3>
                        <div class="checkbox-filter">
                            @foreach ($categories as $cat)
                            <div class="input-checkbox">
                                <input type="checkbox" id="category-{{ $cat->id }}" name="categories[]" value="{{ $cat->id }}" {{-- @if (in_array($cat->id, request('categories', []))) checked @endif> --}} {{-- @if ((isset($category) && $cat->id == $category->id) || in_array($cat->id, request('categories', []))) checked @endif> --}} @if (isset($category) && $cat->id == $category->id) checked @endif
                                @if (!isset($category) && in_array($cat->id, request('categories', []))) checked @endif
                                @if (isset($category) && $cat->id != $category->id) disabled @endif>

                                <label for="category-{{ $cat->id }}">
                                    <span></span>
                                    {{ $cat->name }}
                                    <small>({{ $cat->products_count }})</small>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- /Categories Widget -->

                    <!-- Price Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Price</h3>
                        <div class="price-filter">
                            <div id="price-slider"></div>
                            <div class="input-number price-min">
                                <input id="price-min" type="number" name="min_price" {{-- value="{{ request('min_price', 0) }}" --}} value="{{ request('min_price', $minPrice ?? 0) }}" step="0.1">
                                <span class="qty-up">+</span>
                                <span class="qty-down">-</span>
                            </div>
                            <span>-</span>
                            <div class="input-number price-max">
                                <input id="price-max" type="number" name="max_price" {{-- value="{{ request('max_price', 10000) }}" --}} value="{{ request('max_price', $maxPrice ?? 10000) }}" step="0.1">
                                <span class="qty-up">+</span>
                                <span class="qty-down">-</span>
                            </div>
                        </div>
                    </div>
                    <!-- /Price Widget -->

                    <!-- Brands Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Brand</h3>
                        <div class="checkbox-filter">
                            @if ($brands->isNotEmpty())
                            @foreach ($brands as $brand)
                            <div class="input-checkbox">
                                <input type="checkbox" id="brand-{{ $brand->id }}" name="brands[]" value="{{ $brand->id }}" {{-- {{ in_array($brand->id, request('brands', [])) ? 'checked' : '' }}> --}}
                                {{ in_array($brand->id, request()->input('brands', [])) ? 'checked' : '' }}>
                                <label for="brand-{{ $brand->id }}">
                                    <span></span>
                                    {{ $brand->name }}
                                    <small>({{ $brand->products_count }})</small>
                                </label>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                    <!-- /Brands Widget -->

                    <!-- Apply Button -->
                    <button type="submit" class="btn btn-danger" style="background-color: #D10024; border-color: #D10024;">
                        Apply
                    </button>

                </form>

                <!-- Top Selling Widget -->
                <div class="aside">
                    <h3 class="aside-title">Top selling</h3>
                    @if ($topSellingProducts->isNotEmpty())
                    @foreach ($topSellingProducts as $product)
                    <div class="product-widget">
                        <div class="product-img">
                            <img id="alttext-image" src="{{ asset('storage/img/' . $product->images[0]) }}" alt="{{ $product->name }}">
                        </div>
                        <div class="product-body">
                            <p class="product-category">{{ $product->category->name }}</p>
                            <h3 class="product-name">
                                <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                            </h3>
                            <h4 class="product-price">
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
                <!-- /Top Selling Widget -->
            </div>
            <!-- /ASIDE -->


            <!-- STORE -->
            <div id="app" class="col-md-9">

                <!-- store top filter -->
                <div class="store-filter clearfix">
                    <div class="store-sort">
                        <label>
                            Sort By:
                            <select class="input-select" onchange="location = this.value;">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'position']) }}" {{ request('sort') == 'position' ? 'selected' : '' }}>Position</option>
                            </select>
                        </label>

                        <label>
                            Show:
                            <select class="input-select" onchange="location = this.value;">
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 20]) }}" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </label>
                    </div>

                    <ul class="store-grid">
                        <li class="{{ request('view', 'grid') == 'grid' ? 'active' : '' }}">
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'grid']) }}"><i class="fa fa-th"></i></a>
                        </li>
                        <li class="{{ request('view') == 'list' ? 'active' : '' }}">
                            <a href="{{ request()->fullUrlWithQuery(['view' => 'list']) }}"><i class="fa fa-th-list"></i></a>
                        </li>
                    </ul>

                </div>
                <!-- /store top filter -->

                <!-- store products -->
                {{-- <products :products='@json($products)' :categories='@json($categories)'></products> --}}
                {{-- <products :products='@json($products->data())' :categories='@json($categories)'></products> --}}

                <products :products='@json($products->items())' :categories='@json($categories)' view-type="{{ request('view', 'grid') }}"></products>

                <!-- /store products -->

                <!-- store bottom filter -->
                <div class="store-filter clearfix">
                    <span class="store-qty">Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of
                        {{ $products->total() }} products</span>

                    <ul class="store-pagination">
                        @if ($products->onFirstPage())
                        <li class="disabled"><span>«</span></li>
                        @else
                        <li><a href="{{ $products->previousPageUrl() }}">«</a></li>
                        @endif

                        @foreach ($products->links()->elements[0] as $page => $url)
                        <li class="{{ $page == $products->currentPage() ? 'active' : '' }}">
                            <a href="{{ $url }}">{{ $page }}</a>
                        </li>
                        @endforeach

                        @if ($products->hasMorePages())
                        <li><a href="{{ $products->nextPageUrl() }}">»</a></li>
                        @else
                        <li class="disabled"><span>»</span></li>
                        @endif
                    </ul>
                </div>
                <!-- /store bottom filter -->

                <!-- /store bottom filter -->
            </div>
            <!-- /STORE -->


        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

<!-- NEWSLETTER -->
<x-newsletter />
<!-- /NEWSLETTER -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        let minPrice = parseFloat("{{ request('min_price', $minPrice ?? 0) }}");
        let maxPrice = parseFloat("{{ request('max_price', $maxPrice ?? 10000) }}");

        // Устанавливаем значения в инпуты (на всякий случай)
        document.getElementById("price-min").value = minPrice;
        document.getElementById("price-max").value = maxPrice;

        // Если у тебя используется jQuery и UI Slider:
        if ($("#price-slider").length) {
            $("#price-slider").slider({
                range: true
                , min: 0
                , max: 1000
                , values: [minPrice, maxPrice], // Тут обновляем с сохраненными значениями
                slide: function(event, ui) {
                    $("#price-min").val(ui.values[0]);
                    $("#price-max").val(ui.values[1]);
                }
            });
        }
    });
</script>
@endsection