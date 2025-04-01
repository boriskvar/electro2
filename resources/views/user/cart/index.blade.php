@extends('layouts.main')
@section('content')

<body>
    <!--nghm-->
    <app-root>
        <modal class="large">
            <modal-layout class="modal-layout">
                <!--СТРОКА Заголовок модального окна -->
                <div class="custom-header">
                    <h2 class="h2">Корзина</h2>
                    <modal-close-btn class="close-modal">
                        <button class="close-button" (click)="closeModal()">
                            <span class="close-icon">&times;</span>
                        </button>
                    </modal-close-btn>
                </div>
                <!-- Контент модального окна -->
                <div class="custom-content"> {{-- нет в стилях --}}
                    <shopping-cart class="shopping-cart"> {{-- нет в стилях --}}
                        <div class="cart"> {{-- нет в стилях --}}

                            <!--СТРОКА  Выбрано 2 из 2 -->
                            <div class="cart-header">
                                <!-- Блок чекбокса и текста "Выбрать все" -->
                                <div class="cart-header__select-all">
                                    <div class="cart-header__checkbox-holder">
                                        <input type="checkbox" class="cart-header__checkbox" id="select-all">
                                        <label class="cart-header__checkbox-label" for="select-all"></label>
                                    </div>
                                    <span class="select-all-text">Выбрано 2 из 2</span>
                                </div>

                                <!-- Третий блок (сердечко) -->
                                <button class="button cart-header__wishlist">
                                    <svg width="23.998" height="23.998" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path class="wishlist-path" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" stroke="#ffa800" stroke-width="2" />
                                    </svg>
                                </button>
                                <!-- Четвертый блок (корзина) -->
                                <div class="cart-header__remove">
                                    <popup-menu>
                                        <button class="button cart-header__cart">
                                            <i class="fa fa-trash" style="color: #1E90FF;"></i>
                                        </button>
                                    </popup-menu>
                                </div>
                            </div>

                            <!--СТРОКА Список товаров в корзине -->
                            <cart-purchases>
                                <ul class="cart-list">
                                    @foreach ($cartItems as $item)
                                    @if ($item->product->in_stock == 1)
                                    <!-- Элемент списка товара -->
                                    <li class="cart-list__item">
                                        <cart-product>
                                            <div class="cart-product">
                                                <div class="cart-product__body">
                                                    <!--Первый бллок: для чекбокса -->
                                                    <div class="cart-product__checkbox">
                                                        <input type="checkbox" id="product-{{ $item->id }}" class="cart-product__checkbox__checkbox" data-item-id="{{ $item->id }}" data-in-stock="{{ $item->product->in_stock }}">
                                                        <label for="product-{{ $item->id }}" class="cart-product__checkbox__label"></label>
                                                    </div>

                                                    <!-- Второй блок: картинка -->
                                                    <div class="cart-product__picture">
                                                        <span class="cart-product__label">
                                                            @if ($item->discount_percentage > 0)
                                                            -{{ round($item->discount_percentage) }}%
                                                            @endif
                                                        </span>
                                                        <button-product-page>
                                                            <a href="{{ route('admin.products.show', $item->product_id) }}" title="{{ $item->product->name }}">
                                                                @php
                                                                $images = $item->product->images;
                                                                @endphp
                                                                @if ($images && isset($images[0]) && Storage::disk('public')->exists('img/' . basename($images[0])))
                                                                <img src="{{ asset('storage/img/' . basename($images[0])) }}" alt="{{ $item->product->name }}" style="max-width: 50px; height: auto;">
                                                                @else
                                                                <p>Изображение недоступно</p>
                                                                @endif
                                                            </a>
                                                        </button-product-page>
                                                    </div>

                                                    <!-- Третий блок: информация о товаре (продавец)-->
                                                    <div class="cart-product__main">
                                                        {{-- <button-product-page> --}}
                                                        <a href="{{ route('admin.products.show', $item->product->id) }}" class="product-link" title="{{ $item->product->name }}">
                                                            <span class="cart-product__title">{{ $item->product->name }}</span>
                                                        </a>
                                                        {{--
                                                            </button-product-page> --}}
                                                        <div class="cart-product__seller__title">
                                                            <span> Продавец: {{ $item->product->seller }} </span>
                                                        </div>

                                                    </div>

                                                    <!-- Четвертый блок: три точки-->
                                                    <popup-menu class="cart-product__actions">
                                                        <!-- Кнопка с тремя точками -->
                                                        <button class="menu-toggle-btn" aria-label="Открыть меню">
                                                            <svg class="icon-dots" width="24" height="24" aria-hidden="true">
                                                                <use href="/svg/icons.svg#icon-vertical-dots"></use>
                                                            </svg>
                                                        </button>

                                                        <!-- Блок с иконкой корзины и текстом "Удалить" -->
                                                        <div class="delete-option hidden">
                                                            <i class="fa fa-trash"></i>
                                                            <span class="delete-text">Удалить</span>
                                                        </div>

                                                        <!-- Вставка компонента -->
                                                        <!-- Блок с иконкой корзины и текстом "Удалить"  -->
                                                        <div class="delete-option hidden">
                                                            <div class="overlay-container">
                                                                <div class="overlay-backdrop"></div>
                                                                <div class="global-overlay-wrapper">
                                                                    <div class="overlay-content">
                                                                        <div class="menu-list-wrap" id="cartProductActions" aria-labelledby="cartProductActions">
                                                                            <ul class="menu-list">
                                                                                <li class="menu-item menu-item--delete">
                                                                                    <trash-icon>
                                                                                        <button type="button" class="menu-toggle-btn">
                                                                                            <i class="fa fa-trash"></i>
                                                                                            <span class="delete-text">Удалить</span>
                                                                                        </button>
                                                                                    </trash-icon>
                                                                                </li>
                                                                                <li class="menu-item menu-item--wishlist">
                                                                                    <wishlist-add-button>
                                                                                        <!-- Контент для кнопки добавления в избранное -->
                                                                                    </wishlist-add-button>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </popup-menu>

                                                </div>

                                                <!-- Следующий ряд: счетчик и цена -->
                                                <div class="cart-product__footer" id="footer-{{ $item->id }}">
                                                    <!-- Счетчик количества -->
                                                    <cart-counter class="cart-product__counter">
                                                        <div class="cart-counter">
                                                            <!-- Кнопка уменьшения количества -->
                                                            <button type="button" aria-label="Уменьшить количество" class="cart-counter__btn minus" id="minus-{{ $item->id }}">
                                                                <svg width="24" height="24" aria-hidden="true">
                                                                    <use href="/svg/icons.svg#icon-minus"></use>
                                                                </svg>
                                                            </button>
                                                            <!-- Значение количества -->
                                                            <input type="number" value="{{ $item->quantity }}" class="cart-counter__input" id="quantity-{{ $item->id }}">
                                                            <!-- Кнопка увеличения количества -->
                                                            <button type="button" aria-label="Увеличить количество" class="cart-counter__btn plus">
                                                                <svg width="24" height="24" aria-hidden="true">
                                                                    <use href="/svg/icons.svg#icon-plus"></use>
                                                                </svg>
                                                            </button>

                                                        </div>
                                                    </cart-counter>
                                                    <!-- Блок с ценами -->
                                                    <div class="cart-product__coast">
                                                        <p class="cart-product__old-price" id="old-price-{{ $item->id }}" data-base-old-price="{{ $item->product->old_price }}">
                                                            {{ round($item->product->old_price) }}
                                                            <span class="currency">₴</span>
                                                        </p>
                                                        <p class="cart-product__price--red" id="price-{{ $item->id }}" data-base-price="{{ $item->product->price }}">
                                                            {{ round($item->product->price) }}
                                                            <span class="currency">₴</span>
                                                        </p>
                                                    </div>
                                                </div>


                                            </div>
                                        </cart-product>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </cart-purchases>

                            <!-- Нижняя часть корзины -->
                            <div class="cart-footer">
                                <button class="cart-footer__continue">Продолжить покупки</button>

                                <div class="cart-receipt" id="cartReceipt" style="display: none;">
                                    <div class="cart-receipt__sum">
                                        <p class="cart-receipt__sum-label">Итого</p>
                                        <div class="cart-receipt__sum-price">
                                            <span>{{-- {{ round($totalPrice) }} --}}<span class="cart-receipt__sum-currency">₴</span></span>
                                        </div>
                                    </div>
                                    <checkout-button class="checkout-button">
                                        <a href="{{-- https://electro/admin/checkout --}}" class="cart-receipt__submit">
                                            <span data-testid="cart-receipt-submit-order">Оформить заказ</span>
                                        </a>
                                    </checkout-button>
                                </div>
                            </div>

                            <notification-list>
                                <ul></ul>
                            </notification-list>
                            <h4 class="cart-dummy__heading ng-star-inserted">Сейчас не в наличии</h4>


                            <!--СТРОКА Список товаров в корзине -->
                            <cart-purchases>
                                <ul class="cart-list">
                                    @foreach ($cartItems as $item)
                                    @if ($item->product->in_stock == 0)
                                    <!-- Элемент списка товара -->
                                    <li class="cart-list__item">
                                        <cart-product>
                                            <div class="cart-product cart-product--out-of-stock">
                                                <div class="cart-product__body">
                                                    <!--Первый бллок: для чекбокса -->
                                                    <div class="cart-product__checkbox">
                                                        <input type="checkbox" id="product-{{ $item->id }}" class="cart-product__checkbox__checkbox" data-item-id="{{ $item->id }}" data-in-stock="{{ $item->product->in_stock }}">
                                                        <label for="product-{{ $item->id }}" class="cart-product__checkbox__label"></label>
                                                    </div>

                                                    <!-- Второй блок: картинка -->
                                                    <div class="cart-product__picture">
                                                        <span class="cart-product__label">
                                                            @if ($item->discount_percentage > 0)
                                                            -{{ round($item->discount_percentage) }}%
                                                            @endif
                                                        </span>
                                                        <button-product-page>
                                                            <a href="{{ route('admin.products.show', $item->product_id) }}" title="{{ $item->product->name }}">
                                                                @php
                                                                $images =
                                                                $item->product->images;
                                                                @endphp
                                                                @if ($images && isset($images[0]) && Storage::disk('public')->exists('img/' . basename($images[0])))
                                                                <img src="{{ asset('storage/img/' . basename($images[0])) }}" alt="{{ $item->product->name }}" style="max-width: 50px; height: auto;">
                                                                @else
                                                                <p>Изображение недоступно</p>
                                                                @endif
                                                            </a>
                                                        </button-product-page>
                                                    </div>

                                                    <!-- Третий блок: информация о товаре (продавец)-->
                                                    <div class="cart-product__main">
                                                        {{-- <button-product-page> --}}
                                                        <a href="{{ route('admin.products.show', $item->product->id) }}" class="product-link" title="{{ $item->product->name }}">
                                                            <span class="cart-product__title">{{ $item->product->name }}</span>
                                                        </a>
                                                        {{--
                                                            </button-product-page> --}}
                                                        <div class="cart-product__seller__title">
                                                            <span> Продавец: {{ $item->product->seller }} </span>
                                                        </div>

                                                    </div>

                                                    <!-- Четвертый блок: три точки-->
                                                    <popup-menu class="cart-product__actions">
                                                        <!-- Кнопка с тремя точками -->
                                                        <button class="menu-toggle-btn" aria-label="Открыть меню">
                                                            <svg class="icon-dots" width="24" height="24" aria-hidden="true">
                                                                <use href="/svg/icons.svg#icon-vertical-dots"></use>
                                                            </svg>
                                                        </button>

                                                        <!-- Блок с иконкой корзины и текстом "Удалить" -->
                                                        <div class="delete-option hidden">
                                                            <i class="fa fa-trash"></i>
                                                            <span class="delete-text">Удалить</span>
                                                        </div>
                                                    </popup-menu>

                                                </div>

                                                <!-- Следующий ряд: счетчик и цена -->
                                                <div class="cart-product__footer" id="footer-{{ $item->id }}">
                                                    <!-- Счетчик количества -->
                                                    <cart-counter class="cart-product__counter">
                                                        <div class="cart-counter">
                                                            <!-- Кнопка уменьшения количества -->
                                                            <button type="button" aria-label="Уменьшить количество" class="cart-counter__btn minus" id="minus-{{ $item->id }}">
                                                                <svg width="24" height="24" aria-hidden="true">
                                                                    <use href="/svg/icons.svg#icon-minus"></use>
                                                                </svg>
                                                            </button>
                                                            <!-- Значение количества -->
                                                            <input type="number" value="{{ $item->quantity }}" class="cart-counter__input" id="quantity-{{ $item->id }}">
                                                            <!-- Кнопка увеличения количества -->
                                                            <button type="button" aria-label="Увеличить количество" class="cart-counter__btn plus">
                                                                <svg width="24" height="24" aria-hidden="true">
                                                                    <use href="/svg/icons.svg#icon-plus"></use>
                                                                </svg>
                                                            </button>

                                                        </div>
                                                    </cart-counter>
                                                    <!-- Блок с ценами -->
                                                    <div class="cart-product__coast">
                                                        <p class="cart-product__old-price" id="old-price-{{ $item->id }}" data-base-old-price="{{ $item->product->old_price }}">
                                                            {{ round($item->product->old_price) }}
                                                            <span class="currency">₴</span>
                                                        </p>
                                                        <p class="cart-product__price--red" id="price-{{ $item->id }}" data-base-price="{{ $item->product->price }}">
                                                            {{ round($item->product->price) }}
                                                            <span class="currency">₴</span>
                                                        </p>
                                                    </div>
                                                </div>


                                            </div>
                                        </cart-product>
                                    </li>
                                    @endif
                                    @endforeach
                                </ul>
                            </cart-purchases>

                        </div>
                    </shopping-cart>
                    <!---->
                </div>
            </modal-layout>
        </modal>
        <!---->
        <!---->
    </app-root>

</body>

<!-- Рекомендуемые товары -->
<style>
    /* Базовые стили  */
    /* Можно добавить дополнительные глобальные переменные и стили */
    /* :root { */
    /* --global-spacer: 4px; */
    /* --cart-picture-size: 96px; */
    /* --global-green: #00a046; */
    /* --global-black: #221f1f; */
    /* --global-black-10: #e9e9e9; */
    /* --global-black-20: #d2d2d2; */
    /* --global-blue: #3e77aa; */
    /* --global-light-blue: #31a3db; */
    /* --link-hover-color: #ff7878; */
    /* } */
    /* Сброс box-sizing и стандартных отступов */
    *,
    *:before,
    *:after {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    /* Устанавливаем начальные стили для всех элементов */
    html {
        font-size: 62.5%;
        /* Удобный расчет rem */
    }

    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        font-family: 'Montserrat', sans-serif;
        line-height: 1.5;
        color: #221f1f;
        background-color: #f5f5f5;
        -webkit-font-smoothing: antialiased;
        -webkit-tap-highlight-color: transparent;
        text-size-adjust: 100%;
        -webkit-text-size-adjust: 100%;
    }

    /* Переопределение других элементов */
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    blockquote,
    pre,
    a,
    abbr,
    acronym,
    address,
    big,
    cite,
    code,
    del,
    dfn,
    em,
    img,
    ins,
    kbd,
    q,
    s,
    samp,
    small,
    strike,
    strong,
    sub,
    sup,
    tt,
    var,
    b,
    u,
    i,
    center,
    dl,
    dt,
    dd,
    ol,
    ul,
    li,
    fieldset,
    form,
    label,
    legend,
    table,
    caption,
    tbody,
    tfoot,
    thead,
    tr,
    th,
    td,
    article,
    aside,
    canvas,
    details,
    embed,
    figure,
    figcaption,
    footer,
    header,
    hgroup,
    menu,
    nav,
    output,
    ruby,
    section,
    summary,
    time,
    mark,
    audio,
    video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
    }



    /* Контейнер модального окна */
    modal.large {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 16px;
        /* Затемнение фона */
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
    }

    /* Макет модального окна */
    .modal-layout {
        /* Адаптивная ширина с учетом отступов */
        width: calc(100% - 32px);
        /* Фиксированная ширина для больших экранов */
        max-width: 960px;
        height: auto;
        /* Гибкая максимальная высота */
        max-height: calc(100% - 32px);
        padding: 0 0 16px;
        background-color: #fff;
        /* Закругление углов */
        border-radius: 16px;
        /* Глубокая тень */
        box-shadow: 0 8px 40px rgba(0, 0, 0, 0.32);
        /* Для внутренних элементов */
        position: relative;
        box-sizing: border-box;
        /* Для предотвращения переполнения */
        overflow: hidden;
        /* Анимация появления */
        animation: modal-show 0.2s ease-in-out;
    }

    /* Анимация появления */
    @keyframes modal-show {
        0% {
            transform: scale3d(0.3, 0.3, 0.3);
            opacity: 0;
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    @media (max-width: 1023px) {
        .modal-layout {
            max-height: calc(100% - 16px);
        }
    }


    /* Шапка модального окна */
    .custom-header {
        border-bottom: 1px solid #221f1f;
        /* Используем переменную для цвета */
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        /* padding: 0 16px; */
        /* Добавляем отступы для контента */
        box-sizing: border-box;
        /* Убедимся, что размеры учитывают отступы */
    }

    /* Заголовок "Корзина" */
    .h2 {
        /* Уменьшаем размер шрифта */
        font-size: 18px;
        /* Устанавливаем более жирный шрифт, как у них */
        font-weight: 700;
        color: #221f1f;
        /* Добавляем более компактное межстрочное расстояние */
        line-height: 1.25;
        margin: 0;
        /* Применяем отступы со всех сторон для гибкости */
        padding: 16px;
        text-align: left;
        display: flex;
        align-items: center;
        /* Оставляем автоширину, чтобы заголовок мог растягиваться по содержимому */
        width: auto;
    }

    /* Родитель Блок Кнопки закрытия */
    .close-modal {
        width: 60px;
        height: 60px;
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        /* Это гарантирует, что кнопка будет справа */
        margin-left: auto;
    }

    /* Если хотите использовать их глобальный класс для выравнивания */
    .ms-auto {
        margin-left: auto;
    }

    /* Кнопка закрытия */
    .close-button {
        width: 60px;
        height: 60px;
        background: none;
        border: none;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        /* Убираем дополнительные отступы */
        padding: 0;
    }

    .close-button:hover {
        transform: scale(1.1);
    }

    /* Иконка кнопки закрытия */
    .close-icon {
        font-size: 24px;
        color: #797878;
        width: 24px;
        height: 24px;
        text-align: center;
        /* Для вертикального выравнивания */
        line-height: 24px;
        /* Сохраняем квадратную форму */
        aspect-ratio: 1 / 1;
        /* Плавное изменение цвета */
        transition: color 0.2s ease-in-out;
    }

    .close-button:hover .close-icon {
        color: #f84147;
    }

    /* окончание ститей 1-й строки "Корзина" */

    /*----2-------------- 2-я строка 2из2 -----------------------------------*/

    /* Основной контейнер контента */
    .custom-content {
        /* Заданная ширина */
        width: 100%;
        height: auto;
        max-height: 80vh;
        /* Ограничиваем высоту контента */
        padding: 16px 16px 0;
        /* Убираем любое кастомное позиционирование */
        /*border: none;*/
        /* Разрешаем прокрутку, если контента больше, чем помещается */
        overflow: auto;
    }

    /* Стили для полосы прокрутки */
    .custom-content::-webkit-scrollbar {
        width: 4px;
        background-color: transparent;
    }

    .custom-content::-webkit-scrollbar-thumb {
        background-color: #d2d2d2;
    }

    /* Стили для shopping-cart */
    .shopping-cart>* {
        display: block;
        /* Каждый элемент внутри занимает всю ширину */
        max-width: 100%;
        /* Ограничение ширины */
        /*box-sizing: border-box;*/
        /* Учитываем padding и border */
    }

    .shopping-cart * {
        /* Элементы внутри не шире контейнера */
        max-width: 100%;
        /* Учитывать padding и border */
        /*box-sizing: border-box;*/
    }

    /* Контейнер корзины */
    .cart {
        /* Размер шрифта */
        font-size: 14px;
        /* Межстрочное расстояние */
        line-height: 1.214;
        background-color: #ffffff;
        border-radius: 4px;
    }

    .cart-header {
        width: 896.01px;
        height: 40px;
        margin: 0 0 16px 0;
        display: flex;
        align-items: center;
        /* Это выравнивание по правому краю */
        justify-content: flex-end;
        /* Прозрачный фон для заголовка. */
        background: transparent;
        /* Обеспечивает, чтобы заголовок не закрывался другим содержимым */
        z-index: 10;
    }

    /* Блок чекбокса и текста */
    .cart-header__select-all {
        display: flex;
        align-items: center;
        margin-right: auto;
        font-size: 14px;
        padding-left: 16px;
    }

    /* Обертка для чекбокса */
    .cart-header__checkbox-holder {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 32px;
    }

    /* Настройки для чекбокса */
    input[type="checkbox"] {
        /* Отключаем стиль для WebKit */
        -webkit-appearance: none;
        /* Убирает оформление для Firefox */
        -moz-appearance: none;
        /* Убирает оформление для других браузеров */
        appearance: none;
        /* цвет фона */
        background-color: #e4eae6;
        /* рамка */
        border: 0.548px solid #999b9a;
        border-radius: 3px;
        width: 15.99px;
        height: 15.99px;
        cursor: pointer;
        /*transition: background-color 0.3s, border-color 0.3s;*/
        position: relative;
        /* Убираем стандартную рамку при фокусе */
        outline: none;
    }

    /* Когда на чекбокс наведён курсор */
    input[type="checkbox"]:hover {
        /* фон при наведении */
        background-color: #bfc2c0;
        /* цвет рамки при наведении */
        border-color: #090000;
        /* Светлая тень при фокусе */
        box-shadow: 0 4px 8px rgba(0, 160, 70, 0.5);
    }

    /* Когда чекбокс выбран  */
    input[type="checkbox"]:checked {
        background-color: #00a046;
        /* Отключаем рамку */
        outline: none;
        /* Убираем стандартный контур */
        border-color: transparent;
    }

    /* Убираем рамку при нажатии (активном состоянии), фокусе и при изменении состояния */
    input[type="checkbox"]:active,
    input[type="checkbox"]:focus {
        /* Убираем стандартный контур */
        outline: none !important;
        /* Отключаем рамку */
        border-color: transparent !important;
    }

    /* Псевдоэлемент Галочка в чекбоксе */
    input[type="checkbox"]:checked::after {
        content: "✔";
        position: absolute;
        /* Расстояние от левого края */
        left: 3px;
        /* Расстояние от верхнего края */
        top: -1px;
        font-size: 12px;
        color: #fff;
    }

    /* Текст "Выбрано 2 с 2" */
    .select-all-text {
        font-size: 14px;
        color: #221F1F;
    }

    /* Настройки для кнопок */
    .button {
        width: 35.993px;
        height: 40px;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    /* Сердечко */
    .wishlist-path {
        fill: #FFFFFF;
        stroke: #ffa800;
        transition: fill 0.3s, stroke 0.3s;
    }

    /* Сердечко активируется при добавлении класса */
    .cart-header__wishlist.active-wishlist .wishlist-path {
        fill: #ffa800;
        stroke: #ffa800;
    }


    /* Корзина */
    .cart-header__cart .fa-trash {
        font-size: 23.998px;
        color: #1E90FF;
        transition: color 0.3s;
    }

    .cart-header__cart:hover .fa-trash {
        color: #f84147 !important;
    }



    /* ------3-строка------------------------------------------------------- */


    /*     Список товаров в корзине */
    cart-purchases {
        width: auto;
        height: auto;

    }

    /* Стили для элементов списка корзины */
    .cart-list__item {
        border: 1px solid #e0e0e0;
        /* Светло-серый цвет границы */
        border-radius: 8px;
        /* Закруглённые углы */
        padding: 24px;
        /* Внутренний отступ */
        box-sizing: border-box;
        /* Учитываем padding и border */
    }

    /* Сброс первого элемента списка */
    .cart-list__item:first-child {
        margin-top: 0;
    }

    /* ------3-большой блок (общая рамка товара) ----------------------------------------- */
    .cart-product--out-of-stock {
        opacity: 0.5;
        /* Затемняем товар */
        pointer-events: none;
        /* Блокируем все клики */
        /* position: relative; Для позиционирования "трёх точек" и меню */
    }

    .cart-product--out-of-stock .menu-toggle-btn {
        pointer-events: auto !important;
        /* Разрешаем клики для "три точки" */
        /* cursor: pointer; */
    }

    .cart-product--out-of-stock .menu-toggle,
    /* .cart-product--out-of-stock .delete-option */
        {
        pointer-events: auto;
        /* Разрешаем клики для "три точки" и кнопки "Удалить" */
        z-index: 1;
        /* Выводим элементы поверх */
        position: relative;
    }

    .cart-product--out-of-stock .delete-option {
        pointer-events: auto;
        /* Разрешаем клики */
        z-index: 2;
        /* Убедитесь, что значение выше, чем у других элементов */
        /* position: relative; Если нужно выровнять элемент */
    }

    .cart-product__body {
        display: flex;
        /* align-items: center; */
        align-items: stretch;
        /* Устанавливает одинаковую высоту вложенных элементов */
        position: relative;
        padding: 0;
        width: 100%;
        box-sizing: border-box;
    }

    .cart-product__body>* {
        height: auto;
        /* Наследует максимальную высоту автоматически */
    }

    /* ------3.1-блок первый------------------------------------------------------- */
    /* Основной блок с чекбоксом */
    .cart-product__checkbox {
        display: flex;
        flex-direction: column;
        justify-content: center;
        margin: 0 32px 0 0;
        width: 12px;
        height: 96px;
    }

    /* Стиль для самого чекбокса (скрыт и стилизуется через псевдоэлемент) */
    /* Скрываем сам чекбокс */
    .cart-product__checkbox__checkbox {
        -webkit-appearance: none !important;
        -moz-appearance: none !important;
        appearance: none !important;
        position: absolute !important;
        /* Позиционирование чекбокса */
        opacity: 0 !important;
        /* Скрываем чекбокс */
        width: 0 !important;
        /* Ширина 0 */
        height: 0 !important;
        /* Высота 0 */
        padding: 0 12px !important;
        /* Паддинги слева и справа по 12px */
        margin: 3px 3px 3px 4px !important;
        /* Отступы с учетом их стилей */
        border: none !important;
        /* Убираем границу */
    }

    /* Стиль для метки (label), которая выступает как видимый чекбокс */
    /* Метка для отображения кастомного чекбокса */
    .cart-product__checkbox__label {
        position: relative;
        cursor: pointer;
        display: inline-block;
        margin: 0 0 8px 0;
        width: 11.995px;
        height: 0;
        padding: 0;

    }

    /* Псевдоэлемент ::before для создания видимого чекбокса */
    /* Видимый квадрат чекбокса, стилизованный через ::before */
    .cart-product__checkbox__label::before {
        content: "";
        display: inline-block;
        position: absolute;
        /* вертикальная позиция */
        top: 2px;
        /* горизонтальная позиция */
        left: 0px;
        /* ширина псевдоэлемента чекбокса */
        width: 15.99px;
        /* высота псевдоэлемента */
        height: 15.99px;
        /* ширина рамки и цвет */
        border: 0.548px solid #d2d2d2;
        border-radius: 4px;
        background-color: #fff;
        transition: background-color 0.2s ease-in-out, border 0.2s ease-in-out;
        box-sizing: border-box;
    }

    /* Эффект при наведении */
    .cart-product__checkbox__label:hover::before {
        /* Темнеет фон при наведении */
        background-color: #f0f0f0;
        /* Темнеет граница при наведении */
        border-color: #888;
    }

    /* Стилизация при активации чекбокса */
    .cart-product__checkbox__checkbox:checked+.cart-product__checkbox__label::before {
        /* цвет при активном состоянии */
        background-color: #00a046;
        border-color: #00a046;
    }

    /* Белая галочка внутри чекбокса при отметке */
    .cart-product__checkbox__checkbox:checked+.cart-product__checkbox__label::before {
        /* Юникод для галочки */
        content: "\2714";
        /* Цвет галочки */
        color: #fff;
        /* Размер галочки */
        font-size: 14px;
        /* Центрируем галочку */
        text-align: center;
        line-height: 15.99px;
        /* Цвет фона при активном чекбоксе */
        background-color: #00a046;
        /* Цвет рамки при активном чекбоксе */
        border: 0.548px solid #00a046;
    }

    /* ----3.2 --------------------- */
    /* Картинка родитель */
    .cart-product__picture {
        width: 96px;
        height: 96px;
        max-width: 100%;
        flex-shrink: 0;
        /* предотвращает сжатие */
        overflow: hidden;
        margin: 0 16px 0 0;
    }

    /* Стили для картинки внутри блока */
    .cart-product__picture img {
        width: 100%;
        /* Картинка будет занимать всю ширину блока */
        height: 100%;
        /* Картинка будет занимать всю высоту блока */
        object-fit: contain;
        /* Заполняет контейнер, обрезая лишнее */
    }

    /* Скидка */
    .cart-product__label {
        position: absolute;
        top: 0;
        /* Ставим сверху */
        left: 0;
        /* Ставим слева */
        background-color: #f84147;
        /* Цвет фона, как у Розетки */
        color: #fff;
        /* Белый цвет текста */
        font-size: 8px;
        /* Размер шрифта, как у Розетки */
        font-weight: 700;
        /* Жирное начертание */
        padding: 0 8px;
        /* Отступы по бокам, как у Розетки */
        height: 16px;
        /* Высота, как у Розетки */
        line-height: 16px;
        /* Выравнивание текста по вертикали */
        border-radius: 50px;
        /* Скругленные углы, как у Розетки */
        text-transform: uppercase;
        /* Преобразование текста в верхний регистр */
        display: inline-block;
        /* Чтобы элемент был строчным с блоковыми характеристиками */
    }

    button-product-page {
        display: flex;
        /* Поддерживает выравнивание содержимого */
        align-items: center;
        /* Вертикальное центрирование содержимого */
        justify-content: center;
        /* Горизонтальное центрирование содержимого */
        width: 100%;
        /* Наследует ширину от родителя */
        height: 100%;
        /* Наследует высоту от родителя */
        background-color: #fff;
        /* Задайте цвет фона */
        color: #fff;
        /* Цвет текста */
        font-size: 14px;
        /* Размер шрифта */
        border: none;
        /* Без рамки */
        border-radius: 4px;
        /* Закругленные углы */
        cursor: pointer;
        /* Указатель при наведении */
        transition: background-color 0.3s ease, transform 0.3s ease;
        /* Эффекты при наведении */
    }

    a.product-link {
        /* color: #3e77aa; Цвет текста ссылки */
        cursor: pointer;
        /* Указывает, что элемент кликабельный */
        text-decoration: none;
        /* Убирает подчеркивание текста */
        transition: color 0.3s ease;
        /* Плавный переход цвета при наведении */
    }

    button-product-page p {
        font-size: 12px;
        /* Размер шрифта для текста */
        color: #999;
        /* Цвет текста для сообщения о недоступности изображения */
        margin-top: 10px;
        /* Отступ сверху */
    }

    /* ---3.3.1 (контейнер)------------------------- */
    /* Стили для ссылки с подчеркиванием */

    /* Основной контейнер для cart-product__main */
    .cart-product__main {
        flex-grow: 1;
        /* margin-right: 16px; */
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    /* Стили для ссылки на продукт */

    .product-link {
        font-size: 14px;
        /* Размер шрифта */
        color: #000;
        /* Цвет текста */
        text-decoration: none;
        /* Убираем подчеркивание */
        margin-bottom: 2px;
        /* Уменьшаем отступ */
    }

    /* Hover-эффект для подчеркивания ссылки */
    .product-link:hover {
        text-decoration: underline;
        /* Подчеркиваем при наведении */
    }

    /* Стили для заголовка продавца */
    .cart-product__seller__title {
        font-size: 12px;
        /* Размер шрифта */
        color: #888;
        /* Цвет текста (цвет ссылки у Rozetka) */
        margin-top: 0;
        /* Убираем лишние отступы сверху */
        /* line-height: 1.2; */
        /* Уменьшаем высоту строки для компактности */
        padding: 0 0 12px 0;
    }

    /* ---- 3.3.2---- иконка (три точки) последний блок строки товара ---------------- */
    /* Cart product actions container */
    .cart-product__actions {
        display: flex;
        /* Use flex layout */
        align-items: center;
        /* Vertically center content */
        justify-content: flex-end;
        /* Align items to the right */
        position: relative;
        /* Allow positioning */
        width: 40px;
        /* Set container width */
        height: 40px;
        /* Set container height */
    }

    /* Styles for the menu toggle button */
    .menu-toggle-btn {
        background: none;
        /* Remove background */
        border: none;
        /* Remove border */
        cursor: pointer;
        /* Pointer cursor on hover */
        display: flex;
        /* Use flex layout */
        align-items: center;
        /* Vertically center content */
        justify-content: center;
        /* Horizontally center content */
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        /* Убираем лапку с кнопки */
        cursor: default;
        /* Убираем изменение цвета на кнопке с тремя точками */
        /* transition: none; */
        /*transition: fill 0.2s ease;*/
        pointer-events: auto;
    }

    /* Синие три точки и изменение цвета на красный при наведении */
    .icon-dots {
        /* Синий цвет по умолчанию */
        fill: #1E90FF;
        /*transition: fill 0.2s ease;*/
        /* Убираем эффект при наведении */
    }

    .menu-toggle-btn:hover .icon-dots {
        fill: red;
        /* Красный при наведении */
    }

    /* Блок для опции "Удалить", скрыт по умолчанию */
    .delete-option {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        position: absolute;
        top: 0;
        /* Чтобы блок появился левее */
        left: -100px;
        transition: color 0.2s ease;
    }

    /* Скрытие блока по умолчанию */
    .hidden {
        display: none;
    }

    /* Иконка мусорного ведра */
    .fa-trash {
        /* Синий мусорного ведра */
        color: #1E90FF;
        transition: color 0.2s ease;
    }

    .delete-text {
        /* Синий цвет текста "Удалить" */
        color: #1E90FF;
        font-size: 14px;
        transition: color 0.2s ease;
    }

    /* Красный цвет текста и иконки при наведении на блок "Удалить" */
    .delete-option:hover .fa-trash,
    .delete-option:hover .delete-text {
        color: #f84147;
        /* Красный цвет */
    }

    /* Лапка для блоков "Удалить" */
    .delete-option:hover {
        cursor: pointer;
    }

    /* --------стили из компонента -------------------- */
    .overlay-container {
        position: fixed;
        z-index: 1000;
        width: 530px;
        /* Ширина для теста */
        height: 443px;
        /* Высота фиксирована */
        /* border: 2px dashed red; Рамка для теста */
    }

    .overlay-backdrop {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* background-color: rgba(0, 0, 0, 0.5); */
        /* border: 20px dashed blue; Рамка для теста */
    }

    .global-overlay-wrapper {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        /* border: 4px dashed green; Рамка для теста */
    }

    .overlay-content {
        position: absolute;
        /* Позволяет использовать top/right для точного позиционирования */
        top: 173px;
        /* Отступ сверху */
        right: 85px;
        /* Отступ справа */
        width: 250px;
        /* Ширина */
        height: 56px;
        /* Высота */
        pointer-events: auto;
        /* Включает интерактивность */
        z-index: 1000;
        /* Слой */
        display: flex;
        /* Гибкое размещение дочерних элементов */
        max-width: 100%;
        /* Максимальная ширина для предотвращения переполнения */
        max-height: 100%;
        /* Максимальная высота */
        box-sizing: border-box;
        /* Учитывает границы и паддинги в размере */
        /* border: 2px dashed orange; Временная рамка для теста */
        transform: translateX(-330px) translateY(40px);
        /* Сдвигает всё содержимое вниз */

    }

    .menu-list-wrap {
        z-index: 102;
        /* Уровень слоя */
        width: 250px;
        /* Фиксированная ширина */
        border-radius: 8px;
        /* Закругленные углы */
        background-color: #fff;
        /* Белый фон */
        box-shadow: 0 2px 4px rgba(255, 223, 0, 0.4);
        /* Жёлтая тень */

        padding: 8px 16px;
        /* Внутренние отступы */
        box-sizing: border-box;
        /* Учитываем padding в размерах */
        /* border: 2px solid purple; Временная рамка для теста */

    }


    ul.menu-list {
        list-style: none;
        /* Убираем стандартные маркеры списка */
        margin: 0;
        padding: 0;
        display: flex;
        /* Для возможности выравнивания содержимого */
        flex-direction: column;
        /* Вертикальное расположение элементов */
        gap: 5px;
        /* Расстояние между элементами */
        width: 100%;
        /* Ширина списка */
        /* height: 40px; Устанавливаем высоту 40 пикселей */
    }

    li.menu-item--delete {
        all: unset;
        /* Сбрасываем все стили */
        display: flex;
        /* Восстанавливаем flex-поведение */
        align-items: center;
        justify-content: flex-start;
        width: 218px;
        /* Ширина для теста */
        height: 40px;
        /* Высота */
        box-sizing: border-box;
        /* transform: translateX(-100px) translateY(100px); Корректировка смещения по горизонтали и вертикали */
    }


    .menu-item {
        display: flex;
        align-items: center;
        /* Центрируем содержимое по вертикали */
        justify-content: start;
        /* Содержимое выравнивается по левому краю */
        width: 218px;
        /* Ширина элемента */
        padding: 0;
        /* Убираем внутренние отступы */
        margin: 0;
        /* Убираем внешние отступы */
        background-color: #f9f9f9;
        /* Фон элемента */
        box-sizing: border-box;
        /* Учитываем отступы и границы в размере */
        overflow: hidden;
        /* Прячем содержимое, выходящее за границы */
        transition: width 0.3s ease;
        /* Плавное изменение ширины при необходимости */
    }

    .menu-item--delete {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        /* Содержимое прижато к началу */
        width: 218px;
        /* Фиксированная ширина родителя */
        height: 40px !important;
        /* Принудительная высота */
        padding: 0;
        margin: 0;
        box-sizing: border-box;
        /* Учитываем padding и border */
        overflow: hidden;
        /* Прячем избыточное содержимое */
    }

    trash-icon {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        width: 100%;
        /* Подстраивается под ширину родителя */
        height: 100%;
        /* Подстраивается под высоту родителя */
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    .fa-trash {
        color: #1E90FF;
    }

    .menu-list .menu-item--delete .menu-toggle-btn {
        width: 100%;
        /* Заполняет ширину родителя */
        height: 40px;
        /* Фиксированная высота */
        line-height: 40px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        background: none;
        border: none;
        padding: 0;
        margin: 0;
        font-size: 16px;
        /* Средний размер текста */
        color: #3e77aa;
        /* Цвет текста */
        cursor: pointer;
        position: relative;
        /* Важно для иконок */
        text-decoration: none;
        border-radius: 8px;
        /* Радиус углов */
        box-sizing: border-box;
    }

    .menu-list .menu-item--delete .menu-toggle-btn {
        font-size: 22px;
        /* Размер шрифта */
        line-height: 1.5;
        /* Высота строки */
        padding: 0 8px;
        /* Внутренние отступы */
        display: flex;
        align-items: center;
        /* Выравнивание текста и иконки */
    }


    /* Наведение */
    .menu-list .menu-item--delete .menu-toggle-btn:hover {
        color: #f84147;
        /* красный цвет при наведении */
    }

    .menu-toggle-btn:hover i {
        color: inherit !important;
        /* унаследует цвет родителя */
        /* fill: #f84147 !important; изменяет цвет заливки для SVG */
    }

    /* Стили иконки */
    .menu-list .menu-item--delete .menu-toggle-btn i {
        width: 23px;
        /* Устанавливаем ширину корзины */
        height: 23px;
        /* Устанавливаем высоту корзины */
        font-size: 23px;
        /* Обеспечивает правильный масштаб для Font Awesome */
        display: inline-block;
        /* Поддержка размеров */
        margin-right: 8px;
        /* отступ справа от иконки */
        color: inherit;
        /* унаследует цвет родителя */
        /* fill: inherit; для SVG-иконки, чтобы изменить её цвет */
    }

    .menu-list .menu-item--delete .menu-toggle-btn i.fa-trash {
        flex-shrink: 0;
        /* Запрещает сжатие элемента */
        margin-right: 12px;
        /* Добавляет отступ справа */
    }

    /* Стили текста внутри кнопки удаления */
    .menu-list .menu-item--delete .menu-toggle-btn .delete-text {
        font-size: 16px;
        /* Увеличиваем размер шрифта */
        line-height: 1.5;
        /* Высота строки */
        /* font-weight: bold; Добавьте при необходимости */
    }

    .menu-item--wishlist {
        width: 0;
        /* Устанавливаем ширину в 0, чтобы скрыть элемент */
        background-color: #d4edda;
        /* Светлый зеленый фон */
        color: #155724;
        /* Тёмный зеленый текст */
        overflow: hidden;
        /* Прячем содержимое */
    }




    /* --------окончание стилей из компонента -------------------- */
    /* СТРОКА ОБЩЕЙ ЦЕНЫ И СЧЕТЧИКА */
    /* Нижний блок с количеством и ценой */


    /* Общие стили для блока .cart-product__footer */
    .cart-product__footer {
        display: flex !important;
        flex-direction: row;
        flex-wrap: nowrap;
        /* Предотвращает разрыв на две строки */
        /* justify-content: space-between; */
        justify-content: flex-end !important;
        /* border: 1px solid red; */
        /* Временный бордер для проверки границ */
        /* Выравнивание элементов влево */
        align-items: center;
        /* Центрируем элементы по вертикали */
        padding: 0;
        /* Убираем отступы */
        box-sizing: border-box;
        /* Учитываем отступы и границы в общей высоте */
        height: 40px;
        /* Фиксируем высоту */
        width: 100%;
        /* Растягиваем на всю ширину */
    }

    .cart-product__footer>* {
        flex-shrink: 0;
        /* Предотвращает сжатие элементов */
    }



    .cart-product__counter,
    .cart-product__coast {
        flex-shrink: 0;
        /* Устанавливает фиксированный размер */
    }

    /* Контейнер для счётчика */
    .cart-product__counter {
        display: flex;
        justify-content: center;
        /* Центрируем содержимое */
        align-items: center;
        /* По вертикали */
        width: 144px;
        /* Фиксированная ширина */
        height: 40px;
        /* Фиксированная высота */
        background-color: #f5f5f5;
        /* Временный фон для визуализации */
        box-sizing: border-box;
        /* Учитываем границы в размерах */
    }

    /* Внутренний контейнер для кнопок и поля*/
    .cart-counter {
        display: flex;
        flex-direction: row;
        align-items: center;
        /* Центровка содержимого по вертикали */
        width: 144px;
        height: 40px;
    }

    /* Кнопки (уменьшение/увеличение) */
    .cart-counter__btn {
        width: 40px;
        height: 40px;
        display: flex;
        justify-content: center;
        align-items: center;
        border: none;
        /* Убираем границу */
        background-color: transparent;
        /* Убираем фон */
        padding: 0;
        /* Убираем внутренние отступы */
        margin: 0;
        /* Убираем внешние отступы */
        position: relative;
        /* Устанавливаем позицию для контроля */
        box-sizing: border-box;
        /* Учитываем границы в размерах */
        cursor: pointer;
        /* Указатель */
        /* color: #d2d2d2;Цвет по умолчанию */
    }


    /* Стиль для иконки "-" */
    .cart-counter__btn.minus {
        color: #d2d2d2;
        /* Цвет для иконки "-" при одном товаре */
    }

    .cart-counter__btn.minus.active {
        color: #3e77aa;
        /* Цвет для иконки "-" при количестве больше одного */
    }

    /* Цвет иконки внутри кнопки "-" */
    .cart-counter__btn.minus.active svg path {
        fill: currentColor;
        /* Используем цвет текста для иконки */
    }

    /* Стиль для иконки "+" (без зависимости от количества) */
    .cart-counter__btn.plus {
        color: #3e77aa;
        /* Цвет для иконки "+" */
    }


    /* Применение цвета к иконке внутри кнопки */
    .cart-counter__btn svg path {
        fill: currentColor;
        /* Привязываем цвет иконки к свойству color */
    }

    /* Поле ввода количества */


    /* Стили для поля ввода количества */
    .cart-counter__input {
        width: 56px;
        /* Внешняя ширина */
        height: 40px;
        /* Высота */
        margin: 0 4px;
        /* Внешние отступы */
        padding: 1px 12px;
        /* Внутренние отступы */
        border: 0.552px solid #d2d2d2;
        /* Рамка */
        border-radius: 8px;
        /* Скругление углов */
        background-color: #fff;
        /* Цвет фона */
        font-size: 16px;
        /* Размер текста */
        text-align: center;
        /* Выравнивание текста */
        outline: none;
        /* Убираем стандартную подсветку */
        box-sizing: border-box;
        /* Учитываем рамку и отступы */
        transition: border 0.2s ease-in-out;
        /* Плавный переход для изменения рамки */
        -moz-appearance: textfield;
        /* Убираем стрелки в Firefox */
    }

    /* Убираем стрелки в Chrome, Safari, Edge */
    .cart-counter__input::-webkit-inner-spin-button,
    .cart-counter__input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Стили при фокусе */
    .cart-counter__input:focus {
        border-color: #3e77aa;
        /* Синий цвет рамки при фокусе */
    }

    .cart-counter__input:hover {
        border-color: #797878;
        /* Основной цвет рамки */
        border-width: 1px;
        /* Тонкая рамка */
        border-radius: 6px;
        /* Увеличенное скругление углов */
        /* Светлая тень для углов */
        box-shadow: 0 0 2px rgba(34, 31, 31, 0.4),
            /* Светлая тень по бокам */
            inset 0 0 2px rgba(255, 255, 255, 0.8);
        /* Светлая внутренняя тень для дополнительного светлого эффекта */
    }

    .cart-counter__input[type=number] {
        -moz-appearance: textfield;
    }

    /*   ПРАВЫЙ Блок с ценами   */
    .cart-product__coast {
        display: flex;
        /* Включаем flexbox */
        flex-direction: column;
        /* Располагаем элементы в колонку */
        justify-content: center;
        /* Центруем содержимое по вертикали */
        text-align: right;
        /* Текст выравнивается по правому краю */
        /* margin-left: 174px; */
        /* Отступ слева */
        padding: 0 16px;
        /* Внутренние отступы (по бокам) */
        width: 81px;
        /* Внешняя ширина */
        height: 40px;
        /* Внешняя высота */
        max-width: 40%;
        /* Ограничиваем максимальную ширину */
        box-sizing: border-box;
        /* Включаем отступы в размер */
    }


    .cart-product__old-price {
        font-size: 12px;
        line-height: 1;
        /* Точное соответствие высоты строки */
        text-decoration: line-through;
        /* Перечёркивание текста */
        color: #a6a5a5;
        ;
        /* Цвет текста (серый с прозрачностью) */
        width: 49px;
        /* Фиксированная ширина */
        height: 14px;
        /* Фиксированная высота */
        margin: 0;
        /* Убираем внешние отступы */
        box-sizing: border-box;
        /* Учитываем размеры */
    }

    .currency {
        margin-left: 2px;
        /* Отступ слева */
        display: inline;
        /* Оставляем "встроенный" тип отображения */
        vertical-align: middle;
        /* Центрирование по вертикали */
        line-height: inherit;
        /* Наследование высоты строки для согласованности */
        box-sizing: content-box;
        /* Учитываем только содержимое, без внутренних отступов */
    }

    .cart-product__price--red {
        color: #e53935;
        font-size: 20px;
        /* Размер шрифта */
        font-weight: bold;
        line-height: 24px;
        /* Высота строки для соответствия высоте блока */
        white-space: nowrap;
        /* Запрещаем перенос текста */
        display: inline-block;
        /* Обеспечиваем гибкость при выравнивании */
        width: 49px;
        /* Внешняя ширина */
        height: 24px;
        /* Внешняя высота */
        box-sizing: border-box;
        /* Учитываем отступы в расчёте размера */
    }



    /* ------------------------------------------------------------------------------------- */
    /* СЛЕДУЮЩАЯ СТРОКА ОБЩАЯ ФУТЕР */
    /* Стили для футера корзины (div.cart-footer) */

    .cart-footer {
        position: sticky;
        /* Фиксируем элемент */
        bottom: -16px;
        /* Сдвиг вниз */
        display: flex;
        /* Включаем flexbox */
        flex-direction: row;
        /* Располагаем элементы в строку */
        flex-wrap: wrap;
        /* Разрешаем перенос */
        align-items: center;
        /* Выравниваем элементы по центру */
        padding: 16px 0 24px 0;
        /* Внутренние отступы */
        width: auto;
        /* Устанавливаем ширину */
        height: auto;
        /* Внешняя высота */
        background-color: #fff;
        /* Белый фон */
        box-sizing: border-box;
        /* Включаем отступы в размер */
    }

    /* Кнопка "Продолжить покупки" */
    /* Кнопка "Продолжить покупки" */
    .cart-footer__continue {
        min-width: 191px;
        /* Минимальная ширина кнопки */
        height: 40px;
        /* Внешняя высота */
        padding: 1px 16px 1px 16px;
        /* Внутренние отступы */
        background-color: #f5f5f5;
        /* Фон */
        color: #3e77aa;
        /* Цвет текста */
        border: none;
        /* Без границ */
        border-radius: 8px;
        /* Закруглённые углы */
        font-size: 16px;
        /* Размер шрифта */
        font-weight: 500;
        /* Толщина шрифта */
        line-height: 40px;
        /* Для центрирования текста по вертикали */
        text-align: center;
        /* Выравнивание текста по центру */
        box-shadow: inset 0 0 0 1px #ebebeb;
        /* Внутренняя тень */
        cursor: pointer;
        /* Указатель при наведении */
        position: relative;
        /* Для возможных абсолютных вложенных элементов */
        transition: background-color 0.2s, color 0.2s;
        /* Плавное изменение при наведении */
        white-space: nowrap;
        /* Запрещаем перенос строки */
    }


    @media (min-width: 768px) {
        .cart-footer__continue {
            display: block;
            /* Показываем кнопку на экранах шире 768px */
        }
    }

    .cart-footer__continue:hover {
        background-color: #e0e0e0;
        /* Цвет фона при наведении */
        color: #005a8e;
        /* Цвет текста при наведении */
    }

    /* ------------------------------------------------------------ */

    /* Стили для контейнера "Оформить заказ" (div.cart-receipt) */

    /* Стили для контейнера "Оформить заказ" (div.cart-receipt) */

    /* div.cart-receipt {
                                                            display: flex !important;
                                                            } */

    .cart-receipt {
        display: flex;
        flex-direction: row;
        /* Ориентация элементов по горизонтали */
        align-items: center;
        /* Центрируем элементы по вертикали */
        justify-content: flex-start;
        /* Текст и кнопка идут в строку */
        width: auto;
        /* Ширина автоматически */
        padding: 24px;
        /* Паддинг для центрирования содержимого */
        background-color: rgba(0, 255, 0, 0.1);
        /* Для проверки Полупрозрачный фон */
        border: 0.552px solid green;
        /* Для проверки Граница */
        border-radius: 8px;
        /* Закругленные углы */
        box-sizing: border-box;
        /* Учитываем отступы в ширине */
        margin-left: auto;
        /* Прижимаем вправо */
    }

    /* Внутренний блок суммы (div.cart-receipt__sum) */
    .cart-receipt__sum {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        margin-right: 24px;
        /* Отступ справа для раздвижения */
    }

    /* Лейбл "Итого" (p.cart-receipt__sum-label) */
    .cart-receipt__sum-label {
        font-size: 20px;
        display: block;
        /* Для устройств с шириной экрана меньше 768px */
    }

    @media (min-width: 768px) {
        .cart-receipt__sum-label {
            display: none;
            /* Скрываем текст "Итого" на больших экранах */
        }
    }

    /* Блок цены (div.cart-receipt__sum-price) */
    .cart-receipt__sum-price {
        margin-left: auto;
        /* Выравниваем по правому краю */
        font-size: 20px;
        /* Размер текста по умолчанию */
        display: flex;
        /* Для упрощения размещения дочерних элементов */
        align-items: center;
        /* Вертикальное центрирование */
        justify-content: flex-end;
        /* Выравниваем содержимое вправо */
    }

    @media (min-width: 768px) {
        .cart-receipt__sum-price {
            font-size: 24px;
            /* Увеличиваем размер текста на экранах >=768px */
            margin-left: 0;
            /* Убираем отступ слева */
        }
    }

    @media (min-width: 1280px) {
        .cart-receipt__sum-price {
            font-size: 36px;
            /* Увеличиваем текст на больших экранах */
        }
    }

    .cart-receipt__sum-price>span {
        font-size: inherit;
        /* Наследуем размер шрифта от родителя */
        /* font-weight: bold; */
        /* Подчёркиваем важность суммы */
        line-height: 1;
        /* Оптимальная высота строки */
        white-space: nowrap;
        /* Предотвращаем перенос текста на другую строку */
    }

    /* Значок валюты (span.cart-receipt__sum-currency) */
    .cart-receipt__sum-currency {
        font-family: inherit;
        /* Использует тот же шрифт, что и число */
        font-size: 24px;
        /* Совпадает с числом */
        margin-left: 4px;
    }

    @media (min-width: 1280px) {
        .cart-receipt__sum-price {
            font-size: 36px;
            /* Размер шрифта для цены */
        }

        .cart-receipt__sum-currency {
            font-size: 36px;
            /* То же значение для значка гривни */
            margin-left: 4px;
            /* Отступ для значка */
        }
    }

    /* ---------------------- */
    .checkout-button {
        display: inline-block;
        /* Контейнер для кнопки */
    }


    /* Стили для кнопки "Оформить заказ" */


    /* Общие стили для кнопки */
    .cart-receipt__submit {
        position: relative;
        /* или absolute, если нужно точное позиционирование */
        display: inline-block;
        width: 172px;
        /* Внешняя ширина */
        height: 48px;
        /* Высота */
        padding: 0 16px;
        /* Внутренние отступы */
        background-color: #00a046;
        /* Цвет фона */
        color: #fff;
        /* Цвет текста */
        border-radius: 8px;
        /* Закругленные углы */
        text-align: center;
        font-size: 18px;
        /* Размер шрифта */
        line-height: 48px;
        /* Выравнивание по высоте */
        text-decoration: none;
        /* Убираем подчеркивание */
        cursor: pointer;
        /* Курсор при наведении */
        transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
    }

    /* Стили для при наведении */
    .cart-receipt__submit:hover {
        background-color: #008c34;
        /* Темный оттенок при наведении */
        color: #e0e0e0;
        /* Цвет текста при наведении */
    }

    /* Ссылка внутри кнопки (a.cart-receipt__submit) */
    .cart-receipt__submit a {
        display: block;
        width: 100%;
        height: 100%;
        color: inherit;
        text-decoration: none;
        line-height: 48px;
        text-align: center;
    }

    /* Текст в кнопке ("span.cart-receipt-submit-order") */
    .cart-receipt__submit span {
        font-size: 16px;
        line-height: 1;
        text-align: center;
    }

    /* Стили для кнопки при наведении */
    /* .cart-receipt__submit:hover {
                                                                    background-color: #73c592;
                                                                    color: #fff;
                                                                } */

    .cart-receipt__submit a:hover {
        color: inherit;
        color: #fff;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Константы для элементов
        const selectAllCheckbox = document.querySelector('#select-all');
        const checkboxes = document.querySelectorAll('.cart-product__checkbox__checkbox');
        const wishlistButton = document.querySelector('.cart-header__wishlist');
        const cartReceipt = document.getElementById('cartReceipt');
        const selectAllText = document.querySelector('.select-all-text');
        const deleteSelectedBtn = document.querySelector('.cart-header__cart'); // Кнопка удаления выбранных товаров
        const menuToggleBtns = document.querySelectorAll('.menu-toggle-btn');

        // Ищем кастомный элемент modal-close-btn
        const closeButtonElement = document.querySelector('modal-close-btn');

        if (closeButtonElement) {
            // Проверяем, есть ли у него Shadow DOM
            const shadowRoot = closeButtonElement.shadowRoot;
            const closeButton = shadowRoot ? shadowRoot.querySelector('.close-button') : closeButtonElement.querySelector('.close-button');

            if (closeButton) {
                closeButton.addEventListener('click', function() {
                    const modal = document.querySelector('modal');
                    if (modal) {
                        modal.style.display = 'none'; // Закрываем модалку
                    }

                    // Возвращаемся на предыдущую страницу
                    history.back();
                });
            } else {
                console.warn('Кнопка закрытия не найдена внутри modal-close-btn');
            }
        } else {
            console.warn('Элемент modal-close-btn не найден');
        }

        // Проверка на наличие элементов
        if (!selectAllCheckbox || !checkboxes.length || !selectAllText || !wishlistButton || !cartReceipt || !deleteSelectedBtn) {
            console.warn('Не все необходимые элементы найдены на странице.');
            return;
        }

        // Обновление цены товара
        function updatePrice(itemId) {
            const quantityInput = document.getElementById(`quantity-${itemId}`);
            const priceElement = document.getElementById(`price-${itemId}`);
            const oldPriceElement = document.getElementById(`old-price-${itemId}`);

            if (!quantityInput || !priceElement || !oldPriceElement) return;

            const quantity = Math.max(1, parseInt(quantityInput.value, 10) || 1);
            quantityInput.value = quantity;

            const basePrice = parseInt(priceElement.dataset.basePrice, 10);
            const baseOldPrice = parseInt(oldPriceElement.dataset.baseOldPrice, 10);

            priceElement.textContent = `${basePrice * quantity} ₴`;
            oldPriceElement.textContent = `${baseOldPrice * quantity} ₴`;
        }

        // Обновление общей суммы
        function updateTotalSum() {
            let total = 0;
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const itemId = checkbox.dataset.itemId;
                    const quantityInput = document.getElementById(`quantity-${itemId}`);
                    const priceElement = document.getElementById(`price-${itemId}`);

                    if (quantityInput && priceElement) {
                        const quantity = parseInt(quantityInput.value, 10) || 0;
                        const basePrice = parseInt(priceElement.dataset.basePrice, 10) || 0;
                        total += basePrice * quantity;
                    }
                }
            });

            const totalSumElement = document.querySelector('.cart-receipt__sum-price span');
            if (totalSumElement) totalSumElement.textContent = `${total} ₴`;
        }

        // Обновление состояния общего чекбокса
        function updateSelectAllState() {
            const checkedCheckboxes = Array.from(checkboxes).filter(
                checkbox =>
                checkbox.checked &&
                !checkbox.closest('.cart-product').classList.contains('cart-product--out-of-stock')
            );

            const totalSelectable = Array.from(checkboxes).filter(
                checkbox => !checkbox.closest('.cart-product').classList.contains('cart-product--out-of-stock')
            ).length;

            const allChecked = checkedCheckboxes.length === totalSelectable;
            const anyChecked = checkedCheckboxes.length > 0;

            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = !allChecked && anyChecked;

            selectAllText.textContent = anyChecked ?
                `Выбрано ${checkedCheckboxes.length} из ${totalSelectable}` :
                'Выберите элементы';

            wishlistButton.classList.toggle('active-wishlist', anyChecked);
            cartReceipt.style.display = anyChecked ? 'flex' : 'none';
        }

        // Обработчик для кнопки удаления выбранных товаров чекбоксом
        deleteSelectedBtn.addEventListener('click', function() {
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const productElement = checkbox.closest('.cart-product');
                    productElement.remove(); // Удаляем элемент товара
                }
            });

            // Обновляем состояние
            updateSelectAllState();
            updateFooters();
            updateTotalSum();
        });


        /* ------------------------------------------------------------------------------------------------ */
        // 1 блок "три точки"

        // Инициализация переменной для отслеживания состояния прокрутки
        let isScrollDisabled = false;

        // Функция для отключения прокрутки
        function preventDefault(e) {
            e.preventDefault();
        }

        // Блокировка прокрутки
        function disableScroll() {
            document.documentElement.style.overflow = 'hidden';
            document.body.style.overflow = 'hidden';
            window.addEventListener('wheel', preventDefault, {
                passive: false
            });
            isScrollDisabled = true;
            console.log('Прокрутка отключена');
        }

        // Включение прокрутки
        function enableScroll() {
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
            window.removeEventListener('wheel', preventDefault);
            isScrollDisabled = false;
            console.log('Прокрутка включена');
        }

        // Проверьте, не была ли уже объявлена переменная menuToggleBtns
        if (typeof menuToggleBtns === 'undefined') {
            const menuToggleBtns = document.querySelectorAll('.menu-toggle-btn');

            menuToggleBtns.forEach(function(menuToggleBtn) {
                const cartProduct = menuToggleBtn.closest('.cart-product');
                const deleteOption = cartProduct.querySelector('.delete-option');

                // Добавляем обработчик события на иконку "три точки"
                menuToggleBtn.addEventListener('click', function(event) {
                    event.stopPropagation(); // Предотвращаем всплытие события

                    // Показать/скрыть меню "Удалить"
                    if (deleteOption.classList.contains('hidden')) {
                        deleteOption.classList.remove('hidden');
                        menuToggleBtn.classList.add('clicked');

                        // Отключаем прокрутку при открытии меню
                        if (!isScrollDisabled) {
                            disableScroll();
                        }
                    } else {
                        deleteOption.classList.add('hidden');
                        menuToggleBtn.classList.remove('clicked');

                        // Включаем прокрутку при закрытии меню
                        if (isScrollDisabled) {
                            enableScroll();
                        }
                    }
                });
            });
        }

        // Обработчик клика по "Удалить"
        document.querySelectorAll('.menu-item--delete button').forEach(function(deleteButton) {
            deleteButton.addEventListener('click', function(event) {
                event.stopPropagation(); // Предотвращаем всплытие события

                const cartProduct = deleteButton.closest('.cart-product');
                if (cartProduct) {
                    // Удаляем товар из DOM
                    cartProduct.remove();

                    // Обновление состояния корзины
                    updateTotalSum();
                    updateSelectAllState();
                    updateFooters();

                    // Включаем прокрутку после удаления
                    if (isScrollDisabled) {
                        enableScroll();
                    }

                    console.log('Товар удален.');
                }
            });
        });


        /* --------------------------------------------------------------------------------------------- */

        // Принудительная активация обработчиков для затемнённых товаров
        document.querySelectorAll('.cart-product--out-of-stock .delete-option').forEach(function(deleteOption) {
            const cartProduct = deleteOption.closest('.cart-product');

            deleteOption.addEventListener('click', function(event) {
                event.stopPropagation(); // Предотвращаем всплытие события

                // Удаление затемнённого товара из DOM
                cartProduct.remove();

                // Обновление общей суммы
                updateTotalSum();

                // Обновление состояния чекбоксов и футеров
                updateSelectAllState();
                updateFooters();

                console.log('Затемнённый товар успешно удалён из корзины.');
            });
        });

        // Обновление футеров
        function updateFooters() {
            checkboxes.forEach(checkbox => {
                const itemId = checkbox.dataset.itemId;
                const footer = document.getElementById(`footer-${itemId}`);
                if (footer) footer.style.display = checkbox.checked ? 'block' : 'none';
            });
        }

        // Обработчик для кнопки '+' (ПЛЮС)
        document.querySelectorAll('.cart-counter__btn.plus').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.closest('.cart-product').querySelector('.cart-counter__input').id.split('-')[1];
                const quantityInput = document.getElementById(`quantity-${itemId}`);
                quantityInput.value = parseInt(quantityInput.value, 10) + 1;

                const minusButton = document.getElementById(`minus-${itemId}`);
                if (minusButton) minusButton.classList.add('active');

                updatePrice(itemId);
                updateTotalSum();
            });
        });

        // Обработчик для кнопки '-' (МИНУС)
        document.querySelectorAll('.cart-counter__btn.minus').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.closest('.cart-product').querySelector('.cart-counter__input').id.split('-')[1];
                const quantityInput = document.getElementById(`quantity-${itemId}`);
                const currentValue = parseInt(quantityInput.value, 10);

                if (currentValue > 1) {
                    quantityInput.value = currentValue - 1;

                    const minusButton = document.getElementById(`minus-${itemId}`);
                    if (currentValue - 1 === 1 && minusButton) minusButton.classList.remove('active');

                    updatePrice(itemId);
                    updateTotalSum();
                }
            });
        });

        // Обработчик для ввода количества
        document.querySelectorAll('.cart-counter__input').forEach(input => {
            input.addEventListener('input', function() {
                const itemId = this.id.split('-')[1];
                updatePrice(itemId);
                updateTotalSum();
            });
        });

        // Обработчик для общего чекбокса
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;

            checkboxes.forEach(checkbox => {
                const productElement = checkbox.closest('.cart-product');
                if (!productElement.classList.contains('cart-product--out-of-stock')) {
                    checkbox.checked = isChecked;
                }
            });

            updateSelectAllState();
            updateFooters();
            updateTotalSum();
        });

        // Обработчик для индивидуальных чекбоксов
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                updateSelectAllState();
                updateFooters();
                updateTotalSum();
            });
        });

        // Инициализация
        updateSelectAllState();
        updateFooters();
        updateTotalSum();
    });
</script>


@endsection