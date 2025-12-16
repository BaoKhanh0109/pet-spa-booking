<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Sửa Dịch Vụ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg border border-gray-700">
                <form action="{{ route('admin.services.update', $service->serviceID) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-5">
                        <label class="block text-white font-bold mb-2 text-lg">Tên dịch vụ</label>
                        <input type="text" name="serviceName" value="{{ $service->serviceName }}" 
                               class="w-full bg-white text-gray-900 border-gray-300 rounded-lg p-3 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-5">
                        <label class="block text-white font-bold mb-2 text-lg">Giá tiền</label>
                        <input type="number" name="price" value="{{ $service->price }}" 
                               class="w-full bg-white text-gray-900 border-gray-300 rounded-lg p-3 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-white font-bold mb-2 text-lg">Mô tả</label>
                        <textarea name="description" rows="4" 
                                  class="w-full bg-white text-gray-900 border-gray-300 rounded-lg p-3 focus:ring-blue-500">{{ $service->description }}</textarea>
                    </div>

                    <div class="flex justify-end gap-4">
                        <a href="{{ route('admin.services.index') }}" class="text-gray-300 hover:text-white py-3 px-6 font-bold">Hủy</a>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition hover:scale-105">
                            Cập nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>