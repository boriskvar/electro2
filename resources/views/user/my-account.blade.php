@extends('layouts.main')

@section('content')
<div class="container">

    <div class="row">
        <!-- Сайдбар -->
        <div class="col-md-3">
            <div class="sidebar" style="border: 1px solid #D10024; " {{-- style="width: 150px;" --}}>

                @auth
                <div class="media" style="padding: 8px; background-color: #f5f5f5; border-bottom: 1px solid #ddd;">
                    <div class="media-left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#D10024" viewBox="0 0 24 24">
                            <path d="M12 2a7 7 0 0 1 7 7c0 3.9-3.1 7-7 7s-7-3.1-7-7a7 7 0 0 1 7-7zm0 16c5.5 0 10 2.2 10 5v1H2v-1c0-2.8 4.5-5 10-5z" />
                        </svg>
                    </div>
                    <div class="media-body" style="padding-left: 8px;">
                        <strong style="font-size: 13px;">{{ Auth::user()->name }}</strong><br>
                        <span style="font-size: 11px; color: #888;">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                @endauth




                <a href="{{ route('my-account') }}" class="my-sidebar-item {{ $activePage === 'dashboard' ? 'active' : '' }}">Dashboard</a>
                <a href="{{ route('wishlist.index') }}" class="my-sidebar-item {{ $activePage === 'wishlist' ? 'active' : '' }}">My Wishlist</a>
                <a href="{{ route('compare.index') }}" class="my-sidebar-item {{ $activePage === 'compare' ? 'active' : '' }}">Compare list</a>
                <a href="{{ route('cart.index') }}" class="my-sidebar-item {{ $activePage === 'cart' ? 'active' : '' }}">My Cart</a>
                <a href="{{ route('checkout.index') }}" class="my-sidebar-item {{ $activePage === 'checkout' ? 'active' : '' }}"> My Checkout</a>

                <a href="{{ route('reviews.index') }}" class="my-sidebar-item {{ $activePage === 'reviews' ? 'active' : '' }}">My Reviews</a>

                {{-- <a href="{{ route('orders.index') }}" class="my-sidebar-item {{ $activePage === 'orders' ? 'active' : '' }}"> My Orders</a> --}}
                {{-- <a href="{{ route('products.index') }}" class="my-sidebar-item {{ $activePage === 'products' ? 'active' : '' }}">Viewed products</a> --}}

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

                    @elseif ($activePage === 'compare')
                    @include('user.compare.index')

                    @elseif ($activePage === 'checkout')
                    {{-- <h3>My Checkout</h3> --}}
                    {{-- <p>Оформление заказа.</p> --}}
                    @include('user.checkout.index')

                    @elseif ($activePage === 'reviews')
                    @foreach ($reviews as $review)
                    <div class="mb-4 review-item pb-3">
                        <strong>{{ $review->product->name ?? 'Товар удалён' }}</strong><br>

                        {{-- звёзды --}}
                        <div class="review">
                            <div class="rating-stars">
                                @for ($i = 1; $i <= 5; $i++) <i class="fa fa-star{{ $i <= $review->rating ? ' red-star' : '-o' }}"></i>
                                    @endfor
                            </div>
                            <p>{{ $review->review }}</p>
                        </div>

                        <small class="text-muted">{{ $review->author_name }} — {{ $review->created_at->format('d.m.Y H:i') }}</small>

                    </div>
                    @endforeach

                    <style>
                        /* Кастомизация цвета звезд для рейтинга */
                        .red-star {
                            color: red !important;
                            /* Используем красный цвет для активных звезд */
                        }

                        /* Дополнительно можно сделать, чтобы неактивные звезды были более тусклыми */
                        .fa-star-o {
                            color: #ccc;
                            /* Цвет для неактивных звезд (серый) */
                        }

                        /* Подчеркивание (разделитель) под каждым отзывом */
                        .review-item {
                            border-bottom: 2px solid #e0e0e0;
                            /* Подчеркивание светло-серого цвета */
                            padding-bottom: 15px;
                            /* Дополнительное пространство снизу для отделения */
                            margin-bottom: 15px;
                            /* Пространство между отзывами */
                        }
                    </style>


                    {{-- @if($reviews->isEmpty())
                    <p>Вы ещё не оставляли отзывов.</p>
                    @else
                    <ul class="list-group">
                        @foreach ($reviews as $review)
                        <li class="list-group-item">
                            <strong>{{ $review->product->title ?? 'Товар удалён' }}</strong><br>
                    <small>{{ $review->created_at->format('d.m.Y H:i') }}</small><br>
                    <div>
                        @for ($i = 1; $i <= 5; $i++) <i class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                            @endfor
                    </div>
                    <p>{{ $review->review }}</p>
                    </li>
                    @endforeach
                    </ul>
                    @endif --}}

                    {{-- @elseif ($activePage === 'orders')
                    <h3>My Orders</h3>
                    <p>История заказов.</p> --}}

                    {{-- @elseif ($activePage === 'products')
                    <h3>Viewed products</h3>
                    <p>Просмотренные товары.</p> --}}
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