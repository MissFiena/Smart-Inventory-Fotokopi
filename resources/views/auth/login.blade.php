<x-guest-layout>
    <!-- Increased max-w-md to max-w-lg and px-8 to px-12 for more interior space -->
    <div class="w-full sm:max-w-lg mt-6 px-12 py-10 bg-white shadow-xl rounded-2xl border border-gray-100">
        
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-2 w-full p-2.5" type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-6">
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-2 w-full p-2.5" type="password" name="password" required />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-6">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm" name="remember">
                    <span class="ms-3 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-8">
                @if (Route::has('password.request'))
                    <a class="text-sm text-gray-600 hover:text-gray-900 underline" href="{{ route('password.request') }}">
                        {{ __('Forgot password?') }}
                    </a>
                @endif

                <x-primary-button class="bg-gray-800 hover:bg-gray-900 px-6 py-2.5">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>