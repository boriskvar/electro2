@extends('layouts.admin')

@section('content')
<div class="container">
    <h1>Добавление товаров в заказ</h1>
    <form action="{{ route('admin.order-items.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="order_id">Выберите заказ:</label>
            <select class="form-control" id="order_id" name="order_id" required>
                <option value="">Выберите заказ</option>
                @foreach ($orders as $singleOrder)
                <option value="{{ $singleOrder->id }}">{{ $singleOrder->order_number }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="product_id">Товар:</label>
            <select class="form-control" id="product_id" name="product_id" required>
                <option value="">Выберите товар</option>
                @foreach ($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} (Цена за единицу:
                    {{ $product->price }})
                </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="quantity">Количество:</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" value="1" required>
        </div>

        {{-- <div class="form-group">
        <label for="price_x_quantity">Цена (х) кол-во.:</label>
        <input type="number"
               class="form-control"
               id="price_x_quantity"
               name="price_x_quantity"
               readonly>
      </div> --}}

        <button type="submit" class="btn btn-primary">Создать заказ</button>
        <a href="{{ route('admin.order-items.index', ['order' => $order->id]) }}" class="btn btn-warning">Назад к списку
            товаров в заказе</a>
    </form>
</div>

{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
      const productSelect = document.getElementById('product_id');
      const quantityInput = document.getElementById('quantity');
      const priceField = document.getElementById('price_x_quantity');

      function updatePrice() {
        const selectedOption = productSelect.options[productSelect.selectedIndex];
        const productPrice = parseFloat(selectedOption.getAttribute('data-price')) || 0;
        const quantity = parseInt(quantityInput.value) || 0;

        priceField.value = (productPrice * quantity).toFixed(2);
      }

      productSelect.addEventListener('change', updatePrice);
      quantityInput.addEventListener('input', updatePrice);

      // Инициализируем цену при загрузке
      updatePrice();
    });
  </script> --}}
@endsection