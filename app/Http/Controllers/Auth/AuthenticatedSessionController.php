<?php

namespace App\Http\Controllers\Auth;

use App\Models\Wishlist;
use Illuminate\View\View;
use App\Models\Comparison;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    /* public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    } */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Аутентификация пользователя
        $request->authenticate();
        Session::regenerate(); // если нужно, но можно и оставить $request->session()->regenerate()

        $userId = Auth::id(); // <- через фасад

        // ✅ Проверка на наличие wishlist_product_id в запросе
        if (request()->has('wishlist_product_id')) {
            $productId = request('wishlist_product_id');

            // Проверка, добавлен ли товар в wishlist
            $exists = Wishlist::where('user_id', $userId)
                ->where('product_id', $productId)
                ->exists();

            // Если товар не добавлен — добавляем его в список желаемого
            if (!$exists) {
                Wishlist::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                ]);
            }

            // Редирект обратно на страницу товара с успешным сообщением
            return redirect()->route('products.show', $productId)
                ->with('success', 'Товар добавлен в Wishlist!');
        }


        // ✅ Compare
        if (request()->has('compare_product_id')) {
            $productId = request('compare_product_id');

            // Получаем или создаём запись в comparisons для текущего пользователя
            $comparison = Comparison::firstOrCreate(
                ['user_id' => $userId],
                ['created_at' => now(), 'updated_at' => now()]
            );

            // На всякий случай сохраняем, если объект был только что создан
            if (!$comparison->exists) {
                $comparison->save();
            }

            // Проверяем, добавлен ли товар в сравнение
            // $alreadyAttached = $comparison->products()->where('product_id', $productId)->exists();


            /* if (!$alreadyAttached) {
                $comparison->products()->attach($productId);
            } */
            if (!$comparison->products()->where('product_id', $productId)->exists()) {
                $comparison->products()->attach($productId);
            }

            // Редирект на страницу товара
            return redirect()->route('products.show', $productId)
                ->with('success', 'Товар добавлен в сравнения!');
        }


        // 👇 стандартный редирект
        return redirect()->intended(route('dashboard', absolute: false));
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
