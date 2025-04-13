<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ панель</title>

    <!-- Подключение CSS файлов -->
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" />

    <!-- Slick -->
    {{-- <link rel="stylesheet" href="{{ asset('css/slick.css') }}" /> --}}
    {{-- <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}" /> --}}

    <!-- nouislider -->
    {{-- <link rel="stylesheet" href="{{ asset('css/nouislider.min.css') }}" /> --}}

    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">

    <!-- Custom stlylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}"> --}}

    <style>
        :root {
            --background-color: rgb(242, 241, 241);
            /* --global-spacer: 4px; */
            /* --cart-picture-size: 96px; */
            /* --global-green: #00a046; */
            /* --global-black: #221f1f; */
            /* --global-black-10: #e9e9e9; */
            /* --global-black-20: #d2d2d2; */
            /* --global-blue: #3e77aa; */
            /* --global-light-blue: #31a3db; */
            /* --link-hover-color: #ff7878; */
        }

        .admin-dashboard {
            display: flex;
            /* Используем Flexbox для размещения элементов в строку */
            height: 100vh;
            /* Высота на весь экран */
        }

        .custom-sidebar {
            width: 150px;
            /* Ширина сайдбара */
            background-color: #f4f4f4;
            /* Цвет фона */
            padding: 20px;
            /* Отступы внутри */
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            /* Тень */
            flex-shrink: 0;
            /* Не сжимаем сайдбар */
        }

        .custom-sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .custom-sidebar ul li {
            margin-bottom: 15px;
        }

        .custom-sidebar ul li a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            display: block;
            padding: 10px;
            border-radius: 4px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .custom-sidebar ul li a:hover,
        .custom-sidebar ul li a:focus {
            background-color: #007bff;
            /* Цвет фона при наведении */
            color: #fff;
            /* Цвет текста при наведении */
        }

        .custom-sidebar ul li a.active {
            background-color: #007bff !important;
            /* Цвет фона для активной ссылки */
            color: #fff !important;
            /* Цвет текста для активной ссылки */
        }

        /* Для всех ссылок, если текущий путь совпадает с href ссылки */
        .custom-sidebar ul li a[href*="{{ request()->url() }}"] {
            background-color: #007bff !important;
            color: #fff !important;
        }

        /* Основной стиль для ошибки валидации */
        .is-invalid {
            border: 2px solid red;
            /* Толщина и цвет рамки */
            border-radius: 4px;
            /* Скругленные углы, если нужно */
            background-color: #ffe6e6;
            /* Фоновый цвет (светло-красный) */
            color: #cc0000;
            /* Цвет текста */
        }

        /* Сообщение об ошибке */
        .invalid-feedback {
            color: #cc0000;
            /* Цвет текста сообщения */
            font-size: 14px;
            /* Размер шрифта */
            margin-top: 4px;
            /* Отступ сверху */
        }
    </style>
</head>

<body>


    {{-- @include('partials.header') --}}

    <div class="admin-dashboard">
        <!-- Сайдбар с ссылками на разные страницы -->
        <div class="custom-sidebar">
            <ul>
                <li>
                    <a href="{{ route('admin.dashboard.index') }}" class="{{ request()->routeIs('admin.dashboard.index') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li class="{{ request()->is('admin/social-links*') ? 'active' : '' }}">
                    <a href="{{ route('admin.social-links.index') }}">social-links</a>
                </li>
                <li class="{{ request()->is('admin/wishlists*') ? 'active' : '' }}">
                    <a href="{{ route('admin.wishlists.index') }}">wishlists</a>
                </li>
                <li class="{{ request()->is('admin/brands*') ? 'active' : '' }}">
                    <a href="{{ route('admin.brands.index') }}">Бренды</a>
                </li>
                <li class="{{ request()->is('admin/categories*') ? 'active' : '' }}">
                    <a href="{{ route('admin.categories.index') }}">Категории</a>
                </li>
                <li class="{{ request()->is('admin/category-attributes*') ? 'active' : '' }}">
                    <a href="{{ route('admin.category-attributes.index') }}">хар-ки Категорий</a>
                </li>
                <li class="{{ request()->is('admin/pages*') ? 'active' : '' }}">
                    <a href="{{ route('admin.pages.index') }}">Страницы</a>
                </li>
                <li class="{{ request()->is('admin/menus*') ? 'active' : '' }}">
                    <a href="{{ route('admin.menus.index') }}">Меню</a>
                </li>
                <li class="{{ request()->is('admin/products*') ? 'active' : '' }}">
                    <a href="{{ route('admin.products.index') }}">Товары</a>
                </li>
                <li class="{{ request()->is('admin/product-attributes*') ? 'active' : '' }}">
                    <a href="{{ route('admin.product-attributes.index') }}">хар-ки Товара</a>
                </li>

                <li class="{{ request()->is('admin/comparisons*') ? 'active' : '' }}">
                    <a href="{{ route('admin.comparisons.index') }}">сomparisons_list</a>
                </li>
                @php
                $comparison = $comparison ?? null; // Проверяем, есть ли переменная
                @endphp
                <li class="{{ request()->is('admin/comparisons/*/products*') ? 'active' : '' }}">
                    @if($comparison)
                    <a href="{{ route('admin.comparison_products.index', ['comparison' => $comparison->id]) }}">
                        comparison_product
                    </a>
                    @else
                    <span class="text-muted">comparison_product (выберите сравнение)</span>
                    @endif
                </li>

                <li class="{{ request()->is('admin/cart*') ? 'active' : '' }}">
                    <a href="{{ route('admin.cart.index') }}">Корзина</a>
                </li>
                <li class="{{ request()->is('admin/orders*') ? 'active' : '' }}">
                    <a href="{{ route('admin.orders.index') }}">Заказы</a>
                </li>
                <li class="{{ request()->is('admin/order-items*') ? 'active' : '' }}">
                    <a href="{{ route('admin.order-items.redirect') }}">Элемент Заказа</a>
                </li>

                <li class="{{ request()->is('admin/reviews*') ? 'active' : '' }}">
                    <a href="{{ route('admin.reviews.index') }}">Отзывы</a>
                </li>
                {{-- <li class="{{ request()->is('admin/payments*') ? 'active' : '' }}">
                <a href="{{ route('admin.payments.index') }}">Оплата</a>
                </li> --}}

                <li class="{{ request()->is('admin/contacts*') ? 'active' : '' }}">
                    <a href="{{ route('admin.contacts.index') }}">Контакты</a>
                </li>
            </ul>
        </div>

        <!-- Основной контент -->
        <div class="content">
            @yield('content')
        </div>
    </div>


    {{-- @include('partials.footer') --}}

    <!-- Подключение JS файлов -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    {{-- <script src="{{ asset('js/slick.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('js/nouislider.min.js') }}"></script> --}}
    <script src="{{ asset('js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    {{-- <script src="{{ asset('js/admin/admin.js') }}"></script> --}}


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const links = document.querySelectorAll('.custom-sidebar ul li a');

            links.forEach(function(link) {
                if (window.location.href.indexOf(link.href) !== -1) {
                    link.classList.add('active'); // Добавляем класс active для ссылок, которые совпадают с текущим URL
                }

                link.addEventListener('click', function() {
                    links.forEach(function(link) {
                        link.classList.remove('active'); // Убираем активный класс с других ссылок
                    });
                    link.classList.add('active'); // Добавляем активный класс к кликнутой ссылке
                });
            });
        });
    </script>


</body>

</html>