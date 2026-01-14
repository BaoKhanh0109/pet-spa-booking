<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Chỉnh Sửa Chức Vụ</h2>

                    <form action="{{ route('admin.roles.update', $role->roleID) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Tên chức vụ -->
                            <div>
                                <label for="roleName" class="block font-medium text-sm text-gray-700 mb-2">
                                    Tên chức vụ <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="roleName" id="roleName"
                                    value="{{ old('roleName', $role->roleName) }}"
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
                                    placeholder="Mô tả về chức vụ này...">{{ old('description', $role->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Thông tin nhân viên -->
                            @if($role->employees->count() > 0)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <p class="text-sm text-blue-800 font-medium">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Có <strong>{{ $role->employees->count() }} nhân viên</strong> đang sử dụng chức vụ này
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-4 mt-8">
                            <a href="{{ route('admin.roles.index') }}"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2.5 px-4 rounded-lg text-center transition">
                                Hủy
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-4 rounded-lg transition">
                                <i class="fas fa-save mr-2"></i>Cập nhật chức vụ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
