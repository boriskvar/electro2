@extends('layouts.main')

@section('content')
<div class="container">

    <div class="row">
        <!-- Сайдбар -->
        <div class="col-md-3">
            <div class="sidebar" {{-- style="border: 1px solid red; "  --}}{{-- style="width: 150px;" --}}>

                <a href="{{ route('my-account') }}" class="my-sidebar-item {{ $activePage === 'dashboard' ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('wishlist.index') }}" class="my-sidebar-item {{ $activePage === 'wishlist' ? 'active' : '' }}">My Wishlist</a>
                <a href="{{ route('compare.index') }}" class="my-sidebar-item {{ $activePage === 'compare' ? 'active' : '' }}">Compare list</a>
                <a href="{{ route('cart.index') }}" class="my-sidebar-item {{ $activePage === 'cart' ? 'active' : '' }}">My Cart</a>

                <a href="{{ route('orders.index') }}" class="my-sidebar-item {{ $activePage === 'orders' ? 'active' : '' }}"> My Orders</a>
                <a href="{{ route('products.index') }}" class="my-sidebar-item {{ $activePage === 'products' ? 'active' : '' }}">Viewed products</a>
                <a href="{{ route('reviews.index') }}" class="my-sidebar-item {{ $activePage === 'reviews' ? 'active' : '' }}">My Reviews</a>

            </div>
        </div>

        <!-- Контент -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-body">

                    @if ($activePage === 'dashboard')
                    @include('dashboard')

                    @elseif ($activePage === 'wishlist')
                    @include('user.wishlist.index')

                    @elseif ($activePage === 'cart')
                    <h3>My Cart</h3>
                    <p>Список товаров, добавленных в корзину.</p>
                    @include('user.cart.index')
                    {{-- <div id="cart-products" data-products="{{ json_encode($products) }}">
                    <cart :cart-items="products"></cart>
                </div> --}}

                @elseif ($activePage === 'orders')
                <h3>My Orders</h3>
                <p>История заказов.</p>

                @elseif ($activePage === 'compare')
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

<style>
    /* Устанавливаем ширину сайдбара */
    .sidebar {
        width: 150px;
        /* width: 100%; */
        /* Установите нужную ширину */
        /* background-color: #f8f9fa; */
        /* Цвет фона */
        /* padding: 15px; */
        /* Отступы внутри сайдбара */
    }

    /* Стили для элементов в сайдбаре */
    .my-sidebar-item {
        display: block;
        /* Каждая ссылка будет на новой строке */
        padding: 5px 5px;
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
        /* background-color: #007bff; */
        background-color: #D10024;
        /* Цвет фона для активной ссылки */
        color: white;
        /* Цвет текста активной ссылки */
    }

    /* При наведении на ссылку */
    .my-sidebar-item:hover {
        background-color: #e9ecef;
        /* Цвет фона при наведении */
    }
</style>
@endsection