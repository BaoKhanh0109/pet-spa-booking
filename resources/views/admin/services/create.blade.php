<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Thêm Dịch Vụ Mới
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-700">
                <form action="{{ route('admin.services.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-5">
                        <label class="block text-white font-bold mb-2 text-lg">Tên dịch vụ</label>
                        <input type="text" name="serviceName" 
                               class="w-full bg-white text-gray-900 border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                               placeholder="VD: Tắm trắng, Cắt tỉa..." required>
                    </div>

                    <div class="mb-5">
                        <label class="block text-white font-bold mb-2 text-lg">Giá tiền (VNĐ)</label>
                        <input type="number" name="price" 
                               class="w-full bg-white text-gray-900 border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                               placeholder="VD: 500000" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-white font-bold mb-2 text-lg">Mô tả chi tiết</label>
                        <textarea name="description" rows="4" 
                                  class="w-full bg-white text-gray-900 border-gray-300 rounded-lg p-3 focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
                                  placeholder="Mô tả về dịch vụ..."></textarea>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('admin.services.index') }}" class="text-gray-300 hover:text-white py-3 px-6 font-bold">Hủy</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition hover:scale-105">
                            Lưu Dịch Vụ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>