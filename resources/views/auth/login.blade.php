<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        @if (request()->has('wishlist_product_id'))
        <input type="hidden" name="wishlist_product_id" value="{{ request('wishlist_product_id') }}">
        @endif

        @if (request()->has('compare_product_id'))
        <input type="hidden" name="compare_product_id" value="{{ request('compare_product_id') }}">
        @endif

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Register Redirect -->
        <div class="flex items-center justify-between mt-4">
            <p class="text-sm text-gray-600">
                Нет аккаунта?
                @php
                $params = [];
                if (request()->has('wishlist_product_id')) $params['wishlist_product_id'] = request('wishlist_product_id');
                if (request()->has('compare_product_id')) $params['compare_product_id'] = request('compare_product_id');
                @endphp
                <a class="underline hover:text-gray-900" href="{{ route('register', $params) }}">Зарегистрируйтесь</a>
            </p>
        </div>

    </form>
</x-guest-layout>