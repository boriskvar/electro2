@extends('layouts.admin')

@section('content')
  <h1>Добро пожаловать в админ панель</h1>

  <div class="dashboard-overview">
    <div class="stat-box">
      <h2>Заказы</h2>
      <p>Всего: {{-- {{ $totalOrders }} --}}</p>
      <p>Сегодня: {{-- {{ $ordersToday }} --}}</p>
    </div>
    <div class="stat-box">
      <h2>Пользователи</h2>
      <p>Зарегистрировано: {{-- {{ $totalUsers }} --}}</p>
    </div>
    <div class="stat-box">
      <h2>Товары</h2>
      <p>Доступно: {{-- {{ $totalProducts }} --}}</p>
    </div>
  </div>

  <div class="quick-links">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Список заказов</a>
    <a href="{{ route('admin.products.index') }}" class="btn btn-primary">Управление товарами</a>
    <a href="{{-- {{ route('admin.users') }} --}}" class="btn btn-primary">Пользователи</a>
  </div>
@endsection
