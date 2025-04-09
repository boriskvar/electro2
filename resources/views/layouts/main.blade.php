<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой Интернет-магазин</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

    <!-- Vite: Подключение стилей -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Подключение CSS от дизайнера -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Подключение стилей для Vue Toastification -->
    <link rel="stylesheet" href="https://unpkg.com/vue-toastification@2.0.0-beta.1/dist/index.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    {{-- Вставляем сразу после <body>, чтобы сообщение было вверху страницы --}}
    @if (session('success'))
    <div class="alert alert-success" style="position: fixed; top: 20px; left: 50%; transform: translateX(-50%); z-index: 9999;">
        {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    {{-- @include('partials.header', ['menus' => $menus]) --}}
    @include('partials.header', ['wishlistCount' => $wishlistCount, 'mainMenu' => $mainMenu])

    <!-- Включаем общий шаблон магазина, который будет "обрамлять" контент -->
    {{-- @include('web.store')  <!-- Вставляем шаблон магазина --> --}}

    <!-- Основной контент -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Подключение JS от дизайнера -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('js/slick.min.js') }}"></script>
    <script src="{{ asset('js/nouislider.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
</body>

</html>