<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Product;

use App\Models\Wishlist;
use Illuminate\View\View;

use App\Models\Comparison;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // dd($request->all());
        //? 1. 🔐 Валидирует форму:
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        //? 2. 👤 Создаёт пользователя:
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        //? 3. 📣 Триггерит событие Registered:
        event(new Registered($user));

        //? 4. 🔑 Логинит пользователя сразу после регистрации:
        Auth::login($user);

        //? ✅ Если передан wishlist_product_id:
        // === Если передан товар для Wishlist (обработка добавления в Wishlist:) ===
        if ($request->has('wishlist_product_id')) {
            $productId = $request->input('wishlist_product_id');

            $exists = Wishlist::where('user_id', $user->id)
                ->where('product_id', $productId)
                ->exists();

            if (!$exists) {
                Wishlist::create([
                    'user_id' => $user->id,
                    'product_id' => $productId,
                ]);
            }

            return redirect()->route('products.show', $productId ?? null)
                ->with('success', $productId ? 'Товар добавлен в Wishlist!' : 'Регистрация прошла успешно');
        }

        // === Если передан товар для Compare ===
        if ($request->has('compare_product_id')) {
            $productId = $request->input('compare_product_id');

            // Находим сравнение пользователя или создаём новое
            $comparison = Comparison::firstOrCreate([
                'user_id' => $user->id,
            ]);

            // Проверяем, есть ли уже этот товар в списке сравнения
            $exists = $comparison->products()
                ->where('product_id', $productId)
                ->exists();

            if (!$exists) {
                $comparison->products()->attach($productId);
            }

            return redirect()->route('products.show', $productId ?? null)
                ->with('success', $productId ? 'Товар добавлен в Compare!' : 'Регистрация прошла успешно');
        }

        //? ✅ Если ничего не передано — обычный редирект:
        return redirect()->route('dashboard');  // или на главную: route('/')
    }
}
