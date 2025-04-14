@extends('layouts.main')

@section('content')
<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class=" row">
            <!-- Product main img -->
            <div class="col-md-5 col-md-push-2">
                <div id="product-main-img">
                    @foreach ($allImages as $image)
                    <div class="product-preview">
                        <img src="{{ asset('storage/img/' . $image) }}" alt="Product" loading="lazy">
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- /Product main img -->

            <!-- Product thumb imgs -->
            <div class="col-md-2  col-md-pull-5">
                <div id="product-imgs">
                    @if (!empty($allImages))
                    @foreach ($allImages as $image)
                    <div class="product-preview">
                        <img src="{{ asset('storage/img/' . $image) }}" alt="Product Thumbnail" loading="lazy">
                    </div>
                    @endforeach
                    @else
                    <p>No images available</p>
                    @endif
                </div>
            </div>
            <!-- /Product thumb imgs -->

            {{-- Добавь компонент здесь, где тебе нужно вывести иконки --}}
            {{-- <x-product-share :productSocialLinks="$productSocialLinks" /> --}}

            <!-- Product details -->
            <div class="col-md-5">
                <div class="product-details">
                    {{-- <h2 class="product-name">product name goes here</h2> --}}
                    <h2 class="product-name">{{ $product->name }}</h2>
                    <div>
                        <!-- Рейтинг -->
                        <div class="product-rating">
                            @php
                            $rating = $product->rating; // Рейтинг из базы данных
                            $maxRating = 5; // Максимальный рейтинг
                            @endphp
                            @for ($i = 1; $i <= $maxRating; $i++) @if ($i <=$rating) <i class="fa fa-star"></i> <!-- Заполненная звезда -->
                                @else
                                <i class="fa fa-star-o"></i> <!-- Пустая звезда -->
                                @endif
                                @endfor
                        </div>
                        <a class="review-link" href="#">{{ $product->reviews_count }} Review(s) | Add your review</a>
                    </div>

                    <div>
                        <!-- Цена -->
                        {{-- <h3 class="product-price">$980.00 <del class="product-old-price">$990.00</del></h3> --}}
                        <h3 class="product-price">
                            ${{ $product->price }}
                            @if ($product->old_price)
                            <del class="product-old-price">${{ $product->old_price }}</del>
                            @endif
                        </h3>

                        {{-- <span class="product-available">In Stock</span> --}}
                        <span class="product-available">
                            {{ $product->in_stock ? 'есть в наличии' : 'Out of Stock' }}
                        </span>
                    </div>

                    <!-- Описание -->
                    <p>{{ $product->description }}</p>

                    <!-- Опции -->
                    <div class="product-options">
                        <label>
                            Size
                            {{-- <select class="input-select"><option value="0">X</option></select> --}}
                            <select class="input-select">
                                @foreach ($product->sizes as $size)
                                <option value="{{ $size }}">{{ $size }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label>
                            Color
                            {{-- <select class="input-select"><option value="0">Red</option></select> --}}
                            <select class="input-select">
                                @foreach ($product->colors as $color)
                                <option value="{{ $color }}">{{ ucfirst($color) }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>

                    <!-- Добавить в корзину -->
                    <div class="add-to-cart">
                        <div class="qty-label">
                            Qty
                            <div class="input-number">
                                <input type="number" min="1" value="1">
                                <span class="qty-up">+</span>
                                <span class="qty-down">-</span>
                            </div>
                        </div>

                        <button class="add-to-cart-btn" onclick="addToCart({{ $product->id }})">
                            <i class="fa fa-shopping-cart"></i> add to cart
                        </button>
                    </div>

                    <!-- Дополнительные кнопки -->
                    <ul class="product-btns">
                        {{-- <li><a href="#"><i class="fa fa-heart-o"></i> add to wishlist</a></li> --}}
                        <li><a href="#" class="add-to-wishlist" data-id="{{ $product->id }}"><i class="fa fa-heart-o"></i> add to wishlist</a></li>
                        {{-- <li><a href="#"><i class="fa fa-exchange"></i> add to compare</a></li> --}}
                        <li><a href="#" class="add-to-compare" data-id="{{ $product->id }}"><i class="fa fa-exchange"></i> add to compare</a></li>
                    </ul>

                    <!-- Категории -->
                    <ul class="product-links">
                        <li>Category:</li>
                        {{-- <li><a href="#">Headphones</a></li> --}}
                        {{-- <li><a href="#">Accessories</a></li> --}}
                        {{-- <pre>{{ dd($product->category) }}</pre> --}}
                        {{-- <pre>{{ dd($product->category->menu) }}</pre> --}}
                        @if ($product->category && $product->category->menus->first())
                        @php $menu = $product->category->menus->first(); @endphp
                        <li>
                            {{-- <a href="https://electro2.local/menus/laptops/category/laptops">{{ $product->category->name }}</a> --}}
                            <a href="{{ route('menus.category.show', [
                                'slug' => $menu->slug, 
                                'category_slug' => $product->category->slug
                                ]) }}">
                                {{ $product->category->name }}
                            </a>
                        </li>
                        @else
                        <li>No category assigned</li>
                        @endif
                    </ul>

                    <!-- Поделиться -->
                    {{-- <ul class="product-links">
                        <li>Share:</li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                    </ul> --}}
                    @if($productSocialLinks ->count())
                    <ul class="product-links">
                        <li>Share:</li>
                        @foreach($productSocialLinks as $link)
                        <li>
                            <a href="{{ $link->url }}" @if($link->open_in_new_tab) target="_blank" @endif
                                title="{{ $link->name }}">
                                <i class="{{ $link->icon_class }}"></i>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                    @endif

                </div>
            </div>
            <!-- /Product details -->

            <!-- Product tab -->
            <div class="col-md-12">
                <div id="product-tab">
                    <!-- product tab nav -->
                    <ul class="tab-nav">
                        <li class="active"><a data-toggle="tab" href="#tab1">Description</a></li>
                        <li><a data-toggle="tab" href="#tab2">Details</a></li>
                        <li><a data-toggle="tab" href="#tab3">Reviews (3)</a></li>
                    </ul>
                    <!-- /product tab nav -->

                    <!-- product tab content -->
                    <div class="tab-content">
                        <!-- tab1: краткое Описание  -->
                        <div id="tab1" class="tab-pane fade in active">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>{{ $product->description ?? 'краткое Описание недоступно.' }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- /tab1  -->

                        <!-- tab2: Характеристики  -->
                        <div id="tab2" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-12">
                                    <p>{{ $product->details ?? 'Информация о продукте отсутствует.' }}</p>
                                </div>
                            </div>
                        </div>
                        <!-- /tab2  -->

                        <!-- tab3: Отзывы -->
                        <div id="tab3" class="tab-pane fade in">
                            @php
                            $totalReviews = $product->reviews_count; // Общее количество отзывов
                            $reviewsPerPage = 5; // Количество отзывов на странице
                            $totalPages = ceil($totalReviews / $reviewsPerPage); // Количество страниц
                            $currentPage = request()->get('page', 1); // Текущая страница, по умолчанию - 1
                            $start = ($currentPage - 1) * $reviewsPerPage; // Смещение для выборки отзывов
                            $reviews = $product->reviews->slice($start, $reviewsPerPage); // Выбираем отзывы для текущей страницы
                            @endphp

                            <div class="row">
                                <!-- Rating -->
                                <div class="col-md-3">
                                    <div id="rating">
                                        <div class="rating-avg">
                                            <span>{{ $product->rating }}</span> <!-- Средний рейтинг товара -->
                                            <div class="rating-stars">
                                                @for ($i = 1; $i <= 5; $i++) <i class="fa fa-star{{ $i <= $product->rating ? '' : '-o' }}"></i>
                                                    @endfor
                                            </div>
                                        </div>
                                        <ul class="rating">
                                            @foreach ([5, 4, 3, 2, 1] as $star)
                                            <li>
                                                <div class="rating-stars">
                                                    @for ($i = 1; $i <= 5; $i++) <i class="fa fa-star{{ $i <= $star ? '' : '-o' }}"></i>
                                                        @endfor
                                                </div>
                                                <div class="rating-progress">
                                                    <div style="width: {{ $product->reviews->count() > 0 ? ($product->reviews->where('rating', $star)->count() / $product->reviews->count()) * 100 : 0 }}%;">
                                                    </div>
                                                </div>
                                                <span class="sum">{{ $product->reviews->where('rating', $star)->count() }}</span>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <!-- /Rating -->

                                <!-- Reviews -->
                                <div class="col-md-6">
                                    <div id="reviews">
                                        <ul class="reviews">
                                            @foreach ($reviews as $review)
                                            <li>
                                                <div class="review-heading">
                                                    <h5 class="name">{{ $review->user->name }}</h5>
                                                    <p class="date">{{ $review->created_at->format('d M Y, H:i') }}</p>
                                                    <div class="review-rating">
                                                        @for ($i = 1; $i <= 5; $i++) <i class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                            @endfor
                                                    </div>
                                                </div>
                                                <div class="review-body">
                                                    <p>{{ $review->content }}</p>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <ul class="reviews-pagination">
                                            @for ($i = 1; $i <= $totalPages; $i++) <li class="{{ $currentPage == $i ? 'active' : '' }}">
                                                <a href="?page={{ $i }}">{{ $i }}</a>
                                                </li>
                                                @endfor
                                        </ul>
                                    </div>
                                </div>
                                <!-- /Reviews -->

                                <!-- Review Form -->
                                <div class="col-md-3">
                                    <div id="review-form">
                                        <form class="review-form">
                                            <input class="input" type="text" placeholder="Your Name">
                                            <input class="input" type="email" placeholder="Your Email">
                                            <textarea class="input" placeholder="Your Review"></textarea>
                                            <div class="input-rating">
                                                <span>Your Rating: </span>
                                                <div class="stars">
                                                    <input id="star5" name="rating" value="5" type="radio"><label for="star5"></label>
                                                    <input id="star4" name="rating" value="4" type="radio"><label for="star4"></label>
                                                    <input id="star3" name="rating" value="3" type="radio"><label for="star3"></label>
                                                    <input id="star2" name="rating" value="2" type="radio"><label for="star2"></label>
                                                    <input id="star1" name="rating" value="1" type="radio"><label for="star1"></label>
                                                </div>
                                            </div>
                                            <button class="primary-btn">Submit</button>
                                        </form>
                                    </div>
                                </div>
                                <!-- /Review Form -->
                            </div>
                        </div>
                        <!-- /tab3 -->


                    </div>
                    <!-- /product tab content  -->
                </div>
            </div>
            <!-- /product tab -->
        </div>
        <!-- /row -->
    </div>
    <!-- /container -->
</div>
<!-- /SECTION -->

<!-- Section -->
<div id="app" class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <div class="col-md-12">
                <div class="section-title text-center">
                    <h3 class="title">Related Products</h3>
                </div>
            </div>

        </div>
        <!-- /row -->

        <!-- Vue-компонент -->
        <div class="row">
            @foreach ($relatedProducts as $relatedProduct)
            <div class="col-md-3 col-xs-6">
                <div class="product">
                    <div class="product-img">
                        @php
                        $firstImage = $relatedProduct->images[0] ?? null;
                        @endphp
                        <img src="{{ $firstImage ? asset('storage/img/' . $firstImage) : asset('storage/img/default.png') }}" alt="{{ $relatedProduct->name }}" width="40" height="40" />

                        <div class="product-label">
                            @if ($relatedProduct->discount_percentage)
                            <span class="sale">-{{ round($relatedProduct->discount_percentage) }}%</span>
                            @endif
                            @if ($relatedProduct->is_new)
                            <span class="new">NEW</span>
                            @endif
                        </div>
                    </div>
                    <div class="product-body">
                        <p class="product-category">{{ $relatedProduct->category->name }}</p>
                        <h3 class="product-name">
                            <a href="{{ route('products.show', $relatedProduct->id) }}">
                                {{ $relatedProduct->name }}
                            </a>
                        </h3>
                        <h4 class="product-price">
                            ${{ number_format($relatedProduct->price, 2) }}
                            @if ($relatedProduct->oldPrice)
                            <del class="product-old-price">
                                ${{ number_format($relatedProduct->oldPrice, 2) }}
                            </del>
                            @endif
                        </h4>
                        <div class="product-rating">
                            @for ($i = 1; $i <= 5; $i++) <i class="fa {{ $i <= round($relatedProduct->rating) ? 'fa-star' : 'fa-star-o' }}"></i>
                                @endfor
                        </div>
                        <div class="product-btns">
                            <button class="add-to-wishlist" data-id="{{ $relatedProduct->id }}">
                                <i class="fa fa-heart-o"></i>
                                <span class="tooltipp">add to wishlist</span>
                            </button>
                            <button class="add-to-compare" data-id="{{ $relatedProduct->id }}">
                                <i class="fa fa-exchange"></i>
                                <span class="tooltipp">add to compare</span>
                            </button>
                            {{-- <button class="quick-view" data-id="{{ $relatedProduct->id }}"> --}}
                            <button class="quick-view" @click="$refs.quickModal.quickView({{ $relatedProduct->id }})">
                                <i class="fa fa-eye"></i>
                                <span class="tooltipp">quick view</span>
                            </button>
                        </div>
                    </div>
                    <div class="add-to-cart">
                        <button class="add-to-cart-btn" onclick="addToCart({{ $relatedProduct->id }})">
                            <i class="fa fa-shopping-cart"></i> add to cart
                        </button>
                    </div>
                </div>
            </div>

            @endforeach
        </div>


    </div>
    <!-- ✅ ВСТАВЛЯЕМ МОДАЛКУ -->
    <quick-view-modal ref="quickModal"></quick-view-modal>
</div>

<!-- /container -->
</div>
<!-- /Section -->


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // === Функция добавления в wishlist ===
        document.querySelectorAll('.add-to-wishlist').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const productId = this.dataset.id;
                const button = this; // 👈 сохраняем контекст

                fetch('/my-account/wishlist/store', {
                        method: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            , 'Content-Type': 'application/json'
                            , 'Accept': 'application/json'
                        }
                        , body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || "Товар добавлен в список желаний!"); // или показать иконку, или изменить стиль
                            // ✅ Меняем иконку/текст
                            button.innerHTML = '<i class="fa fa-heart-o"></i> added';
                            // ✅ Добавляем класс "disabled" — можно применить стиль в CSS
                            button.classList.add('disabled'); // если хочешь заблокировать повторный клик
                        } else {
                            alert('Ошибка: ' + (data.message || 'Не удалось добавить в Wishlist'));
                        }
                    })
                    .catch(error => {
                        console.error('Ошибка при добавлении в wishlist:', error);
                        alert('Произошла ошибка');
                    });
            });
        });


        // === Функция добавления в сравнение ===
        document.querySelectorAll('.add-to-compare').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();

                const productId = this.dataset.id;

                fetch('/my-account/compare/add', {
                        method: 'POST'
                        , headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            , 'Content-Type': 'application/json'
                            , 'Accept': 'application/json'
                        }
                        , body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message || "Товар добавлен в сравнение!");
                            // Тут можно изменить стиль кнопки, если хочешь
                            // Например:
                            this.innerHTML = '<i class="fa fa-exchange"></i> added';
                            this.classList.add('disabled'); // если хочешь заблокировать повторный клик
                        } else {
                            alert("Ошибка: " + (data.message || "Не удалось добавить в сравнение"));
                        }
                    })
                    .catch(error => {
                        console.error("Ошибка при добавлении в сравнение:", error);
                        alert("Произошла ошибка");
                    });
            });
        });


        // === Функция добавления в корзину ===
        window.addToCart = function(productId) {
            console.log("Добавление в корзину:", productId);

            // Находим поле с количеством в текущем блоке
            let inputField = document.querySelector('.input-number input');
            let quantity = inputField ? parseInt(inputField.value) : 1; // Если поле найдено, берем значение, иначе 1

            // Проверяем, чтобы количество было корректным
            if (isNaN(quantity) || quantity < 1) {
                quantity = 1;
            }

            fetch('/cart/add', {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                    , body: JSON.stringify({
                        product_id: productId
                        , quantity: quantity // Передаем реальное количество
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.dispatchEvent(new Event('updateCart')); // Обновляем корзину
                        setTimeout(() => location.reload(), 100); // Обновляем страницу
                    } else {
                        showToast(data.message, "error");
                    }
                })
                .catch(error => {
                    console.error("Ошибка добавления в корзину:", error);
                    showToast("Ошибка при добавлении товара", "error");
                });
        };
    });
</script>

@endsection