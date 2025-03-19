@extends('layouts.admin')

@section('content')
  <h1>Корзина</h1>

  @if (session()->has('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @elseif(session()->has('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  {{-- @if (!empty($cart) && count($cart) > 0) --}}
  @if (!$cartIsEmpty)
    <table class="table">
      <thead>
        <tr>
          <th>Id</th>
          <th>Товар</th>
          <th>Количество</th>
          <th>Цена(за ед.)</th>
          <th>Цена (x) кол-во</th>
          {{-- <th>Общая стоимость</th> --}}
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        {{-- @foreach ($cart as $productId => $item) --}}
        @foreach ($cart as $item)
          <tr>
            {{-- <td>{{ $item['name'] }}</td> --}}
            <td>{{ $item->id }}</td>
            <td>{{ $item->product->name }}</td>
            {{-- <td>{{ $item['quantity'] }}</td> --}}
            <td>{{ $item->quantity }}</td>
            {{-- <td>{{ number_format($item['price_x_quantity'] / $item['quantity'], 2) }} $</td> --}}
            {{-- <td>{{ number_format($item['price'], 2) }} $</td> --}}
            <td>{{ number_format($item->product->price, 2) }} $</td>
            {{-- <td>{{ number_format($item['price_x_quantity'], 2) }} $</td> --}}
            <td>{{ number_format($item->price_x_quantity, 2) }} $</td>
            <td>
              <!-- Редактировать -->
              {{-- <a href="{{ route('admin.cart.edit', $productId) }}" class="btn btn-primary btn-sm">Редактировать</a> --}}

              <!-- Удалить -->
              {{-- <form action="{{ route('admin.cart.remove', $productId) }}" --}}
              <form action="{{ route('admin.cart.remove', $item->product_id) }}"
                    method="POST"
                    style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
              </form>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Кнопка "Checkout" -->
    <form action="{{ route('admin.cart.checkout') }}" method="POST">
      @csrf
      <button type="submit" class="btn btn-success">Оформить заказ</button>
    </form>
  @else
    <p class="text-muted">Корзина пуста.</p>
  @endif

  <hr>

  <!-- Кнопка "Добавить товар" -->
  <h2>Добавить товар</h2>
  <form action="{{ route('admin.cart.add') }}"
        method="POST"
        class="form-inline">
    @csrf
    <div class="form-group">
      <label for="product_id">Выберите товар:</label>
      <select name="product_id"
              id="product_id"
              class="form-control"
              required>
        @foreach ($products as $product)
          <option value="{{ $product->id }}">{{ $product->name }} - {{ number_format($product->price, 2) }} $</option>
        @endforeach
      </select>
    </div>
    <div class="form-group mx-sm-3">
      <label for="quantity" class="sr-only">Количество</label>
      <input type="number"
             name="quantity"
             id="quantity"
             class="form-control"
             placeholder="Количество"
             value="1"
             min="1"
             required>
    </div>
    <button type="submit" class="btn btn-success">Добавить</button>
  </form>
@endsection
