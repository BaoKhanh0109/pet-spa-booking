<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Thêm Chức Vụ Mới</h2>

                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf

                        <div class="space-y-6">
                            <!-- Tên chức vụ -->
                            <div>
                                <label for="roleName" class="block font-medium text-sm text-gray-700 mb-2">
                                    Tên chức vụ <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="roleName" id="roleName"
                                    value="{{ old('roleName') }}"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="VD: Quản lý, Kỹ thuật viên..."
                                    required>
                                @error('roleName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mô tả -->
                            <div>
                                <label for="description" class="block font-medium text-sm text-gray-700 mb-2">
                                    Mô tả
                                </label>
                                <textarea name="description" id="description" rows="4"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Mô tả về chức vụ này...">{{ old('description') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Trạng thái -->
                            <div>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" value="1" 
                                        {{ old('is_active', true) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700 font-medium">Kích hoạt chức vụ này</span>
                                </label>
                                <p class="mt-1 text-xs text-gray-500">Chức vụ đang hoạt động sẽ hiển thị trong danh sách chọn khi thêm/sửa nhân viên</p>
                            </div>
                        </div>

                        <div class="flex gap-4 mt-8">
                            <a href="{{ route('admin.roles.index') }}"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-4 rounded-lg text-center transition">
                                Hủy
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg transition">
                                <i class="fas fa-plus mr-2"></i>Thêm chức vụ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
