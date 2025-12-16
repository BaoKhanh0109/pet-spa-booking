<x-client-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto px-4">
            
            <div class="mb-6">
                <a href="{{ route('pets.index') }}" class="text-gray-500 hover:text-blue-600 transition flex items-center gap-1">
                    &larr; Quay lại danh sách
                </a>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-blue-600 p-6 text-center">
                    <h2 class="text-2xl font-bold text-white">🐾 Thêm Thú Cưng Mới</h2>
                    <p class="text-blue-100 text-sm">Nhập thông tin Boss để chúng mình chăm sóc tốt hơn nhé!</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2">Tên thú cưng <span class="text-red-500">*</span></label>
                            <input type="text" name="petName" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3" placeholder="Ví dụ: Misa, Lu..." required>
                        </div>

                        <div class="grid grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Loài <span class="text-red-500">*</span></label>
                                <select name="species" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3">
                                    <option value="Chó">Chó</option>
                                    <option value="Mèo">Mèo</option>
                                    <option value="Khác">Khác</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Giống loài</label>
                                <input type="text" name="breed" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3" placeholder="VD: Corgi...">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Tuổi (năm)</label>
                                <input type="number" name="age" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3">
                            </div>
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Cân nặng (kg)</label>
                                <input type="number" step="0.1" name="weight" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3">
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="block text-gray-700 font-bold mb-2">Tiền sử bệnh / Lưu ý</label>
                            <textarea name="medicalHistory" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition p-3" placeholder="Bé có dị ứng thuốc gì không?"></textarea>
                        </div>

                        <div class="mb-8">
                            <label class="block text-gray-700 font-bold mb-2">Ảnh đại diện</label>
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col w-full h-32 border-2 border-dashed border-blue-200 hover:bg-blue-50 hover:border-blue-300 rounded-lg cursor-pointer transition">
                                    <div class="flex flex-col items-center justify-center pt-7">
                                        <svg class="w-8 h-8 text-blue-400 group-hover:text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-blue-600">Chọn ảnh (nếu có)</p>
                                    </div>
                                    <input type="file" name="petImage" class="opacity-0" />
                                </label>
                            </div>
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('pets.index') }}" class="w-1/3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-3 rounded-lg text-center transition">Hủy</a>
                            <button type="submit" class="w-2/3 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                                Lưu Thông Tin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-client-layout>