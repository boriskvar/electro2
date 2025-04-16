<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;

use App\Models\Wishlist;
use App\Models\Product;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

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

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Внутри RegisteredUserController::store
        if ($request->has('wishlist_product_id')) {
            $productId = $request->input('wishlist_product_id');

            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);

            return redirect()->route('products.show', $productId ?? null)
                ->with('success', $productId ? 'Товар добавлен в Wishlist!' : 'Регистрация прошла успешно');
        }

        // return redirect(route('dashboard', absolute: false));
        // fallback: если пользователь просто зарегистрировался
        return redirect()->route('dashboard'); // или '/' если у тебя нет dashboard
    }
}
