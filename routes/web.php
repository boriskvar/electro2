<?php
require base_path('routes/admin.php');
require base_path('routes/user.php');
require base_path('routes/api.php');


use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\UserWishlistController;
use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\MyAccountController;

use Illuminate\Support\Facades\Route;

// Публичные маршруты магазина
Route::group([], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');  // Главная страница магазина
    Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/{slug}/category/{category_slug}', [MenuController::class, 'category'])->name('menus.category.show');
    Route::get('menus/{slug}/page/{page_slug}', [MenuController::class, 'page'])->name('menus.page.show');

    // Страница поиска
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    // Для обычных страниц
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');
    // Детали продукта
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');
    // Подписка на новости
    Route::post('/subscribe', [NewsletterController::class, 'subscribe'])->name('subscribe');

    // Корзина
    Route::prefix('/cart')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('user.cart.index'); // Показать корзину
        Route::post('/add', [CartController::class, 'add'])->name('user.cart.add'); // Добавить товар в корзину
        Route::delete('/remove/{productId}', [CartController::class, 'remove'])->name('user.cart.remove'); // Удалить товар из корзины
        Route::get('/data', [CartController::class, 'getCartData'])->name('cart.data');

        Route::middleware('auth')->group(function () {
            Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index'); // Показать страницу оформления заказа
            Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder'); // Оформить заказ
        });

        Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');
    });
});

// Маршруты Breeze (Личный кабинет и авторизация)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Можно заменить на My Account
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// "My Account" - личный кабинет пользователя
Route::middleware('auth')->prefix('my-account')->group(function () {
    Route::get('/', [MyAccountController::class, 'index'])->name('my-account');
    Route::get('/wishlist', [UserWishlistController::class, 'index'])->name('wishlist.index');
    Route::get('/orders', [UserOrderController::class, 'index'])->name('orders.index');
});

// Подключение маршрутов аутентификации Breeze
require __DIR__ . '/auth.php';
