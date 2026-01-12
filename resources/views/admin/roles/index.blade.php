<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 text-center md:text-left">Quản lý chức vụ nhân viên</h2>
                <a href="{{ route('admin.roles.create') }}"
                    class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition transform hover:scale-105 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Thêm Chức Vụ
                </a>
            </div>

            @if(session('success'))
                <div
                    class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start justify-between animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">✅</span>
                        <div>
                            <p class="font-bold text-green-700">Thành công!</p>
                            <p class="text-sm text-green-600">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()"
                        class="text-green-400 hover:text-green-600 text-xl font-bold">
                        &times;
                    </button>
                </div>

                <script>
                    setTimeout(function () {
                        let alert = document.querySelector('.bg-green-50');
                        if (alert) alert.remove();
                    }, 5000);
                </script>
            @endif

            @if(session('error'))
                <div
                    class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex items-start justify-between animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">❌</span>
                        <div>
                            <p class="font-bold text-red-700">Lỗi!</p>
                            <p class="text-sm text-red-600">{{ session('error') }}</p>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()"
                        class="text-red-400 hover:text-red-600 text-xl font-bold">
                        &times;
                    </button>
                </div>

                <script>
                    setTimeout(function () {
                        let alert = document.querySelector('.bg-red-50');
                        if (alert) alert.remove();
                    }, 5000);
                </script>
            @endif

            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                <table class="min-w-full text-left">
                    <thead class="bg-blue-50 text-blue-700 uppercase font-bold text-sm">
                        <tr>
                            <th class="py-4 px-6">Tên chức vụ</th>
                            <th class="py-4 px-6">Mô tả</th>
                            <th class="py-4 px-6">Trạng thái</th>
                            <th class="py-4 px-6">Số nhân viên</th>
                            <th class="py-4 px-6 text-right">Hành động</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-600">
                        @forelse($roles as $role)
                            <tr class="border-b hover:bg-gray-50 transition duration-150">
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">{{ $role->roleName }}</div>
                                </td>

                                <td class="py-4 px-6 text-sm max-w-xs">
                                    {{ $role->description ?? 'Không có mô tả' }}
                                </td>

                                <td class="py-4 px-6">
                                    @if($role->is_active)
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-700">
                                            Đang hoạt động
                                        </span>
                                    @else
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-700">
                                            Không hoạt động
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-6">
                                    <span class="text-blue-600 font-bold">
                                        {{ $role->employees->count() }} người
                                    </span>
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.roles.edit', $role->roleID) }}"
                                            class="text-yellow-500 hover:text-yellow-600 font-bold transition transform hover:scale-110"
                                            title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role->roleID) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa chức vụ này?');"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-600 font-bold transition transform hover:scale-110"
                                                title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-500">
                                    <p class="mb-4">Chưa có chức vụ nào trong hệ thống.</p>
                                    <a href="{{ route('admin.roles.create') }}" class="text-blue-600 font-bold hover:underline">
                                        Thêm ngay!
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($roles->isNotEmpty())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $roles->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
