<x-client-layout>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">
        
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">

            <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                Quên mật khẩu?
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Đừng lo, chuyện nhỏ thôi! <br>
                Nhập email của bạn vào bên dưới, chúng mình sẽ gửi link đặt lại mật khẩu ngay.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-100">
                
                <x-auth-session-status class="mb-4 bg-green-50 text-green-700 p-3 rounded-lg border border-green-200 text-sm" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email đã đăng ký</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" autocomplete="email" required autofocus
                                   class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition"
                                   placeholder="email@vidu.com" value="{{ old('email') }}">
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:-translate-y-0.5">
                            Gửi Link Khôi Phục
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
                                Bạn nhớ ra mật khẩu rồi?
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500 flex items-center justify-center gap-2 transition hover:underline">
                            &larr; Quay lại trang Đăng nhập
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-client-layout>