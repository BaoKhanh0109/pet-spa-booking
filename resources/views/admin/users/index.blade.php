<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Quản lý người dùng</h2>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start justify-between animate-fade-in-down">
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

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex items-start justify-between animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">❌</span>
                        <div>
                            <p class="font-bold text-red-700">Lỗi!</p>
                            <p class="text-sm text-red-600">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 text-xl font-bold">
                        &times;
                    </button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                <table class="min-w-full text-left">
                    <thead class="bg-blue-50 text-blue-700 uppercase font-bold text-sm">
                        <tr>
                            <th class="py-4 px-6">ID</th>
                            <th class="py-4 px-6">Thông tin</th>
                            <th class="py-4 px-6">Liên hệ</th>
                            <th class="py-4 px-6">Vai trò</th>
                            <th class="py-4 px-6">Thống kê</th>
                            <th class="py-4 px-6">Ngày tạo</th>
                            <th class="py-4 px-6 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @forelse($users as $user)
                            <tr class="border-b hover:bg-gray-50 transition duration-150">
                                <td class="py-4 px-6 font-semibold text-gray-800">
                                    #{{ $user->userID }}
                                </td>
                                
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </td>

                                <td class="py-4 px-6">
                                    <div class="text-gray-700">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>{{ $user->phone ?? 'Chưa có' }}
                                    </div>
                                    @if($user->address)
                                        <div class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>{{ Str::limit($user->address, 30) }}
                                        </div>
                                    @endif
                                </td>

                                <td class="py-4 px-6">
                                    <span class="inline-flex leading-5 font-semibold
                                        @if($user->role === 'admin') text-red-700
                                        @else @endif">
                                        {{ $user->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' }}
                                    </span>
                                </td>

                                <td class="py-4 px-6">
                                    <div class="text-sm">
                                        <div class="flex items-center gap-2 mb-1">
                                            <i class="fas fa-paw text-blue-500"></i>
                                            <span class="font-semibold">{{ $user->pets_count }}</span>
                                            <span class="text-gray-500">thú cưng</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-calendar-check text-green-500"></i>
                                            <span class="font-semibold">{{ $user->appointments_count }}</span>
                                            <span class="text-gray-500">lịch hẹn</span>
                                        </div>
                                    </div>
                                </td>

                                <td class="py-4 px-6 text-sm text-gray-600">
                                    {{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.users.show', $user->userID) }}"
                                            class="text-blue-500 hover:text-blue-600 font-bold transition transform hover:scale-110"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($user->role !== 'admin')
                                            <form action="{{ route('admin.users.destroy', $user->userID) }}" 
                                                method="POST" 
                                                onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này? Tất cả thú cưng và lịch hẹn của họ cũng sẽ bị xóa!')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-600 font-bold transition transform hover:scale-110"
                                                    title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-8 text-gray-500">
                                    <i class="fas fa-users text-4xl mb-2"></i>
                                    <p>Chưa có người dùng nào trong hệ thống.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($users->hasPages())
                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
