@extends('layouts.main')

@section('content')
<div class="container">

    <div class="row">
        <!-- Сайдбар -->
        <div class="col-md-3">
            <div class="list-group">
                <a href="{{ route('my-account.page', 'dashboard') }}" class="list-group-item {{ $activePage === 'dashboard' ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('my-account.page', 'wishlist') }}" class="list-group-item {{ $activePage === 'wishlist' ? 'active' : '' }}">
                    My Wishlist
                </a>
                <a href="{{ route('my-account.page', 'orders') }}" class="list-group-item {{ $activePage === 'orders' ? 'active' : '' }}">
                    My Orders
                </a>
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
                    <p>Список товаров, добавленных в избранное.</p>
                    @elseif ($activePage === 'orders')
                    <h3>My Orders</h3>
                    <p>История заказов.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



@endsection