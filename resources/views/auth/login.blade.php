<x-client-layout>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">
        
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                Chào mừng trở lại!
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Đăng nhập để đặt lịch chăm sóc Boss ngay.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-100">
                
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form class="space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email đăng nhập</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required 
                                   class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition"
                                   placeholder="nguoidung@example.com" value="{{ old('email') }}">
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                   class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition"
                                   placeholder="••••••••">
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                                Ghi nhớ tôi
                            </label>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-sm">
                                <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-500">
                                    Quên mật khẩu?
                                </a>
                            </div>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:-translate-y-0.5">
                            Đăng nhập
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                Bạn chưa có tài khoản?
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('register') }}" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition">
                            Đăng ký tài khoản mới
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-client-layout>