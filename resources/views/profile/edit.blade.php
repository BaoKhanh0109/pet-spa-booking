<x-client-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Thông tin cá nhân</h2>
                <p class="text-gray-600 mt-1">Quản lý thông tin tài khoản và bảo mật của bạn</p>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start justify-between">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">✅</span>
                        <div>
                            <p class="font-bold text-green-700">Thành công!</p>
                            <p class="text-sm text-green-600">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600 text-xl font-bold">
                        &times;
                    </button>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">❌</span>
                        <div>
                            <p class="font-bold text-red-700">Có lỗi xảy ra!</p>
                            <ul class="text-sm text-red-600 mt-1 list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Thông tin cơ bản -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 mb-6">
                <div class="bg-blue-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white">Thông tin cơ bản</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">
                                    Họ và tên <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3"
                                    required>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3"
                                    required>
                                @if($user->email_verified_at)
                                    <p class="text-xs text-green-600 mt-1">✓ Email đã xác thực</p>
                                @else
                                    <p class="text-xs text-yellow-600 mt-1">⚠ Email chưa xác thực</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">
                                    Số điện thoại
                                </label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3"
                                    placeholder="VD: 0912345678">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">
                                    Vai trò
                                </label>
                                <input type="text" value="{{ $user->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' }}"
                                    class="w-full bg-gray-100 border-gray-300 rounded-lg p-3 text-gray-600"
                                    disabled>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2">
                                Địa chỉ
                            </label>
                            <textarea name="address" rows="2"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3"
                                placeholder="Nhập địa chỉ của bạn">{{ old('address', $user->address) }}</textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                                Cập nhật thông tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Thay đổi mật khẩu -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100 mb-6">
                <div class="bg-yellow-500 px-6 py-4">
                    <h3 class="text-xl font-bold text-white">Đổi mật khẩu</h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-4 mb-6">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">
                                    Mật khẩu hiện tại <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="current_password"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition p-3"
                                    required>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">
                                    Mật khẩu mới <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition p-3"
                                    required>
                                <p class="text-xs text-gray-500 mt-1">Tối thiểu 8 ký tự</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">
                                    Xác nhận mật khẩu mới <span class="text-red-500">*</span>
                                </label>
                                <input type="password" name="password_confirmation"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:border-yellow-500 focus:ring focus:ring-yellow-200 transition p-3"
                                    required>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                                Đổi mật khẩu
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Thông tin tài khoản -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                <div class="bg-gray-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white">Thông tin tài khoản</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-start">
                            <div class="w-40 text-gray-500 font-semibold">Ngày tạo:</div>
                            <div class="flex-1 text-gray-800">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-40 text-gray-500 font-semibold">Cập nhật lần cuối:</div>
                            <div class="flex-1 text-gray-800">
                                {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'N/A' }}
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-40 text-gray-500 font-semibold">ID tài khoản:</div>
                            <div class="flex-1 text-gray-800 font-mono">
                                #{{ $user->userID }}
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-40 text-gray-500 font-semibold">Số thú cưng:</div>
                            <div class="flex-1 text-blue-600 font-bold">
                                {{ $user->pets->count() }} con
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-40 text-gray-500 font-semibold">Số lịch hẹn:</div>
                            <div class="flex-1 text-green-600 font-bold">
                                {{ $user->appointments->count() }} lần
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-client-layout>

