<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Thêm Nhân viên Mới') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.employees.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Tên nhân viên -->
                            <div class="col-span-2">
                                <label for="employeeName" class="block font-medium text-sm text-gray-700">
                                    Tên nhân viên <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="employeeName" id="employeeName"
                                    value="{{ old('employeeName') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                @error('employeeName')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Chức vụ -->
                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700">
                                    Chức vụ <span class="text-red-500">*</span>
                                </label>
                                <select name="role" id="role"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                    <option value="">-- Chọn chức vụ --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->roleName }}" {{ old('role') == $role->roleName ? 'selected' : '' }}>
                                            {{ $role->roleName }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">
                                    <a href="{{ route('admin.roles.index') }}" class="text-blue-600 hover:underline" target="_blank">
                                        <i class="fas fa-cog"></i> Quản lý chức vụ
                                    </a>
                                </p>
                                @error('role')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Số điện thoại -->
                            <div>
                                <label for="phoneNumber" class="block font-medium text-sm text-gray-700">
                                    Số điện thoại <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="phoneNumber" id="phoneNumber"
                                    value="{{ old('phoneNumber') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                @error('phoneNumber')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-span-2">
                                <label for="email" class="block font-medium text-sm text-gray-700">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Avatar -->
                            <div class="col-span-2">
                                <label for="avatar" class="block font-medium text-sm text-gray-700">
                                    Ảnh đại diện
                                </label>
                                <input type="file" name="avatar" id="avatar" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-md file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-blue-50 file:text-blue-700
                                    hover:file:bg-blue-100"
                                    onchange="previewImage(event)">
                                @error('avatar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <div id="imagePreview" class="mt-2 hidden">
                                    <img src="" alt="Preview" class="h-32 w-32 object-cover rounded-lg">
                                </div>
                            </div>

                            <!-- Thông tin thêm -->
                            <div class="col-span-2">
                                <label for="info" class="block font-medium text-sm text-gray-700">
                                    Thông tin thêm
                                </label>
                                <textarea name="info" id="info" rows="4"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    placeholder="Kinh nghiệm, chuyên môn, đặc điểm...">{{ old('info') }}</textarea>
                                @error('info')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Dịch vụ phụ trách -->
                            <div class="col-span-2">
                                <label class="block font-medium text-sm text-gray-700 mb-2">
                                    Dịch vụ phụ trách
                                </label>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($services as $service)
                                        <label class="flex items-center p-3 border rounded-lg hover:bg-gray-50 cursor-pointer">
                                            <input type="checkbox" name="services[]" value="{{ $service->serviceID }}"
                                                {{ in_array($service->serviceID, old('services', [])) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">{{ $service->serviceName }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                @error('services')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex gap-4 mt-6">
                            <a href="{{ route('admin.employees.index') }}"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded-lg text-center transition">
                                Hủy
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition">
                                Thêm nhân viên
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('imagePreview');
            const img = preview.querySelector('img');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
