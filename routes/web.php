<?php
require base_path('routes/admin.php');
require base_path('routes/user.php');
require base_path('routes/api.php');


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\QuickViewController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\User\MyAccountController;

use App\Http\Controllers\User\UserOrderController;
use App\Http\Controllers\User\UserCheckoutController;
use App\Http\Controllers\User\UserWishlistController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// Публичные маршруты магазина
Route::group([], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');  // Главная страница магазина

    // Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::get('/menus/{slug}/category/{category_slug}', [MenuController::class, 'category'])->name('menus.category.show');
    Route::get('menus/{slug}/page/{page_slug}', [MenuController::class, 'page'])->name('menus.page.show');

    // Страница быстрого просмотра
    Route::get('/quick-view/{productId}', [QuickViewController::class, 'show'])->name('quickview.show');

    // Страница поиска
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');

    // Для обычных страниц  (web.pages.about-us, web.pages.contact и т.д.)
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');

    // Детали продукта
    Route::get('/product/{id}', [ProductController::class, 'show'])->name('products.show');
    // Отзывы
    Route::post('/reviews', [ProductController::class, 'store'])->name('reviews.store');

    // Подписка на новости
    Route::post('/subscribe', [NewsletterController::class, 'subscribe'])->name('subscribe');
});

// Корзина — доступна всем (в том числе неавторизованным)
Route::prefix('/cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('user.cart.index'); // Показать корзину
    Route::post('/add', [CartController::class, 'add'])->name('user.cart.add'); // Добавить товар в корзину
    Route::delete('/remove/{productId}', [CartController::class, 'remove'])->name('user.cart.remove'); // Удалить товар из корзины
    Route::get('/data', [CartController::class, 'getCartData'])->name('cart.data');

    // Route::middleware('auth')->group(function () {
    //     Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index'); // Показать страницу оформления заказа
    //     Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder'); // Оформить заказ
    // });

    // Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');
});

// 👤"My Account" - личный кабинет и оформление заказа
Route::middleware('auth')->prefix('/my-account')->group(function () {
    Route::get('/', [MyAccountController::class, 'index'])->name('my-account');

    // Wishlist
    Route::get('/wishlist', [MyAccountController::class, 'wishlist'])->name('wishlist.index');
    Route::post('/wishlist/store', [MyAccountController::class, 'storeWishlist'])->name('wishlist.store');
    Route::delete('/wishlist/{id}', [MyAccountController::class, 'removeFromWishlist'])->name('wishlist.remove');

    // Cart (дублирование не нужно, если не выводится в личном кабинете отдельно)
    Route::get('/cart', [MyAccountController::class, 'cart'])->name('cart.index');

    // Сравнение товаров 
    Route::get('/compare', [MyAccountController::class, 'compare'])->name('compare.index');
    Route::post('/compare/add', [MyAccountController::class, 'addToCompare'])->name('compare.add');
    Route::post('/compare/remove', [MyAccountController::class, 'removeFromCompare'])->name('compare.remove');
    Route::delete('/compare/clear', [MyAccountController::class, 'clearCompare'])->name('compare.clear');

    // История заказов, товары, отзывы
    Route::get('/orders', [MyAccountController::class, 'orders'])->name('orders.index');
    Route::get('/products', [MyAccountController::class, 'products'])->name('products.index');

    Route::get('/reviews', [MyAccountController::class, 'reviews'])->name('reviews.index');

    // 🧾 Оформление заказа
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');

    // ✅ Страница успешного заказа
    Route::get('/order/success/{order}', [OrderController::class, 'success'])->name('order.success');
});

// ⚙️ Профиль (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard'); // Можно заменить на редирект в my-account
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Подключение маршрутов аутентификации Breeze
// 🔐 Auth routes (Breeze)
require __DIR__ . '/auth.php';
