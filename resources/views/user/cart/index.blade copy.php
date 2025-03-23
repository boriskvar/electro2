@extends('layouts.user')



@section('content')
<!-- Выводим переменную breadcrumbs для проверки -->
{{-- <pre>{{ var_dump($breadcrumbs) }}</pre> --}}
<!-- Передаем хлебные крошки как пропс -->
<x-breadcrumbs :breadcrumbs="$breadcrumbs" />
{{-- {{ dd($breadcrumbs); }} --}}



<!-- Cart -->
<div class="dropdown">
    <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
        <i class="fa fa-shopping-cart"></i>
        <span>Ваша корзина</span>
        <div class="qty">{{ count(session('cart_1', [])) }}</div>
    </a>
    <div class="cart-dropdown">
        @if(count(session('cart_1', [])) > 0)
        <div class="cart-list">
            @foreach(session('cart_1', []) as $item)
            <div class="product-widget">
                <div class="product-img">
                    <img src="{{ asset('storage/img/' . ($item['image'] ?? 'default-product.png')) }}" alt="{{ $item['name'] }}">
                </div>
                <div class="product-body">
                    <h3 class="product-name">
                        <a href="#">{{ $item['name'] }}</a>
                    </h3>
                    <h4 class="product-price">
                        <span class="qty">{{ $item['qty'] }}x</span>
                        ${{ round($item['price'], 0) }}
                    </h4>
                </div>
                <form action="{{ route('user.cart.remove', ['product_id' => $item['productId']]) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete">
                        <i class="fa fa-close"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        <div class="cart-summary">
            <small>{{ count(session('cart_1', [])) }} товар(а) выбран(о)</small>
            <h5>Итого: ${{ round(array_sum(array_column(session('cart_1', []), 'total')), 0) }}</h5>
        </div>
        <div class="cart-btns">
            <a href="{{ route('user.cart.index') }}">Просмотреть корзину</a>
            <a href="{{ route('user.checkout.index') }}">Оформить заказ <i class="fa fa-arrow-circle-right"></i></a>
        </div>
        @else
        <p class="text-center">Ваша корзина пуста.</p>
        @endif
    </div>
</div>
<!-- /Cart -->

@endsection
