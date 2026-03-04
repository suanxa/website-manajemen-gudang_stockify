<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Manage Product</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex items-center justify-center 
             bg-gradient-to-br from-[#fdf6ec] via-[#fff3e0] to-[#ffe0b2]">

    <div class="relative w-full max-w-md">

        <!-- Glow effect -->
        <div class="absolute -inset-1 bg-gradient-to-r from-orange-400 to-amber-500 rounded-2xl blur opacity-20"></div>

        <!-- Card -->
        <div class="relative bg-white/70 backdrop-blur-xl 
                    border border-orange-100
                    shadow-2xl rounded-2xl p-8 transition-all duration-300 hover:shadow-orange-200">

            <!-- Branding -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-extrabold text-orange-600 tracking-wide uppercase">
                    @php
                        $appName = \App\Models\Setting::where('key', 'app_name')->first();
                    @endphp
                    {{ $appName && $appName->value ? $appName->value : 'Stockify' }}
                </h1>
                <p class="text-lg font-semibold text-gray-700">
                    Manajemen Barang
                </p>
                <p class="text-sm text-gray-500 mt-1">
                    Sistem Pengelolaan Stok Modern
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-3 text-sm text-green-800 bg-green-100 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Email
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2.5 rounded-xl border border-orange-200
                               focus:ring-2 focus:ring-orange-400 focus:outline-none
                               transition duration-200 bg-white text-gray-700 shadow-sm"
                        placeholder="nama@email.com">
                    @error('email')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-700">
                        Password
                    </label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required
                            class="w-full px-4 py-2.5 rounded-xl border border-orange-200
                                   focus:ring-2 focus:ring-orange-400 focus:outline-none
                                   transition duration-200 bg-white text-gray-700 shadow-sm"
                            placeholder="••••••••">

                        <!-- Show Password Button -->
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-2.5 text-sm text-orange-500 hover:text-orange-700">
                            Show
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-gray-600">
                        <input type="checkbox" name="remember"
                               class="rounded border-orange-300 text-orange-500 focus:ring-orange-400">
                        Remember me
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="text-orange-600 hover:underline font-medium">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <!-- Button -->
                <button type="submit"
                    class="w-full py-2.5 rounded-xl font-semibold text-white 
                           bg-gradient-to-r from-orange-500 to-amber-500
                           hover:from-orange-600 hover:to-amber-600
                           shadow-lg hover:shadow-orange-300
                           transition duration-300 transform hover:-translate-y-0.5">
                    Masuk ke Sistem
                </button>

            </form>

        </div>
    </div>

<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}
</script>

</body>
</html>