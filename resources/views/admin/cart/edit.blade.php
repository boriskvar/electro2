@extends('layouts.admin')

@section('content')
  <h1>Редактировать товар в корзине</h1>

  <form action="{{-- {{ route('admin.cart.update', $productId) }} --}}" method="POST">
    @csrf
    @method('PUT')
    <div class="form-group">
      <label for="qty">Количество товара:</label>
      <input type="number"
             name="qty"
             id="qty"
             value="{{ $product['qty'] }}"
             class="form-control"
             min="1">
    </div>

    <button type="submit" class="btn btn-primary">Обновить</button>
  </form>
@endsection
