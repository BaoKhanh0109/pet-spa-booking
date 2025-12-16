<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Thêm Thú Cưng Mới</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <form action="{{ route('pets.update', $pet->petID) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tên thú cưng (*)</label>
                        <input type="text" name="petName" class="w-full border rounded px-3 py-2" value="{{ $pet->petName }}" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Loài (*)</label>
                            <select name="species" class="w-full border rounded px-3 py-2">
                                <option value="Chó">Chó</option>
                                <option value="Mèo">Mèo</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Giống loài</label>
                            <input type="text" name="breed" class="w-full border rounded px-3 py-2" value="{{ $pet->breed }}">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tuổi</label>
                            <input type="number" name="age" class="w-full border rounded px-3 py-2" value="{{ $pet->age }}">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Cân nặng (kg)</label>
                            <input type="number" step="0.1" name="weight" class="w-full border rounded px-3 py-2" value="{{ $pet->weight }}">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tiền sử bệnh / Ghi chú</label>
                        <textarea name="medicalHistory" class="w-full border rounded px-3 py-2" rows="3">{{ $pet->medicalHistory }}</textarea>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Ảnh đại diện</label>
                        <input type="file" name="petImage" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('pets.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Hủy</a>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Lưu Thông Tin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>