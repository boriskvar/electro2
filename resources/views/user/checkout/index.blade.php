@extends('layouts.layout')

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
            <form action="{{ route('checkout.placeOrder') }}" method="POST">
              @csrf
              <div class="form-group">
                <input class="input"
                       type="text"
                       name="first_name"
                       placeholder="First Name">
              </div>
              <div class="form-group">
                <input class="input"
                       type="text"
                       name="last_name"
                       placeholder="Last Name">
              </div>
              <div class="form-group">
                <input class="input"
                       type="email"
                       name="email"
                       placeholder="Email">
              </div>
              <div class="form-group">
                <input class="input"
                       type="text"
                       name="address"
                       placeholder="Address">
              </div>
              <div class="form-group">
                <input class="input"
                       type="text"
                       name="city"
                       placeholder="City">
              </div>
              <div class="form-group">
                <input class="input"
                       type="text"
                       name="country"
                       placeholder="Country">
              </div>
              <div class="form-group">
                <input class="input"
                       type="text"
                       name="zip_code"
                       placeholder="ZIP Code">
              </div>
              <div class="form-group">
                <input class="input"
                       type="tel"
                       name="tel"
                       placeholder="Telephone">
              </div>
              <div class="form-group">
                <div class="input-checkbox">
                  <input type="checkbox"
                         id="create-account"
                         name="create_account">
                  <label for="create-account">
                    <span></span>
                    Create Account?
                  </label>
                  <div class="caption">
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt.</p>
                    <input class="input"
                           type="password"
                           name="password"
                           placeholder="Enter Your Password">
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
              <input type="checkbox"
                     id="shiping-address"
                     name="shipping_address">
              <label for="shiping-address">
                <span></span>
                Ship to a diffrent address?
              </label>
              <div class="caption">
                <!-- Duplicate billing form for shipping address -->
                <div class="form-group">
                  <input class="input"
                         type="text"
                         name="shipping_first-name"
                         placeholder="First Name">
                </div>
                <div class="form-group">
                  <input class="input"
                         type="text"
                         name="shipping_last-name"
                         placeholder="Last Name">
                </div>
                <div class="form-group">
                  <input class="input"
                         type="email"
                         name="shipping_email"
                         placeholder="Email">
                </div>
                <div class="form-group">
                  <input class="input"
                         type="text"
                         name="shipping_address"
                         placeholder="Address">
                </div>
                <div class="form-group">
                  <input class="input"
                         type="text"
                         name="shipping_city"
                         placeholder="City">
                </div>
                <div class="form-group">
                  <input class="input"
                         type="text"
                         name="shipping_country"
                         placeholder="Country">
                </div>
                <div class="form-group">
                  <input class="input"
                         type="text"
                         name="shipping_zip-code"
                         placeholder="ZIP Code">
                </div>
                <div class="form-group">
                  <input class="input"
                         type="tel"
                         name="shipping_tel"
                         placeholder="Telephone">
                </div>
              </div>
            </div>
          </div>
          <!-- /Shiping Details -->

          <!-- Order notes -->
          <div class="order-notes">
            <textarea class="input"
                      name="order_notes"
                      placeholder="Order Notes"></textarea>
          </div>
          <!-- /Order notes -->
        </div>

        <!-- Order Details -->
        <div class="col-md-5 order-details">
          <div class="section-title text-center">
            <h3 class="title">Your Order</h3>
          </div>
          < class="order-summary">
            <div class="order-col">
              <div><strong>PRODUCT</strong></div>
              <div><strong>TOTAL</strong></div>
            </div>
            <div class="order-products">
              @foreach ($cartItems as $item)
                <div class="order-col">
                  {{-- <div>1x Product Name Goes Here</div> --}}
                  {{-- <div>{{ $item->quantity }}x {{ $item->product->name }}</div> --}}
                  <div>{{ $item['quantity'] }}x {{ $item['name'] }}</div>
                  {{-- <div>$980.00</div> --}}
                  {{-- <div>${{ number_format($item->price, 2) }}</div> --}}
                  <div>${{ number_format($item['price'], 2) }}</div>
                </div>
              @endforeach
            </div>

            <div class="order-col">
              <div>Shiping</div>
              <div><strong>FREE</strong></div>
            </div>
            <div class="order-col">
              <div><strong>TOTAL</strong></div>
              {{-- <div><strong class="order-total">$2940.00</strong></div> --}}
              <div><strong class="order-total">${{ number_format($total, 2) }}</strong></div>
            </div>
        </div>

        <div class="payment-method">
          <div class="input-radio">
            <input type="radio"
                   name="payment_method"
                   id="payment-1"
                   value="bank_transfer"
                   required>

            <label for="payment-1">
              <span></span>
              Direct Bank Transfer
            </label>

            <div class="caption">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.</p>
            </div>
          </div>
          <div class="input-radio">
            <input type="radio"
                   {{-- name="payment" --}}
                   name="payment_method"
                   id="payment-2"
                   value="cheque"
                   required>
            <label for="payment-2">
              <span></span>
              Cheque Payment
            </label>
            <div class="caption">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.</p>
            </div>
          </div>
          <div class="input-radio">
            <input type="radio"
                   {{-- name="payment" --}}
                   name="payment_method"
                   id="payment-3"
                   value="paypal"
                   required>
            <label for="payment-3">
              <span></span>
              Paypal System
            </label>
            <div class="caption">
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua.</p>
            </div>
          </div>
        </div>
        <div class="input-checkbox">
          <input type="checkbox"
                 id="terms"
                 name="terms"
                 required>
          <label for="terms">
            <span></span>
            I've read and accept the <a href="#">terms & conditions</a>
          </label>
        </div>
        {{-- <a href="#" class="primary-btn order-submit">Place order</a> --}}
        <button type="submit" class="primary-btn order-submit">Place order</button>
      </div>
      <!-- /Order Details -->
    </div>
    <!-- /row -->
  </div>
  <!-- /container -->
  </div>
  <!-- /SECTION -->
@endsection
