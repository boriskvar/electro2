@extends('layouts.main')

@section('content')
{{-- @dd($categories->toArray()); --}}

<!-- Спиннер загрузки -->
<div id="loading-spinner" class="loading-spinner" style="display: none;">
    <div class="spinner">
        <img src="{{ asset('css/ajax-loader.gif') }}" alt="Loading...">
    </div>
</div>

<!-- SECTION 1 -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">
            <!-- shop -->
            @foreach ($saleCategories->take(3) as $category)
            <div class="col-lg-4 col-md-4 col-sm-6">
                <div class="shop">
                    <div class="shop-img">
                        {{-- <img src="./img/shop01.png" alt=""> --}}
                        <!-- Если изображение есть, показываем его -->
                        @if (!empty($category->image))
                        <img src="{{ asset('storage/categories/' . $category->image) }}" alt="{{ $category->name }}">
                        @else
                        <span>Изображение не указано</span>
                        @endif
                    </div>
                    <div class="shop-body">
                        {{-- <h3>Laptop<br>Collection</h3> --}}
                        <h3>{{ $category->name }}<br>Collection</h3>
                        {{-- <a href="#" class="cta-btn">Shop now <i class="fa fa-arrow-circle-right"></i></a> --}}
                        {{-- <a href="{{ route('categories.show', $category->id) }}" class="cta-btn"> --}}
                        {{-- <a href="{{ route('store', ['category' => $category->id, 'discount' => 1]) }}" class="cta-btn"> --}}
                        {{-- <a href="{{ route('menus.page.show', ['slug' => 'hot-deals', 'page_slug' => 'hot-deals']) }}" class="cta-btn"> --}}
                        <a href="{{ $categoryHotDealLinks[$category->name] ?? '#' }}" class="cta-btn">
                            Shop now <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
            <!-- /shop -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION 1-->

<div id="app">
    <!-- SECTION 2-->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- ✅ НАЧАЛО обёртки Vue -->
                <!-- section title -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">New Products</h3>
                        <div class="section-nav">
                            <ul class="section-tab-nav tab-nav">
                                @foreach($categories as $category)
                                <li class="{{ $loop->first ? 'active' : '' }}">
                                    <a href="#tab{{ $category->id }}" data-toggle="tab">{{ $category->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /section title -->

                <!-- Products tab & slick -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="products-tabs">
                            <!-- Отображаем вкладки для каждой категории -->
                            @foreach($categories as $category)
                            <!-- tab -->
                            <div id="tab{{ $category->id }}" class="tab-pane {{ $loop->first ? 'active' : '' }}">
                                <div class="products-slick" data-nav="#slick-nav-{{ $category->id }}">
                                    {{-- @foreach($products->where('category_id', $category->id) as $product) --}}
                                    {{-- @foreach($productsByCategory[$category->name] ?? [] as $product) --}}
                                    @foreach($newProducts->where('category_id', $category->id) as $product)
                                    <!-- product -->
                                    <div class="product">
                                        <div class="product-img">
                                            <img src="{{ asset('storage/img/' . $product['images'][0]) }}" alt="{{ $product['name'] }}">
                                            <div class="product-label">
                                                @if($product->discount_percentage)
                                                <span class="sale">-{{ round($product->discount_percentage) }}%</span>
                                                @endif
                                                @if($product->is_new)
                                                <span class="new">NEW</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->category->name }}</p>
                                            <h3 class="product-name"><a href="{{ url('/product/' . $product->id) }}">{{ $product->name }}</a></h3>
                                            <h4 class="product-price">${{ $product->price }}
                                                @if($product->old_price)
                                                <del class="product-old-price">${{ $product->old_price }}</del>
                                                @endif
                                            </h4>
                                            <div class="product-rating">
                                                @php $rating = round($product->rating); @endphp
                                                @for ($i = 1; $i <= 5; $i++) @if ($i <=$rating) <i class="fa fa-star"></i>
                                                    @else
                                                    <i class="fa fa-star-o"></i>
                                                    @endif
                                                    @endfor
                                            </div>
                                            <div class="product-btns">
                                                <button class="add-to-wishlist" onclick="addToWishlist({{ $product->id }})"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
                                                <button class="add-to-compare" onclick="addToCompare({{ $product->id }})"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
                                                <button class="quick-view" @click="$refs.quickModal.quickView({{ $product->id }})"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
                                            </div>


                                        </div>
                                        <div class="add-to-cart">
                                            <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})">
                                                <i class="fa fa-shopping-cart"></i> add to cart
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /product -->
                                    @endforeach
                                </div>
                                <!-- Уникальный nav для каждой категории -->
                                <div id="slick-nav-{{ $category->id }}" class="products-slick-nav"></div>
                            </div>
                            @endforeach
                            <!-- /tab -->
                        </div>
                    </div>

                    <!-- ✅ ВСТАВЛЯЕМ МОДАЛКУ -->
                    {{-- <quick-view-modal ref="quickModal"></quick-view-modal> --}}

                </div>
                <!-- ✅ КОНЕЦ обёртки Vue -->
                <!-- Products tab & slick -->

            </div>
            <!-- /row -->

        </div>
        <!-- /container -->

    </div>
    <!-- /SECTION  2-->

    <!-- HOT DEAL SECTION -->
    <div id="hot-deal" class="section" style="background-image: url('{{ asset('storage/img/hotdeal.png') }}'); background-position: center; background-repeat: no-repeat; background-size: cover;">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="hot-deal">
                        <ul class="hot-deal-countdown" id="hot-deal-timer">
                            <li>
                                <div>
                                    <h3 id="days">00</h3>
                                    <span>Days</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3 id="hours">00</h3>
                                    <span>Hours</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3 id="minutes">00</h3>
                                    <span>Mins</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3 id="seconds">00</h3>
                                    <span>Secs</span>
                                </div>
                            </li>
                        </ul>
                        <h2 class="text-uppercase">Hot Deal This Week</h2>
                        <p>New Collection Up to 50% OFF</p>
                        <a class="primary-btn cta-btn" href=" {{ route('menus.page.show', ['slug' => 'hot-deals', 'page_slug' => 'hot-deals']) }} ">Shop now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /HOT DEAL SECTION -->

    <!-- SECTION 3 -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">

                <!-- section title -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Top Selling</h3>
                        <div class="section-nav">
                            <ul class="section-tab-nav tab-nav">
                                @foreach($categories as $category)
                                <li class="{{ $loop->first ? 'active' : '' }}">
                                    <a href="#tab-top-{{ $category->id }}" data-toggle="tab">{{ $category->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /section title -->

                <!-- Products tab & slick -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="products-tabs">
                            <!-- Вкладки для каждой категории -->
                            @foreach($categories as $category)
                            <div id="tab-top-{{ $category->id }}" class="tab-pane {{ $loop->first ? 'active' : '' }}">
                                <div class="products-slick" data-nav="#slick-nav-top-{{ $category->id }}">
                                    {{-- @foreach($products->where('category_id', $category->id)->where('is_top_selling', 1) as $product) --}}
                                    {{-- @foreach($productsByCategory[$category->name]->where('is_top_selling', 1) ?? [] as $product) --}}
                                    {{-- @foreach($productsByCategory[$category->name] ?? []->where('is_top_selling', 1) as $product) --}}
                                    {{-- @foreach(collect($productsByCategory[$category->name] ?? [])->where('is_top_selling', 1) as $product) --}}
                                    @foreach($topSellingProducts->where('category_id', $category->id) as $product)
                                    <!-- product -->
                                    <div class="product">
                                        <div class="product-img">
                                            <img src="{{ asset('storage/img/' . $product['images'][0]) }}" alt="{{ $product['name'] }}">
                                            <div class="product-label">
                                                @if($product->discount_percentage)
                                                <span class="sale">-{{ round($product->discount_percentage) }}%</span>
                                                @endif
                                                @if($product->is_new)
                                                <span class="new">NEW</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->category->name }}</p>
                                            <h3 class="product-name"><a href="{{ url('/product/' . $product->id) }}">{{ $product->name }}</a></h3>
                                            <h4 class="product-price">${{ $product->price }}
                                                @if($product->old_price)
                                                <del class="product-old-price">${{ $product->old_price }}</del>
                                                @endif
                                            </h4>
                                            <div class="product-rating">
                                                @php $rating = round($product->rating); @endphp
                                                @for ($i = 1; $i <= 5; $i++) @if ($i <=$rating) <i class="fa fa-star"></i>
                                                    @else
                                                    <i class="fa fa-star-o"></i>
                                                    @endif
                                                    @endfor
                                            </div>
                                            <div class="product-btns">
                                                <button class="add-to-wishlist" onclick="addToWishlist({{ $product->id }})"><i class="fa fa-heart-o"></i><span class="tooltipp">add to wishlist</span></button>
                                                <button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to compare</span></button>
                                                {{-- <button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button> --}}
                                                <button class="quick-view" @click="$refs.quickModal.quickView({{ $product->id }})"><i class="fa fa-eye"></i><span class="tooltipp">quick view</span></button>
                                            </div>
                                        </div>
                                        <div class="add-to-cart">
                                            {{-- <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button> --}}
                                            <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})">
                                                <i class="fa fa-shopping-cart"></i> add to cart
                                            </button>
                                        </div>
                                    </div>
                                    <!-- /product -->
                                    @endforeach
                                </div>
                                <!-- Уникальный навигатор для каждой категории -->
                                <div id="slick-nav-top-{{ $category->id }}" class="products-slick-nav"></div>
                            </div>
                            @endforeach
                            <!-- /tab -->
                        </div>
                    </div>

                    <!-- ✅ ВСТАВЛЯЕМ МОДАЛКУ -->
                    <quick-view-modal ref="quickModal"></quick-view-modal>

                </div>
                <!-- /Products tab & slick -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION 3 -->
</div>

<!-- SECTION 4 -->
<div class="container">
    <div class="row">
        <!-- Слайдер товаров из категории Laprops -->
        <div class="col-md-4 col-sm-6">
            <div class="section-title">
                <h4 class="title">Top selling</h4>
                <div class="section-nav">
                    <div id="slick-nav-laprops" class="products-slick-nav"></div>
                </div>
            </div>

            <div class="products-widget-slick" data-nav="#slick-nav-laprops">
                @foreach($laptopsProducts->chunk(3) as $chunk)
                <div>
                    @foreach($chunk as $product)
                    <div class="product-widget">
                        <div class="product-img">
                            {{-- <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
                            --}}
                            <img src="{{ asset('storage/img/' . $product['images'][0]) }}" alt="{{ $product['name'] }}">
                        </div>
                        <div class="product-body">
                            <p class="product-category">{{ $product->category->name }}</p>
                            <h3 class="product-name"><a href="{{ url('/product/' . $product->id) }}">{{ $product->name }}</a></h3>
                            <h4 class="product-price">
                                ${{ number_format($product->price, 2) }}
                                @if($product->old_price)
                                <del class="product-old-price">${{ number_format($product->old_price, 2) }}</del>
                                @endif
                            </h4>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>

        <!-- Слайдер товаров из категории Cmartphones -->
        <div class="col-md-4 col-sm-6">
            <div class="section-title">
                <h4 class="title">Top selling</h4>
                <div class="section-nav">
                    <div id="slick-nav-cmartphones" class="products-slick-nav"></div>
                </div>
            </div>

            <div class="products-widget-slick" data-nav="#slick-nav-cmartphones">
                @foreach($smartphonesProducts->chunk(3) as $chunk)
                <div>
                    @foreach($chunk as $product)
                    <div class="product-widget">
                        <div class="product-img">
                            {{-- <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
                            --}}
                            <img src="{{ asset('storage/img/' . $product['images'][0]) }}" alt="{{ $product['name'] }}">
                        </div>
                        <div class="product-body">
                            <p class="product-category">{{ $product->category->name }}</p>
                            <h3 class="product-name"><a href="{{ url('/product/' . $product->id) }}">{{ $product->name }}</a></h3>
                            <h4 class="product-price">
                                ${{ number_format($product->price, 2) }}
                                @if($product->old_price)
                                <del class="product-old-price">${{ number_format($product->old_price, 2) }}</del>
                                @endif
                            </h4>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>

        <!-- Слайдер товаров из категории Accessories -->
        <div class="col-md-4 col-sm-6">
            <div class="section-title">
                <h4 class="title">Top selling</h4>
                <div class="section-nav">
                    <div id="slick-nav-accessories" class="products-slick-nav"></div>
                </div>
            </div>

            <div class="products-widget-slick" data-nav="#slick-nav-accessories">
                @foreach($accessoriesProducts->chunk(3) as $chunk)
                <div>
                    @foreach($chunk as $product)
                    <div class="product-widget">
                        <div class="product-img">
                            {{-- <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}">
                            --}}
                            <img src="{{ asset('storage/img/' . $product['images'][0]) }}" alt="{{ $product['name'] }}">
                        </div>
                        <div class="product-body">
                            <p class="product-category">{{ $product->category->name }}</p>
                            <h3 class="product-name"><a href="{{ url('/product/' . $product->id) }}">{{ $product->name }}</a></h3>
                            <h4 class="product-price">
                                ${{ number_format($product->price, 2) }}
                                @if($product->old_price)
                                <del class="product-old-price">${{ number_format($product->old_price, 2) }}</del>
                                @endif
                            </h4>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- /SECTION 4 -->

<!-- NEWSLETTER -->
<x-newsletter />
<!-- /NEWSLETTER -->

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // === Таймер распродажи ===
        let countDownDate = new Date();
        countDownDate.setDate(countDownDate.getDate() + 2); // 2 дня до конца акции

        function updateTimer() {
            let now = new Date().getTime();
            let distance = countDownDate - now;

            if (distance < 0) {
                document.getElementById("hot-deal-timer").innerHTML = "<h3>Deal Expired</h3>";
                return;
            }

            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            let seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById("days").textContent = days;
            document.getElementById("hours").textContent = hours;
            document.getElementById("minutes").textContent = minutes;
            document.getElementById("seconds").textContent = seconds;
        }

        setInterval(updateTimer, 100);
        updateTimer();

        // === Функция добавления в корзину ===
        window.addToCart = function(productId) {
            console.log("Добавление в корзину:", productId);

            fetch('/cart/add', {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                    , body: JSON.stringify({
                        product_id: productId
                        , quantity: 1
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert("Товар добавлен в корзину!");
                    // Вызываем обновление корзины в хедере
                    window.dispatchEvent(new Event('updateCart'));
                    // Перезагружаем страницу, чтобы обновить корзину
                    setTimeout(() => location.reload(), 1000);
                })
                .catch(error => {
                    console.error("Ошибка добавления в корзину:", error);
                    showToast("Ошибка при добавлении товара", "error");
                });
        };
        // === Функция добавления в wishlist ===
        window.addToWishlist = function(productId) {
            console.log("Добавление в wishlist:", productId);

            fetch('/my-account/wishlist/store', {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                    , body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Товар добавлен в список желаемого!");
                        // Меняем иконку сердечка и блокируем кнопку
                        let btn = document.querySelector(`button[onclick="addToWishlist(${productId})"]`);
                        if (btn) {
                            btn.innerHTML = '<i class="fa fa-heart"></i><span class="tooltipp">added</span>';
                            btn.disabled = true; // Отключаем кнопку после добавления
                        }
                    } else {
                        alert("Ошибка при добавлении в Wishlist");
                    }
                })
                .catch(error => {
                    console.error("Ошибка добавления в wishlist:", error);
                    alert("Ошибка при добавлении товара в Wishlist");
                });
        };

        // === Функция добавления в сравнение ===
        window.addToCompare = function(productId) {
            console.log("Добавление в сравнение:", productId);

            fetch('/my-account/compare/add', {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                    , body: JSON.stringify({
                        product_id: productId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Товар добавлен в сравнение!");
                        // Меняем иконку и блокируем кнопку
                        let btn = document.querySelector(`button[onclick="addToCompare(${productId})"]`);
                        if (btn) {
                            btn.innerHTML = '<i class="fa fa-exchange"></i><span class="tooltipp">added</span>';
                            btn.disabled = true; // Отключаем кнопку
                        }
                    } else {
                        alert("Ошибка при добавлении в сравнение");
                    }
                })
                .catch(error => {
                    console.error("Ошибка добавления в сравнение:", error);
                    alert("Ошибка при добавлении товара в сравнение");
                });
        };


    });
</script>


<style>
    .loading-spinner {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
</style>


@endsection