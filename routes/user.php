<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\User\UserCartController;
use App\Http\Controllers\CheckoutController;

// Главная страница
// Route::get('/', [HomeController::class, 'index'])->name('home');

/* Route::prefix('user')->group(function () {
    // Группировка маршрутов для корзины
    Route::prefix('cart')->group(function () {
        Route::get('/', [UserCartController::class, 'index'])->name('user.cart.index'); // Показать содержимое корзины
        Route::post('/add/{productId}', [UserCartController::class, 'addProductToCart'])->name('user.cart.add'); // Добавить товар в корзину
        Route::put('/update/{productId}', [UserCartController::class, 'update'])->name('user.cart.update'); // Обновить количество товара в корзине
        Route::delete('/remove/{productId}', [UserCartController::class, 'remove'])->name('user.cart.remove'); // Удалить товар из корзины
    });
}); */
// В user.php
/* Route::prefix('user')->middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('user.cart.index'); // Показывает корзину пользователя
    Route::post('/cart/add', [CartController::class, 'add'])->name('user.cart.add'); // Добавляет товар в корзину
    Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('user.cart.remove'); // Удаляет товар
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('user.checkout.index'); // Оформление заказа
}); */


// Маршруты для работы с желаемым списком
/* Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('wishlist')->group(function () {
        Route::get('/', [WishlistController::class, 'index'])->name('wishlist.index'); // Показать все товары в списке желаемого
        Route::get('/create', [WishlistController::class, 'create'])->name('wishlist.create');    // Показать форму для добавления товара в список желаемого
        Route::post('/store', [WishlistController::class, 'store'])->name('wishlist.store');    // Добавить товар в список желаемого
        Route::get('/{wishlist}', [WishlistController::class, 'show'])->name('wishlist.show');    // Показать отдельный товар из списка желаемого
        Route::get('/{wishlist}/edit', [WishlistController::class, 'edit'])->name('wishlist.edit');    // Показать форму для редактирования товара в списке желаемого
        Route::put('/{wishlist}', [WishlistController::class, 'update'])->name('wishlist.update');    // Обновить товар в списке желаемого
        Route::delete('/{wishlist}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');    // Удалить товар из списка желаемого
    }); */


    // Маршруты для оформления заказа
    /* Route::prefix('checkout')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index'); // Показать страницу оформления заказа
        Route::post('/', [CheckoutController::class, 'store'])->name('checkout.store'); // Обработать оформление заказа
    }); */
/* }); */