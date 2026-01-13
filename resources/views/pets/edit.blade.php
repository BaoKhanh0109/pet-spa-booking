<x-client-layout>
    <div class="py-12 bg-blue-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-blue-600 p-6 text-center">
                    <h2 class="text-2xl font-bold text-white">Chỉnh Sửa Hồ Sơ Thú Cưng</h2>
                    <p class="text-blue-100 text-sm">Cập nhật thông tin cho bé {{ $pet->petName }}</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('pets.update', $pet->petID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-6">
                            
                            <div class="md:col-span-4 flex flex-col">
                                <label class="block text-gray-700 font-bold mb-2">Ảnh đại diện</label>
                                <div class="flex-1">
                                    <label for="petImageInput"
                                        class="flex flex-col w-full h-full min-h-[220px] border-2 border-dashed border-blue-300 hover:bg-blue-50 bg-gray-50 rounded-xl cursor-pointer transition relative overflow-hidden group">

                                        <div id="upload-placeholder"
                                            class="flex flex-col items-center justify-center pt-7 h-full {{ $pet->petImage ? 'hidden' : '' }}">
                                            <div class="p-4 bg-blue-100 rounded-full mb-3 group-hover:bg-blue-200 transition">
                                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-gray-600 group-hover:text-blue-600">Tải ảnh lên</p>
                                            <p class="text-xs text-gray-400 mt-1">PNG, JPG, JPEG</p>
                                        </div>

                                        <img id="image-preview"
                                            src="{{ $pet->petImage ? asset('storage/' . $pet->petImage) : '' }}"
                                            class="absolute inset-0 w-full h-full object-contain p-2 bg-gray-50 {{ $pet->petImage ? '' : 'hidden' }}" />

                                        <input type="file" name="petImage" id="petImageInput"
                                            class="opacity-0 absolute inset-0 cursor-pointer"
                                            onchange="previewImage(event)" />
                                    </label>
                                </div>
                            </div>

                            <div class="md:col-span-8 flex flex-col gap-5">
                                <div>
                                    <label class="block text-gray-700 font-bold mb-2">Tên thú cưng <span class="text-red-500">*</span></label>
                                    <input type="text" name="petName" value="{{ $pet->petName }}"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3"
                                        placeholder="Ví dụ: Misa, Lu..." required>
                                </div>

                                <div class="grid grid-cols-2 gap-5">
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2">Loài <span class="text-red-500">*</span></label>
                                        <select name="species"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3">
                                            <option value="Chó" {{ $pet->species == 'Chó' ? 'selected' : '' }}>Chó</option>
                                            <option value="Mèo" {{ $pet->species == 'Mèo' ? 'selected' : '' }}>Mèo</option>
                                            <option value="Khác" {{ $pet->species == 'Khác' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2">Giống loài</label>
                                        <input type="text" name="breed" value="{{ $pet->breed }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3"
                                            placeholder="VD: Corgi...">
                                    </div>
                                </div>
                                
                                <div class="grid grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2 text-sm">Tuổi (năm)</label>
                                        <input type="number" name="age" value="{{ $pet->age }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3 text-center">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2 text-sm">Cân nặng (kg)</label>
                                        <input type="number" step="0.1" name="weight" value="{{ $pet->weight }}" required
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3 text-center"
                                            placeholder="0.0">
                                    </div>
                                    <div>
                                        <label class="block text-gray-700 font-bold mb-2 text-sm">Dài lưng (cm)</label>
                                        <input type="number" step="0.1" name="backLength" value="{{ $pet->backLength }}"
                                            class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3 text-center"
                                            placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 border-t pt-6">
                            <label class="block text-gray-700 font-bold mb-2">Tiền sử bệnh / Ghi chú đặc biệt</label>
                            <textarea name="medicalHistory" rows="3"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3"
                                placeholder="Ví dụ: Bé bị dị ứng đạm gà, cần chải lông kỹ...">{{ $pet->medicalHistory }}</textarea>
                        </div>

                        <div class="flex gap-4 justify-end">
                            <a href="{{ route('pets.index') }}"
                                class="px-6 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-lg text-center transition">
                                Hủy bỏ
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                                Cập Nhật Hồ Sơ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('upload-placeholder');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-client-layout>