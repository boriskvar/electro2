@extends('layouts.main')

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h3 class="title">Список желаемого</h3>
                </div>
            </div>
        </div>

        <div class="row">
            @if($wishlists->isEmpty())
            <p class="text-center">В вашем списке желаемого нет товаров.</p>
            @else
            @foreach ($wishlists as $wishlistItem)
            <div class="col-md-3 col-xs-6">
                <div class="product">

                    <div class="product-btns">
                        <button class="remove-from-wishlist" onclick="removeFromWishlist({{ $wishlistItem->product->id }})">
                            <i class="fa fa-trash"></i>
                            <span class="tooltipp">Удалить из сравнения</span>
                        </button>
                    </div>

                    <div class="product-img">
                        @php
                        $firstImage = $wishlistItem->product->images[0] ?? null;
                        @endphp
                        <img src="{{ $firstImage ? asset('storage/img/' . $firstImage) : asset('storage/img/default.png') }}" alt="{{ $wishlistItem->product->name }}" width="40" height="40" />

                        <div class="product-label">
                            @if ($wishlistItem->product->discount_percentage)
                            <span class="sale">-{{ round($wishlistItem->product->discount_percentage) }}%</span>
                            @endif
                            @if ($wishlistItem->product->is_new)
                            <span class="new">NEW</span>
                            @endif
                        </div>
                    </div>
                    <div class="product-body">
                        <p class="product-category">{{ $wishlistItem->product->category->name }}</p>
                        <h3 class="product-name">
                            <a href="{{ route('products.show', $wishlistItem->product->id) }}">
                                {{ $wishlistItem->product->name }}
                            </a>
                        </h3>
                        <h4 class="product-price">
                            ${{ number_format($wishlistItem->product->price, 2) }}
                            @if ($wishlistItem->product->old_price)
                            <del class="product-old-price">
                                ${{ number_format($wishlistItem->product->old_price, 2) }}
                            </del>
                            @endif
                        </h4>
                        <div class="product-rating">
                            @for ($i = 1; $i <= 5; $i++) <i class="fa {{ $i <= round($wishlistItem->product->rating) ? 'fa-star' : 'fa-star-o' }}"></i>
                                @endfor
                        </div>

                    </div>
                    <div class="add-to-cart">
                        <button class="add-to-cart-btn" onclick="addToCart({{ $wishlistItem->product->id }})">
                            <i class="fa fa-shopping-cart"></i> Добавить в корзину
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // === Функция добавления в корзину ===
        window.addToCart = function(productId) {
            console.log("Добавление в корзину:", productId);

            let quantity = 1; // По умолчанию 1

            fetch('/cart/add', {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                    , body: JSON.stringify({
                        product_id: productId
                        , quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.dispatchEvent(new Event('updateCart'));
                        setTimeout(() => location.reload(), 100); // Перезагружаем страницу
                    } else {
                        alert("Ошибка: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Ошибка добавления в корзину:", error);
                    alert("Ошибка при добавлении товара");
                });
        };

        // === Функция удаления из wishlist ===
        window.removeFromWishlist = function(wishlistId) {
            if (!confirm("Вы уверены, что хотите удалить этот товар из Wishlist?")) {
                return; // Если пользователь нажал "Отмена", ничего не делаем
            }
            console.log("Удаление из wishlist:", wishlistId);

            fetch(`/my-account/wishlist/${wishlistId}`, {
                    method: 'DELETE'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        setTimeout(() => location.reload(), 100); // Перезагружаем страницу
                    } else {
                        alert("Ошибка: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Ошибка удаления из wishlist:", error);
                    alert("Ошибка при удалении товара");
                });
        };
    });
</script>

@endsection