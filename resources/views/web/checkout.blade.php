@extends('layouts.main')

@section('content')
<!-- SECTION -->
<div class="section">
    <!-- container -->
    <div class="container">
        <!-- row -->
        <div class="row">

            <div class="col-md-7">
                <!-- Billing Details -->
                <div class="billing-details">
                    <div class="section-title">
                        <h3 class="title">Billing address</h3>
                    </div>
                    <form id="orderForm" action="{{ route('checkout.placeOrder') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input class="input" type="text" name="first_name" placeholder="First Name">
                        </div>
                        <div class="form-group">
                            <input class="input" type="text" name="last_name" placeholder="Last Name">
                        </div>
                        <div class="form-group">
                            <input class="input" type="email" name="email" placeholder="Email">
                        </div>
                        <div class="form-group">
                            <input class="input" type="text" name="address" placeholder="Address">
                        </div>
                        <div class="form-group">
                            <input class="input" type="text" name="city" placeholder="City">
                        </div>
                        <div class="form-group">
                            <input class="input" type="text" name="country" placeholder="Country">
                        </div>
                        <div class="form-group">
                            <input class="input" type="text" name="zip_code" placeholder="ZIP Code">
                        </div>
                        <div class="form-group">
                            <input class="input" type="tel" name="tel" placeholder="Telephone">
                        </div>
                        <div class="form-group">
                            <div class="input-checkbox">
                                <input type="checkbox" id="create-account" name="create_account">
                                <label for="create-account">
                                    <span></span>
                                    Create Account?
                                </label>
                                <div class="caption">
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p>
                                    {{-- <input class="input"
                           type="password"
                           name="password"
                           placeholder="Enter Your Password"> --}}
                                </div>
                            </div>
                        </div>
                </div>
                <!-- /Billing Details -->

                <!-- Shiping Details -->
                <div class="shiping-details">
                    <div class="section-title">
                        <h3 class="title">Shiping address</h3>
                    </div>
                    <div class="input-checkbox">
                        <input type="checkbox" id="dif_address">
                        <label for="dif_address">
                            <span></span>
                            Ship to a diffrent address?
                        </label>
                        <div class="caption">
                            <!-- Duplicate billing form for shipping address -->
                            <div class="form-group">
                                <input class="input" type="text" name="dif_first_name" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="dif_last_name" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <input class="input" type="email" name="dif_email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="dif_address" placeholder="Address">
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="dif_city" placeholder="City">
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="dif_country" placeholder="Country">
                            </div>
                            <div class="form-group">
                                <input class="input" type="text" name="dif_zip_code" placeholder="ZIP Code">
                            </div>
                            <div class="form-group">
                                <input class="input" type="tel" name="dif_tel" placeholder="Telephone">
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Shiping Details -->

                <!-- Order notes -->
                <div class="order-notes">
                    <textarea class="input" name="order_notes" placeholder="Order Notes"></textarea>
                </div>
                <!-- /Order notes -->
            </div>

            <!-- Order Details -->
            <div class="col-md-5 order-details">
                <div class="section-title text-center">
                    <h3 class="title">Your Order</h3>
                </div>
                <div class="order-summary">
                    <div class="order-col">
                        <div><strong>PRODUCT</strong></div>
                        <div><strong>TOTAL</strong></div>
                    </div>
                    <div class="order-products">
                        @foreach ($cartItems as $item)
                        <div class="order-col">
                            {{-- <div>1x Product Name Goes Here</div> --}}
                            <div>{{ $item->quantity }}x {{ $item->product->name }}</div>
                            {{-- <div>$980.00</div> --}}
                            {{-- <div>${{ number_format($item->price_x_quantity, 2) }}
                        </div> --}}
                        <div>${{ number_format($item->price_x_quantity, 2, '.', '') }}</div>
                    </div>
                    {{-- <div class="order-col"> --}}
                    {{-- <div>2x Product Name Goes Here</div> --}}
                    {{-- <div>$980.00</div> --}}
                    {{-- </div> --}}
                    @endforeach
                </div>
                <div class="order-col">
                    <div>Shiping</div>
                    {{-- <div><strong>FREE</strong></div> --}}
                    <div>
                        <select name="shipping_method" id="shipping_method" class="input" onchange="document.getElementById('checkout-form').submit();">
                            {{-- @foreach (config('shipping.methods') as $key => $method) --}}
                            @foreach ($shippingMethods as $key => $method)
                            <option value="{{ $key }}" data-cost="{{ $method['cost'] }}" {{ $selectedShippingMethod == $key ? 'selected' : '' }}>
                                <span>{{ $method['name'] }}</span> - ${{ number_format($method['cost'], 0, '.', '') }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="order-col">
                    <div><strong>TOTAL</strong></div>
                    {{-- <div><strong class="order-total">$2940.00</strong></div> --}}
                    <div><strong class="order-total">${{ number_format($totalWithShipping, 2, '.', '') }}</strong></div>
                </div>
            </div>

            <div class="payment-method">
                <div class="input-radio">
                    <input type="radio" name="payment_method" id="payment-1" value="bank_transfer">
                    <label for="payment-1">
                        <span></span>
                        {{-- Direct Bank Transfer --}}Карточка
                    </label>
                    <div class="caption">
                        <p>{{ isset($payment_description_card) ? $payment_description_card : 'Оплата карточкой' }}</p>
                    </div>
                </div>

                <div class="input-radio">
                    <input type="radio" name="payment_method" id="payment-2" value="cheque">
                    <label for="payment-2">
                        <span></span>
                        {{-- Cheque Payment --}}Наличные
                    </label>
                    <div class="caption">
                        <p>{{ isset($payment_description_cash) ? $payment_description_cash : 'Оплата наличными, при получении' }}
                    </div>
                </div>

                <div class="input-radio">
                    <input type="radio" name="payment_method" id="payment-3" value="paypal">
                    <label for="payment-3">
                        <span></span>
                        Paypal System
                    </label>
                    <div class="caption">
                        <p>{{ isset($payment_description_online) ? $payment_description_online : 'Оплата онлайн' }}</p>
                    </div>
                </div>
            </div>

            <div class="input-checkbox">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">
                    <span></span>
                    I've read and accept the <a href="#">terms & conditions</a>
                </label>
            </div>

            {{-- <a href="#" class="primary-btn order-submit">Place order</a> --}}
            <button type="submit" class="primary-btn order-submit">Place order</button>
            {{-- <button type="submit"
                  class="primary-btn order-submit"
                  id="placeOrderButton"
                  style="z-index: 9999; position: relative;">
            Place order
          </button> --}}
            </form>
        </div>
        <!-- /Order Details -->
    </div>
    <!-- /row -->
</div>
<!-- /container -->
</div>
<!-- /SECTION -->


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Обновление суммы заказа при выборе метода доставки
        const shippingMethodSelect = document.getElementById("shipping_method");
        const orderTotalElement = document.querySelector(".order-total");
        const initialTotal = parseFloat("{{ $total }}") || 0; // Исправил вывод PHP-переменной

        if (shippingMethodSelect && orderTotalElement) {
            shippingMethodSelect.addEventListener("change", function() {
                const selectedOption = shippingMethodSelect.options[shippingMethodSelect.selectedIndex];
                const shippingCost = parseFloat(selectedOption.getAttribute("data-cost")) || 0;
                const newTotal = initialTotal + shippingCost;

                if (!isNaN(newTotal)) {
                    orderTotalElement.textContent = `$${newTotal.toFixed(2)}`;
                }
            });
        }

        // Обработчик клика на кнопку "PLACE ORDER"
        const placeOrderButton = document.getElementById("placeOrderButton");
        const orderForm = document.getElementById("orderForm");

        if (placeOrderButton && orderForm) {
            placeOrderButton.addEventListener("click", function(event) {
                event.preventDefault(); // Останавливаем стандартное поведение кнопки

                let formData = new FormData(orderForm);
                placeOrderButton.disabled = true; // Блокируем кнопку во время запроса

                fetch(orderForm.action, {
                        method: "POST"
                        , body: formData
                        , headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message); // Выводим сообщение об успешном заказе
                            window.location.href = data.redirect_url; // Перенаправляем на категорию
                        } else {
                            alert(data.message || "Ошибка оформления заказа. Попробуйте снова.");
                            placeOrderButton.disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error("Ошибка:", error);
                        alert("Произошла ошибка! Проверьте интернет-соединение.");
                        placeOrderButton.disabled = false;
                    });
            });
        }
    });
</script>


@endsection