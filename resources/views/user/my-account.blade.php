@extends('layouts.main')

@section('content')
<div class="container">

    <div class="row">
        <!-- Сайдбар -->
        <div class="col-md-3">
            <div class="sidebar" {{-- style="width: 150px;" --}}>

                <a href="{{ route('my-account') }}" class="my-sidebar-item {{ $activePage === 'dashboard' ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('wishlist.index') }}" class="my-sidebar-item {{ $activePage === 'wishlist' ? 'active' : '' }}">My Wishlist</a>
                <a href="{{ route('cart.index') }}" class="my-sidebar-item {{ $activePage === 'cart' ? 'active' : '' }}">My Cart</a>
                <a href="{{ route('orders.index') }}" class="my-sidebar-item {{ $activePage === 'orders' ? 'active' : '' }}"> My Orders</a>
                <a href="{{ route('compare.index') }}" class="my-sidebar-item {{ $activePage === 'compare' ? 'active' : '' }}">Compare list</a>
                <a href="{{ route('products.index') }}" class="my-sidebar-item {{ $activePage === 'products' ? 'active' : '' }}">Viewed products</a>
                <a href="{{ route('reviews.index') }}" class="my-sidebar-item {{ $activePage === 'reviews' ? 'active' : '' }}">My Reviews</a>

            </div>
        </div>

        <!-- Контент -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">
                    @if ($activePage === 'dashboard')
                    @include('dashboard') {{-- Включаем Breeze dashboard.blade.php --}}

                    @elseif ($activePage === 'wishlist')
                    <h3>My Wishlist</h3>
                    <p>Список товаров, добавленных в список желаний.</p>

                    {{-- <div id="wishlist-products" data-products="{{ json_encode($products) }}">
                    <product :products="products" view-type="grid"></product>
                </div> --}}

                {{-- <div id="wishlist-products"> --}}
                {{-- <div id="wishlist-products" data-products="{{ $products->isNotEmpty() ? json_encode($products) : '[]' }}"> --}}
                <div id="wishlist-products" data-products="{{ json_encode($products->toArray()) }}">
                    @if ($products->isEmpty())
                    <p>Ваш список желаний пуст.</p>
                    @else
                    @foreach ($products as $product)
                    <div class="wishlist-item">
                        <form action="{{ route('wishlist.remove', $product->id) }}" method="POST" class="wishlist-remove-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="remove-wishlist-btn">✖</button>
                        </form>

                        <product :products="[{{ json_encode($product) }}]" view-type="grid"></product>
                    </div>
                    @endforeach
                    @endif
                </div>


                @elseif ($activePage === 'cart')
                <h3>My Cart</h3>
                <p>Список товаров, добавленных в корзину.</p>

                <div id="cart-products" data-products="{{ json_encode($products) }}">
                    <cart :cart-items="products"></cart>
                </div>

                @elseif ($activePage === 'orders')
                <h3>My Orders</h3>
                <p>История заказов.</p>

                @elseif ($activePage === 'compare')
                <h3>Compare list</h3>
                <p>Товары добавленные в список сравнения.</p>
                @include('user.compare.index')


                @elseif ($activePage === 'products')
                <h3>Viewed products</h3>
                <p>Просмотренные товары.</p>

                @elseif ($activePage === 'reviews')
                <h3>My reviews</h3>
                <p>Мои отзывы о товарах.</p>

                @endif
            </div>
        </div>
    </div>
</div>
</div>

<script>
    function removeFromWishlist(product) {
        fetch('/my-account/wishlist/remove', {
                method: 'DELETE'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                , }
                , body: JSON.stringify({
                    product_id: product.id
                })
            , })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Товар удален из Wishlist');
                    location.reload(); // Обновляем страницу
                } else {
                    alert('Ошибка: ' + data.message);
                }
            })
            .catch(error => {
                alert('Ошибка при удалении товара');
            });
    }
</script>

<style>
    /* Устанавливаем ширину сайдбара */
    .sidebar {
        /* width: 150px; */
        width: 100%;
        /* Установите нужную ширину */
        background-color: #f8f9fa;
        /* Цвет фона */
        padding: 15px;
        /* Отступы внутри сайдбара */
    }

    /* Стили для элементов в сайдбаре */
    .my-sidebar-item {
        display: block;
        /* Каждая ссылка будет на новой строке */
        padding: 10px 15px;
        /* Отступы для ссылок */
        text-decoration: none;
        /* Убираем подчеркивание */
        color: #333;
        /* Цвет текста */
        margin-bottom: 10px;
        /* Расстояние между ссылками */
        border-radius: 4px;
        /* Скругление углов */
    }

    /* Стили для активных ссылок */
    .my-sidebar-item.active {
        background-color: #007bff;
        /* Цвет фона для активной ссылки */
        color: white;
        /* Цвет текста активной ссылки */
    }

    /* При наведении на ссылку */
    .my-sidebar-item:hover {
        background-color: #e9ecef;
        /* Цвет фона при наведении */
    }

    /* для wishlist */
    .wishlist-item {
        position: relative;
        display: inline-block;
        margin-bottom: 20px;
        /* Отступ между товарами */
        text-align: center;
        /* Выравнивание содержимого по центру */
    }

    .wishlist-remove-form {
        position: absolute;
        top: -15px;
        /* Поднимаем крестик выше карточки */
        left: 50%;
        transform: translateX(-50%);
        /* Центрируем крестик */
    }

    .remove-wishlist-btn {
        background: red;
        color: white;
        border: none;
        font-size: 18px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
    }

    .remove-wishlist-btn:hover {
        background: darkred;
    }
</style>
@endsection