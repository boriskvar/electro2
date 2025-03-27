<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AdminCartController;
use App\Http\Controllers\Admin\AdminMenuController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\AdminBrandController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminCheckoutController;
use App\Http\Controllers\Admin\AdminWishlistController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderItemController;
use App\Http\Controllers\Admin\SearchAnalyticsController;
use App\Http\Controllers\Admin\AdminProductAttributeController;
use App\Http\Controllers\Admin\AdminCategoryAttributeController;


//Route::middleware(['auth:sanctum', 'admin'])->group(function () {
Route::prefix('admin')->group(function () {

    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard.index');


    // Маршрут для просмотра аналитики по поисковым запросам
    Route::get('/search-analytics', [SearchAnalyticsController::class, 'index'])->name('search.analytics');

    // Route::prefix('/wishlist')->middleware(['auth', 'admin'])->group(function () {
    Route::prefix('/wishlists')->group(function () {
        Route::get('/', [AdminWishlistController::class, 'index'])->name('admin.wishlists.index'); // Просмотр всех wishlist'ов пользователей
        Route::post('/', [AdminWishlistController::class, 'store'])->name('admin.wishlists.store');
        Route::delete('/{wishlist}', [AdminWishlistController::class, 'destroy'])->name('admin.wishlists.destroy'); // Удаление товара из wishlist'а
    });


    // Маршруты для работы с категориями (CRUD)
    Route::prefix('/categories')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('admin.categories.index'); //список категорий (READ)
        Route::get('/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create'); //форму добавления категории (CREATE)
        Route::post('/', [AdminCategoryController::class, 'store'])->name('admin.categories.store'); // создание категории (CREATE)
        Route::get('/{category}', [AdminCategoryController::class, 'show'])->name('admin.categories.show'); // детали категории (READ)
        Route::get('/{category}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit'); // форму редактирования категории (UPDATE)
        Route::put('/{category}', [AdminCategoryController::class, 'update'])->name('admin.categories.update'); // обновление категории (UPDATE)
        Route::delete('/{category}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy'); // Удаляет категорию (DELETE)
    });

    // Маршруты для работы с продуктами (CRUD)
    Route::prefix('/products')->group(function () {
        Route::get('/search', [AdminProductController::class, 'search'])->name('admin.products.search'); // Поиск продуктов
        Route::get('/', [AdminProductController::class, 'index'])->name('admin.products.index'); // Отображает список всех продуктов (READ)
        Route::get('/create', [AdminProductController::class, 'create'])->name('admin.products.create'); // Показывает форму для добавления нового продукта (CREATE)
        Route::post('/', [AdminProductController::class, 'store'])->name('admin.products.store'); // Обрабатывает запрос на создание продукта (CREATE)
        Route::get('/{product}', [AdminProductController::class, 'show'])->name('admin.products.show'); // Отображает детали конкретного продукта (READ)
        Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit'); // Показывает форму для редактирования продукта (UPDATE)
        Route::put('/{product}', [AdminProductController::class, 'update'])->name('admin.products.update'); // Обрабатывает запрос на обновление продукта (UPDATE)
        Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy'); // Удаляет продукт (DELETE)
    });

    Route::prefix('/category-attributes')->group(function () {

        // Отображение всех атрибутов
        Route::get('/', [AdminCategoryAttributeController::class, 'index'])->name('admin.category-attributes.index');

        // Форма для добавления нового атрибута
        Route::get('/create', [AdminCategoryAttributeController::class, 'create'])->name('admin.category-attributes.create');

        // Обработка запроса на создание атрибута
        Route::post('/', [AdminCategoryAttributeController::class, 'store'])->name('admin.category-attributes.store');

        // Отображение атрибута (например, просмотр атрибута в контексте категории)
        Route::get('/{categoryAttribute}', [AdminCategoryAttributeController::class, 'show'])->name('admin.category-attributes.show');

        // Форма для редактирования атрибута
        Route::get('/{categoryAttribute}/edit', [AdminCategoryAttributeController::class, 'edit'])->name('admin.category-attributes.edit');

        // Обработка запроса на обновление атрибута
        Route::put('/{categoryAttribute}', [AdminCategoryAttributeController::class, 'update'])->name('admin.category-attributes.update');

        // Удаление атрибута
        Route::delete('/{categoryAttribute}', [AdminCategoryAttributeController::class, 'destroy'])->name('admin.category-attributes.destroy');

        // Поиск атрибутов (если необходимо)
        Route::get('/search', [AdminCategoryAttributeController::class, 'search'])->name('admin.category-attributes.search');
    });



    // Маршруты для работы с меню (CRUD)
    Route::prefix('/menus')->group(function () {
        Route::get('/', [AdminMenuController::class, 'index'])->name('admin.menus.index'); // список категорий (READ)
        Route::get('/create', [AdminMenuController::class, 'create'])->name('admin.menus.create'); // форму добавления категории(CREATE)
        Route::post('/', [AdminMenuController::class, 'store'])->name('admin.menus.store'); // создание категории(CREATE)
        Route::get('/{menu}', [AdminMenuController::class, 'show'])->name('admin.menus.show'); // детали категории (READ)
        Route::get('/{menu}/edit', [AdminMenuController::class, 'edit'])->name('admin.menus.edit'); // форму для редактирования категории (UPDATE)
        Route::put('/{menu}', [AdminMenuController::class, 'update'])->name('admin.menus.update'); // Обрабатывает запрос на обновление категории (UPDATE)
        Route::delete('/{menu}', [AdminMenuController::class, 'destroy'])->name('admin.menus.destroy'); // Удаляет категорию (DELETE)
        Route::get('/{menu}/copy', [AdminMenuController::class, 'copy'])->name('admin.menus.copy');
    });

    // Маршруты для управления страницами
    Route::prefix('/pages')->group(function () {
        Route::get('/', [AdminPageController::class, 'index'])->name('admin.pages.index'); // Отображает список всех страниц (READ)
        Route::get('/create', [AdminPageController::class, 'create'])->name('admin.pages.create'); // Показывает форму для добавления новой страниц (CREATE)
        Route::post('/', [AdminPageController::class, 'store'])->name('admin.pages.store'); // Обрабатывает запрос на создание страниц (CREATE)
        Route::get('/{page}', [AdminPageController::class, 'show'])->name('admin.pages.show'); // Отображает детали конкретной страниц (READ)
        Route::get('/{page}/edit', [AdminPageController::class, 'edit'])->name('admin.pages.edit'); // Показывает форму для редактирования страниц (UPDATE)
        Route::put('/{page}', [AdminPageController::class, 'update'])->name('admin.pages.update'); // Обрабатывает запрос на обновление страниц (UPDATE)
        Route::delete('/{page}', [AdminPageController::class, 'destroy'])->name('admin.pages.destroy'); // Удаляет страницу (DELETE)
    });


    // Маршруты для работы с заказами
    Route::prefix('/orders')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('admin.orders.index'); // Отображение списка заказов
        Route::get('/create', [AdminOrderController::class, 'create'])->name('admin.orders.create'); // Показывает ФОРМУ для создания нового заказа (CREATE)
        Route::post('/', [AdminOrderController::class, 'store'])->name('admin.orders.store'); // Обрабатывает запрос на создание заказа (CREATE)
        Route::get('/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show'); // Отображение деталей заказа
        Route::get('/{order}/edit', [AdminOrderController::class, 'edit'])->name('admin.orders.edit'); // Показывает ФОРМУ для редактирования заказа (UPDATE)
        Route::put('/{order}', [AdminOrderController::class, 'update'])->name('admin.orders.update'); // Обновление статуса заказа
        Route::delete('/{order}', [AdminOrderController::class, 'destroy'])->name('admin.orders.destroy'); // Удаление заказа
    });

    Route::prefix('/order-items')->group(function () {
        // Перенаправление на первый доступный заказ или сообщение об ошибке
        Route::get('/', function () {
            $firstOrder = \App\Models\Order::first();
            if ($firstOrder) {
                return redirect()->route('admin.order-items.index', ['order' => $firstOrder->id]);
            }
            return redirect()->route('admin.orders.index')->with('error', 'Заказы отсутствуют.');
        })->name('admin.order-items.redirect');
        Route::get('/orders/{order}', [AdminOrderItemController::class, 'index'])->name('admin.order-items.index'); // Отображает список всех элементов заказа
        Route::get('/orders/{order}/create', [AdminOrderItemController::class, 'create'])->name('admin.order-items.create'); // Форма создания нового элемента заказа
        Route::post('/', [AdminOrderItemController::class, 'store'])->name('admin.order-items.store'); // Создание нового элемента заказа
        Route::get('/{orderItem}', [AdminOrderItemController::class, 'show'])->name('admin.order-items.show'); // Детали элемента заказа
        Route::get('/{orderItem}/edit', [AdminOrderItemController::class, 'edit'])->name('admin.order-items.edit'); // Форма редактирования элемента заказа
        Route::put('/{orderItem}', [AdminOrderItemController::class, 'update'])->name('admin.order-items.update'); // Обновление элемента заказа
        Route::delete('/{orderItem}', [AdminOrderItemController::class, 'destroy'])->name('admin.order-items.destroy'); // Удаление элемента заказа
    });



    // Группировка маршрутов для корзины
    /* Route::prefix('/cart')->group(function () {
        Route::get('/', [AdminCartController::class, 'index'])->name('admin.cart.index'); // Показать содержимое корзины
        Route::post('/add/{productId}', [AdminCartController::class, 'addProductToCart'])->name('admin.cart.add'); // Добавить товар в корзину
        Route::post('/update/{productId}', [AdminCartController::class, 'update'])->name('admin.cart.update'); // Обновить количество товара в корзине
        Route::post('/remove/{productId}', [AdminCartController::class, 'remove'])->name('admin.cart.remove'); // Удалить товар из корзины
    }); */

    Route::prefix('/cart')->group(function () {
        Route::get('/', [AdminCartController::class, 'index'])->name('admin.cart.index'); // Показать корзину
        Route::post('/add', [AdminCartController::class, 'add'])->name('admin.cart.add');
        // Route::get('/create', [AdminCartController::class, 'create'])->name('admin.cart.create'); // Страница добавления товара в корзину
        // Route::post('/store', [AdminCartController::class, 'store'])->name('admin.cart.store'); // Создать запись корзины (добавить товар)
        // Route::get('/edit/{productId}', [AdminCartController::class, 'edit'])->name('admin.cart.edit'); // Страница редактирования корзины
        // Route::put('/update/{productId}', [AdminCartController::class, 'update'])->name('admin.cart.update'); // Обновить товар в корзине
        Route::delete('/remove/{productId}', [AdminCartController::class, 'remove'])->name('admin.cart.remove'); // Удалить товар из корзины

        Route::post('/checkout', [AdminCartController::class, 'checkout'])->name('admin.cart.checkout');
    });

    // Маршруты для оформления заказа
    /* Route::prefix('/checkout')->group(function () {
        Route::get('/', [AdminCheckoutController::class, 'index'])->name('admin.checkout.index'); // Показать страницу оформления заказа
        Route::get('/list', [AdminCheckoutController::class, 'list'])->name('admin.checkout.list'); // Показать список всех оформлений
        Route::get('/create', [AdminCheckoutController::class, 'create'])->name('admin.checkout.create'); // Показать форму создания оформления
        Route::post('/', [AdminCheckoutController::class, 'store'])->name('admin.checkout.store'); // Обработать оформление заказа
        Route::get('/{checkout}/edit', [AdminCheckoutController::class, 'edit'])->name('admin.checkout.edit'); // Показать форму редактирования оформления
        Route::get('/{checkout}', [AdminCheckoutController::class, 'show'])->name('admin.checkout.show'); // Показать конкретное оформление заказа по ID
        Route::put('/{checkout}', [AdminCheckoutController::class, 'update'])->name('admin.checkout.update'); // Обновить оформление
        Route::delete('/{checkout}', [AdminCheckoutController::class, 'destroy'])->name('admin.checkout.destroy'); // Удалить оформление заказа
    }); */

    // Маршруты для работы с отзывами
    Route::prefix('/reviews')->group(function () {
        Route::get('/', [AdminReviewController::class, 'index'])->name('admin.reviews.index'); // Отображение списка отзывов
        Route::get('/create', [AdminReviewController::class, 'create'])->name('admin.reviews.create'); // Форма добавления отзыва
        Route::post('/store', [AdminReviewController::class, 'store'])->name('admin.reviews.store'); // Добавить отзыв
        Route::get('/{review}/edit', [AdminReviewController::class, 'edit'])->name('admin.reviews.edit'); // Редактировать отзыв
        Route::get('/{review}', [AdminReviewController::class, 'show'])->name('admin.reviews.show'); // Отображение конкретного отзыва
        Route::put('/{review}', [AdminReviewController::class, 'update'])->name('admin.reviews.update'); // Обновление отзыва
        Route::delete('/{review}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy'); // Удаление отзыва
    });

    // Маршруты для управления платежами
    /* Route::prefix('/payments')->group(function () {
        Route::get('/', [AdminPaymentController::class, 'index'])->name('admin.payments.index'); // Показать все платежи
        Route::get('/create', [AdminPaymentController::class, 'create'])->name('admin.payments.create'); // Показать форму для создания платежа
        Route::post('/', [AdminPaymentController::class, 'store'])->name('admin.payments.store'); // Сохранить новый платеж
        Route::get('/{payment}', [AdminPaymentController::class, 'show'])->name('admin.payments.show'); // Показать отдельный платеж
        Route::get('/{payment}/edit', [AdminPaymentController::class, 'edit'])->name('admin.payments.edit'); // Показать форму для редактирования платежа
        Route::put('/{payment}', [AdminPaymentController::class, 'update'])->name('admin.payments.update'); // Обновить платеж
        Route::delete('/{payment}', [AdminPaymentController::class, 'destroy'])->name('admin.payments.destroy'); // Удалить платеж
    }); */

    // Маршруты для работы с брендами (CRUD)
    Route::prefix('brands')->group(function () {
        Route::get('/', [AdminBrandController::class, 'index'])->name('admin.brands.index'); // Отображает список всех брендов (READ)
        Route::get('/create', [AdminBrandController::class, 'create'])->name('admin.brands.create'); // Показывает форму для добавления нового бренда (CREATE)
        Route::post('/', [AdminBrandController::class, 'store'])->name('admin.brands.store'); // Обрабатывает запрос на создание бренда (CREATE)
        Route::get('/{brand}', [AdminBrandController::class, 'show'])->name('admin.brands.show'); // Отображает детали конкретного бренда (READ)
        Route::get('/{brand}/edit', [AdminBrandController::class, 'edit'])->name('admin.brands.edit'); // Показывает форму для редактирования бренда (UPDATE)
        Route::put('/{brand}', [AdminBrandController::class, 'update'])->name('admin.brands.update'); // Обрабатывает запрос на обновление бренда (UPDATE)
        Route::delete('/{brand}', [AdminBrandController::class, 'destroy'])->name('admin.brands.destroy'); // Удаляет бренд (DELETE)
    });

    // Маршруты для работы с контактами
    Route::prefix('/contacts')->group(function () {
        // Route::get('/search', [AdminContactController::class, 'search'])->name('admin.contacts.search'); // Поиск 
        Route::get('/', [AdminContactController::class, 'index'])->name('admin.contacts.index'); // список 
        Route::get('/create', [AdminContactController::class, 'create'])->name('admin.contacts.create'); // форма создания
        Route::post('/', [AdminContactController::class, 'store'])->name('admin.contacts.store'); // создание контакта
        Route::get('/{contact}', [AdminContactController::class, 'show'])->name('admin.contacts.show'); // детали конкретного контакта
        Route::get('/{contact}/edit', [AdminContactController::class, 'edit'])->name('admin.contacts.edit'); // форма редактирования контакта
        Route::put('/{contact}', [AdminContactController::class, 'update'])->name('admin.contacts.update'); // обновление контакта
        Route::delete('/{contact}', [AdminContactController::class, 'destroy'])->name('admin.contacts.destroy'); // Удаляет контакт
    });
});
