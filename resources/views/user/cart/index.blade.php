<!DOCTYPE html>
<html>

  <head>
    <title>Корзина</title>
  </head>

  <body>
    <h1>Корзина</h1>
    @if (!empty($cartItems))
      @foreach ($cartItems as $item)
        <div class="product-widget">
          <div class="product-img">
            <img src="{{ $item['image'] }}" alt="">
          </div>
          <div class="product-body">
            <h3 class="product-name"><a href="#">{{ $item['name'] }}</a></h3>
            <h4 class="product-price"><span class="qty">{{ $item['quantity'] }}x</span>${{ $item['price'] }}</h4>
          </div>
          <form action="{{ route('user.cart.remove', $item['product_id']) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete"><i class="fa fa-close"></i></button>
          </form>
        </div>
      @endforeach
    @else
      <p>Ваша корзина пуста</p>
    @endif
    <div class="cart-summary">
      <small>{{ count($cartItems) }} Item(s) selected</small>
      {{-- <h5>SUBTOTAL: ${{ $subtotal }}</h5> <!-- Цена может быть динамической --> --}}
    </div>
    <a href="{{ route('checkout.index') }}">Оформить заказ</a>
  </body>

</html>
