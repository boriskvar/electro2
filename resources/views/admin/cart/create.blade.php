@extends('layouts.admin')

@section('content')
  <h1>Добавить товар в корзину</h1>

  <form action="{{ route('admin.cart.store') }}" method="POST">
    @csrf
    <div class="form-group">
      <label for="product_id">Выберите товар:</label>
      <select name="product_id"
              id="product_id"
              class="form-control">
        @foreach ($products as $product)
          <option value="{{ $product->id }}">{{ $product->name }}</option>
        @endforeach
      </select>
    </div>

    <button type="submit" class="btn btn-success">Добавить в корзину</button>
  </form>
@endsection
