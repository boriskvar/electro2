   @props(['wishlistCount'])

   <!-- MAIN HEADER -->
   <div id="header">
       <!-- container -->
       <div class="container">
           <!-- row -->
           <div class="row">
               <!-- LOGO -->
               <div class="col-md-3">
                   <div class="header-logo">
                       <a href="#" class="logo">
                           <img src="/storage/img/logo.png" alt="logo">
                       </a>
                   </div>
               </div>
               <!-- /LOGO -->



               <!-- SEARCH BAR -->
               <div class="col-md-6">
                   <div class="header-search">
                       <form method="GET" action="{{ route('search.index') }}">
                           <select class="input-select" name="category">
                               <option value="">All Categories</option>
                               @foreach ($categories as $cat)
                               <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                                   {{ $cat->name }}
                               </option>
                               @endforeach
                           </select>
                           <input class="input" type="text" name="search" placeholder="Search here" value="{{ request('search') }}">
                           <button type="submit" class="search-btn">Search</button>
                       </form>

                   </div>
               </div>
               <!-- /SEARCH BAR -->
               {{-- {{ dd($wishlistCount) }} --}}
               {{-- <div class="qty" id="wishlist-count">
                   {{ $wishlistCount }}
           </div> --}}

           <!-- ACCOUNT -->
           <div class="col-md-3 clearfix">
               <div class="header-ctn">
                   <!-- Wishlist -->
                   <div>
                       <a href="{{ route('wishlist.index') }}">
                           <i class="fa fa-heart-o"></i>
                           <span>Your Wishlist</span>
                           <div class="qty" id="wishlist-count">{{ $wishlistCount }}</div><!-- Динамическое количество -->
                       </a>
                   </div>
                   <!-- /Wishlist -->

                   <!-- Cart -->
                   <div class="dropdown">
                       <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                           <i class="fa fa-shopping-cart"></i>
                           <span>Your Cart</span>
                           {{-- <div class="qty">3 </div> --}}
                           <div class="qty">{{ $cartItems->count() ?? 0 }}</div>
                       </a>
                       <div class="cart-dropdown">
                           <div class="cart-list">
                               @if (!empty($cartItems))
                               @foreach ($cartItems as $item)
                               <div class="product-widget" data-product-id="{{ $item->product_id }}">
                                   <div class="product-img">
                                       <img src="{{ $item->image ? '/storage/img/' . $item->image : '/path/to/default/image.jpg' }}" alt="">
                                   </div>
                                   <div class="product-body">
                                       <h3 class="product-name"><a href="#">{{ $item->product->name }}</a></h3>
                                       <h4 class="product-price"><span class="qty">{{ $item->quantity }}x</span>${{ $item->product->price }}</h4>
                                   </div>
                                   {{-- <button class="delete"><i class="fa fa-close"></i></button> --}}
                                   <button class="delete" onclick="removeFromCart({{ $item->product_id }})"><i class="fa fa-close"></i></button>
                               </div>
                               @endforeach
                               @else
                               <p>Ваша корзина пуста</p>
                               @endif
                           </div>
                           <div class="cart-summary">
                               <small>{{ $cartItems->count() ?? 0 }} Item(s) selected</small>
                               <h5>SUBTOTAL: ${{ $cartItems->sum('price_x_quantity') ?? 0 }}</h5>
                           </div>
                           <div class="cart-btns">
                               <a href="{{ route('cart.index') }}">View Cart</a>
                               <a href="{{ route('checkout.index') }}">Checkout <i class="fa fa-arrow-circle-right"></i></a>
                           </div>
                       </div>
                   </div>
                   <!-- /Cart -->

                   <!-- Menu Toogle -->
                   <div class="menu-toggle">
                       <a href="#">
                           <i class="fa fa-bars"></i>
                           <span>Menu</span>
                       </a>
                   </div>
                   <!-- /Menu Toogle -->
               </div>
           </div>
           <!-- /ACCOUNT -->
       </div>
       <!-- row -->
   </div>
   <!-- container -->
   </div>
   <!-- /MAIN HEADER -->

   <!-- Уведомления -->
   <div id="notification-container" class="notification-container"></div>

   <script>
       // Функция удаления товара из корзины
       function removeFromCart(productId) {
           if (confirm('Вы уверены, что хотите удалить этот товар из корзины?')) {
               fetch(`/cart/remove/${productId}`, {
                       method: 'DELETE'
                       , headers: {
                           'X-CSRF-TOKEN': '{{ csrf_token() }}'
                           , 'Content-Type': 'application/json'
                       }
                   })
                   .then(response => response.json())
                   .then(data => {
                       if (data.success) {
                           // Удаляем товар из DOM
                           const productWidget = document.querySelector(`[data-product-id="${productId}"]`);
                           if (productWidget) {
                               productWidget.remove();
                           }
                           updateCartSummary(); // Обновляем корзину
                           showToast('Товар удален из корзины', 'success');
                       } else {
                           showToast(data.message, 'error');
                       }
                   })
                   .catch(error => {
                       console.error('Ошибка:', error);
                       showToast('Произошла ошибка при удалении товара', 'error');
                   });
           }
       }

       // Обновление данных о корзине
       function updateCartSummary() {
           fetch('/cart/data')
               .then(response => response.json())
               .then(data => {
                   document.querySelector('.qty').textContent = data.cartCount;
                   document.querySelector('#cart-subtotal').textContent = `SUBTOTAL: $${data.subtotal}`;
                   document.querySelector('#cart-count').textContent = `${data.cartCount} Item(s) selected`;

                   const cartList = document.querySelector('#cart-list');
                   cartList.innerHTML = '';

                   if (data.cartItems.length > 0) {
                       data.cartItems.forEach(item => {
                           const productWidget = document.createElement('div');
                           productWidget.classList.add('product-widget');
                           productWidget.setAttribute('data-product-id', item.product_id);

                           productWidget.innerHTML = `
                            <div class="product-img">
                                <img src="${item.image ? '/storage/img/' + item.image : '/path/to/default/image.jpg'}" alt="">
                            </div>
                            <div class="product-body">
                                <h3 class="product-name"><a href="#">${item.product.name}</a></h3>
                                <h4 class="product-price"><span class="qty">${item.quantity}x</span>$${item.product.price}</h4>
                            </div>
                            <button class="delete" onclick="removeFromCart(${item.product_id})"><i class="fa fa-close"></i></button>
                        `;

                           cartList.appendChild(productWidget);
                       });
                   } else {
                       cartList.innerHTML = '<p>Ваша корзина пуста</p>';
                   }
               })
               .catch(error => console.error('Ошибка:', error));
       }

       // Функция отображения уведомлений
       function showToast(message, type) {
           const notificationContainer = document.querySelector('#notification-container');
           const notification = document.createElement('div');
           notification.classList.add('notification', type);
           notification.innerHTML = `
            <span>${message}</span>
            <button onclick="this.parentElement.remove()">&times;</button>
        `;

           notificationContainer.appendChild(notification);

           setTimeout(() => {
               notification.remove();
           }, 3000);
       }

       // Обработчики событий для обновления корзины и отображения уведомлений
       window.addEventListener('updateCart', updateCartSummary);
       window.addEventListener('showToast', (event) => {
           showToast(event.detail.message, event.detail.type);
       });

       // Функция добавления товара в Wishlist
       function addToWishlist(productId) {
           fetch('/account/wishlist/store', {
                   method: 'POST'
                   , headers: {
                       'Content-Type': 'application/json'
                       , 'X-CSRF-TOKEN': '{{ csrf_token() }}'
                   , }
                   , body: JSON.stringify({
                       product_id: productId
                   })
               })
               .then(response => response.json())
               .then(data => {
                   if (data.success) {
                       showToast('Товар добавлен в Wishlist', 'success');
                       // Обновляем страницу после успешного добавления
                       location.reload(); // Обновление страницы
                   } else {
                       showToast(data.message, 'error');
                   }
               })
               .catch(error => {
                   console.error('Ошибка при добавлении в Wishlist:', error);
                   showToast('Произошла ошибка при добавлении товара в Wishlist', 'error');
               });
       }

       // Обновление количества товаров в Wishlist
       function updateWishlistCount() {
           fetch(`/account/wishlist/count?t=${Date.now()}`) // Добавили параметр для избежания кэширования
               .then(response => response.json())
               .then(data => {
                   const wishlistQtyElement = document.querySelector('.wishlist-qty');
                   if (wishlistQtyElement) { // Проверяем, существует ли элемент
                       wishlistQtyElement.textContent = data.count; // Обновляем количество товаров
                   } else {
                       console.error("❌ Элемент .wishlist-qty не найден в DOM!");
                   }
               })
               .catch(error => {
                   console.error('Ошибка при обновлении количества в Wishlist:', error);
               });
       }

       // Вызываем обновление количества в Wishlist после изменений (например, добавление/удаление товара)
       function triggerWishlistUpdate() {
           updateWishlistCount();
       }

       // Пример вызова функции после изменения в Wishlist (например, при добавлении товара)
       window.addEventListener('wishlistUpdated', triggerWishlistUpdate);
   </script>




   <style>
       .notification-container {
           position: fixed;
           top: 20px;
           right: 20px;
           z-index: 1000;
       }

       .notification {
           background-color: #333;
           color: #fff;
           padding: 10px 20px;
           margin-bottom: 10px;
           border-radius: 5px;
           display: flex;
           justify-content: space-between;
           align-items: center;
       }

       .notification.success {
           background-color: green;
       }

       .notification.error {
           background-color: red;
       }

       .notification button {
           background: none;
           border: none;
           color: #fff;
           font-size: 16px;
           cursor: pointer;
       }
   </style>