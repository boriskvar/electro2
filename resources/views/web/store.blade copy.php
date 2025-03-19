@extends('layouts.app')

@section('content')
<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- ASIDE -->
            {{-- <div id="aside" class="col-md-2">
                <!-- Categories Widget -->
                <div class="aside">
                    <h3 class="aside-title">Categories</h3>
                    <div class="checkbox-filter">
                        @foreach ($categories as $category)
                        <div class="input-checkbox">
                            <input type="checkbox" id="category-{{ $category->id }}">
            <label for="category-{{ $category->id }}">
                <span></span>
                {{ $category->name }}
                <small>({{ $category->products_count }})</small>
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
            <input id="price-min" type="number" value="{{ $minPrice ?? 0 }}">
            <span class="qty-up">+</span>
            <span class="qty-down">-</span>
        </div>
        <span>-</span>
        <div class="input-number price-max">
            <input id="price-max" type="number" value="{{ $maxPrice ?? 0 }}">
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
        @foreach ($brands as $brand)
        <div class="input-checkbox">
            <input type="checkbox" id="brand-{{ $brand->id }}">
            <label for="brand-{{ $brand->id }}">
                <span></span>
                {{ $brand->name }}
                <small>({{ $brand->products_count }})</small>
            </label>
        </div>
        @endforeach
    </div>
</div>
<!-- /Brands Widget -->

<!-- Top Selling Widget -->
<div class="aside">
    <h3 class="aside-title">Top selling</h3>
    @foreach ($topSellingProducts as $product)
    <div class="product-widget">
        <div class="product-img">
            <!-- Отображение изображения продукта -->
            <img id="alttext-image" src="{{ asset('storage/img/' . $product->images[0]) }}" style="float: left; margin: 0px;" alt="{{ $product->name }}">
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
</div>
<!-- /Top Selling Widget -->
</div> --}}
<!-- /ASIDE -->


<!-- STORE -->
<div id="app" class="col-md-9">

    <!-- store top filter -->
    <div class="store-filter clearfix">
        <div class="store-sort">
            <label>
                Sort By:
                <select class="input-select">
                    <option value="0">Popular</option>
                    <option value="1">Position</option>
                </select>
            </label>

            <label>
                Show:
                <select class="input-select">
                    <option value="0">20</option>
                    <option value="1">50</option>
                </select>
            </label>
        </div>
        <ul class="store-grid">
            <li class="active"><i class="fa fa-th"></i></li>
            <li>
                <a href="#"><i class="fa fa-th-list"></i></a>
            </li>
        </ul>
    </div>
    <!-- /store top filter -->

    <!-- store products -->
    @yield('page-content') <!-- Это место для динамического контента -->

    <!-- /store products -->

    <!-- store bottom filter -->
    <div class="store-filter clearfix">
        <span class="store-qty">Showing 20-100 products</span>
        <ul class="store-pagination">
            <li class="active">1</li>
            <li><a href="#">2</a></li>
            <li><a href="#">3</a></li>
            <li><a href="#">4</a></li>
            <li>
                <a href="#"><i class="fa fa-angle-right"></i></a>
            </li>
        </ul>
    </div>
    <!-- /store bottom filter -->
</div>
<!-- /STORE -->


</div>
<!-- /row -->
</div>
<!-- /container -->
</div>
<!-- /SECTION -->


@endsection