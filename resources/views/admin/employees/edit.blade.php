<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Chỉnh Sửa Nhân Viên</h2>

            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">

                <div class="bg-blue-50 px-8 py-5 border-b border-blue-100">
                    <h3 class="text-blue-700 font-bold uppercase text-sm tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Thông tin chi tiết
                    </h3>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.employees.update', $employee->employeeID) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Tên nhân viên -->
                            <div class="col-span-2">
                                <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                    Tên nhân viên <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="employeeName" id="employeeName"
                                    value="{{ old('employeeName', $employee->employeeName) }}"
                                    class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium placeholder-gray-400"
                                    placeholder="VD: Nguyễn Văn A..."
                                    required>
                                @error('employeeName')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Chức vụ -->
                            <div>
                                <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                    Chức vụ <span class="text-red-500">*</span>
                                </label>
                                <select name="roleID" id="roleID"
                                    class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium"
                                    required>
                                    <option value="">-- Chọn chức vụ --</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->roleID }}" {{ old('roleID', $employee->roleID) == $role->roleID ? 'selected' : '' }}>
                                            {{ $role->roleName }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-gray-500">
                                    <a href="{{ route('admin.roles.index') }}" class="text-blue-600 hover:underline font-semibold" target="_blank">
                                        <i class="fas fa-cog"></i> Quản lý chức vụ
                                    </a>
                                </p>
                                @error('roleID')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Số điện thoại -->
                            <div>
                                <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                    Số điện thoại <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="phoneNumber" id="phoneNumber"
                                    value="{{ old('phoneNumber', $employee->phoneNumber) }}"
                                    class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium placeholder-gray-400"
                                    placeholder="VD: 0123456789"
                                    required>
                                @error('phoneNumber')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-span-2">
                                <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="email" id="email"
                                    value="{{ old('email', $employee->email) }}"
                                    class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium placeholder-gray-400"
                                    placeholder="VD: employee@example.com"
                                    required>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Avatar -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                Ảnh đại diện
                            </label>
                            @if ($employee->avatar)
                                <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <p class="text-sm text-gray-600 font-semibold mb-2">Ảnh hiện tại:</p>
                                    <img src="{{ asset('storage/' . $employee->avatar) }}"
                                        alt="{{ $employee->employeeName }}"
                                        class="h-32 w-32 object-cover rounded-lg shadow-md border-2 border-gray-300">
                                </div>
                            @endif
                            <div class="flex items-center justify-center w-full">
                                <label for="avatar"
                                    class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-blue-50 hover:border-blue-400 transition group relative overflow-hidden">

                                    <div id="image-placeholder"
                                        class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-blue-500 transition"
                                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 group-hover:text-blue-600"><span
                                                class="font-bold">Bấm để chọn ảnh mới</span> hoặc kéo thả</p>
                                        <p class="text-xs text-gray-400">SVG, PNG, JPG (Max 2MB)</p>
                                    </div>

                                    <img id="image-preview"
                                        class="hidden w-full h-full object-contain p-2 absolute inset-0 bg-white"
                                        alt="Image preview" />

                                    <input id="avatar" type="file" name="avatar" class="hidden"
                                        accept="image/*" onchange="previewImage(this)" />
                                </label>
                            </div>
                            <p class="mt-2 text-xs text-gray-500 italic">Để trống nếu không muốn thay đổi ảnh</p>
                            @error('avatar')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thông tin thêm -->
                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                Thông tin thêm
                            </label>
                            <textarea name="info" id="info" rows="4"
                                class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium placeholder-gray-400"
                                placeholder="Kinh nghiệm, chuyên môn, đặc điểm...">{{ old('info', $employee->info) }}</textarea>
                            @error('info')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dịch vụ phụ trách -->
                        <div class="mb-8">
                            <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wider">
                                Dịch vụ phụ trách
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach ($services as $service)
                                    <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 cursor-pointer transition group">
                                        <input type="checkbox" name="services[]" value="{{ $service->serviceID }}"
                                            {{ in_array($service->serviceID, old('services', $employee->services->pluck('serviceID')->toArray())) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-2 focus:ring-blue-500">
                                        <span class="ml-2 text-sm text-gray-700 font-medium group-hover:text-blue-700">{{ $service->serviceName }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @error('services')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                            <a href="{{ route('admin.employees.index') }}"
                                class="text-gray-500 hover:text-gray-800 font-bold px-4 py-2 transition duration-200">
                                Hủy bỏ
                            </a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform hover:-translate-y-0.5 transition duration-200 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                Cập Nhật
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const placeholder = document.getElementById('image-placeholder');
            const preview = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>
