<x-client-layout>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">
        
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
            <span class="text-6xl">🐾</span>
            <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                Tạo tài khoản mới
            </h2>
            <p class="mt-2 text-sm text-gray-600">
                Điền đầy đủ thông tin để chúng tôi liên hệ dễ dàng hơn.
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl sm:rounded-2xl sm:px-10 border border-gray-100">
                
                <form class="space-y-5" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Họ và Tên</label>
                        <div class="mt-1">
                            <input name="name" type="text" required class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition" placeholder="Nguyễn Văn A" value="{{ old('name') }}">
                            <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-500 text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Địa chỉ Email</label>
                        <div class="mt-1">
                            <input name="email" type="email" required class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition" placeholder="email@vidu.com" value="{{ old('email') }}">
                            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                        <div class="mt-1">
                            <input name="phone" type="text" required class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition" placeholder="0912..." value="{{ old('phone') }}">
                            <x-input-error :messages="$errors->get('phone')" class="mt-2 text-red-500 text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Địa chỉ liên hệ</label>
                        <div class="mt-1">
                            <input name="address" type="text" required class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition" placeholder="Số nhà, đường, quận/huyện..." value="{{ old('address') }}">
                            <x-input-error :messages="$errors->get('address')" class="mt-2 text-red-500 text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Mật khẩu</label>
                        <div class="mt-1">
                            <input name="password" type="password" required class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition" placeholder="••••••••">
                            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nhập lại Mật khẩu</label>
                        <div class="mt-1">
                            <input name="password_confirmation" type="password" required class="appearance-none block w-full px-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition" placeholder="••••••••">
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition transform hover:-translate-y-0.5">
                            Đăng Ký Tài Khoản
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Đã có tài khoản?</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('login') }}" class="w-full flex justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-blue-600 bg-white hover:bg-blue-50 transition">
                            Đăng nhập ngay
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-client-layout>