        <!-- Сайдбар с ссылками на разные страницы -->
        <div class="sidebar">
            <ul>
                <li class="{{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard.index') }}">Dashboard</a>
                </li>
                <li class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}">Категории</a>
                </li>
                <li class="{{ request()->is('admin/products*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}">Товары</a>
                </li>
                <li class="{{ request()->is('admin/menus*') ? 'active' : '' }}">
                    <a href="{{ route('admin.menus.index') }}">Меню</a>
                </li>
                <li class="{{ request()->is('admin/pages*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}">Страницы</a>
                </li>
                <li class="{{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}">Заказы</a>
                </li>
                {{-- <li class="{{ request()->is('admin/order-items*') ? 'active' : '' }}">
                <a href="{{ route('admin.order-items.index') }}">Меню</a>
                </li> --}}
                <li class="{{ request()->is('admin/cart*') ? 'active' : '' }}">
                    <a href="{{ route('admin.cart.index') }}">Корзина</a>
                </li>
                <li class="{{ request()->is('admin/checkout*') ? 'active' : '' }}">
                    <a href="{{ route('admin.checkout.index') }}">Оформить</a>
                </li>
                <li class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reviews.index') }}">Отзывы</a>
                </li>
                <li class="{{ request()->is('admin/payments*') ? 'active' : '' }}">
                    <a href="{{ route('admin.payments.index') }}">Оплата</a>
                </li>
                <li class="{{ request()->is('admin/brands*') ? 'active' : '' }}">
                    <a href="{{ route('admin.brands.index') }}">Бренды</a>
                </li>
                <li class="{{ request()->is('admin/contacts*') ? 'active' : '' }}">
                    <a href="{{ route('admin.contacts.index') }}">Контакты</a>
                </li>
            </ul>
        </div>
