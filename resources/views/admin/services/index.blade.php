<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Quản lý Dịch vụ') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-end mb-4">
                <a href="{{ route('admin.services.create') }}" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 px-4 rounded">
                    + Thêm Dịch Vụ
                </a>
            </div>

            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full text-left text-gray-400">
                    <thead class="bg-gray-700 uppercase text-gray-200 font-bold">
                        <tr>
                            <th class="px-6 py-3">Tên Dịch Vụ</th>
                            <th class="px-6 py-3">Giá (VNĐ)</th>
                            <th class="px-6 py-3">Mô tả</th>
                            <th class="px-6 py-3 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-700">
                        @foreach($services as $sv)
                        <tr class="hover:bg-gray-700">
                            <td class="px-6 py-4 font-bold text-white">{{ $sv->serviceName }}</td>
                            <td class="px-6 py-4 text-white">{{ number_format($sv->price) }}</td>
                            <td class="px-6 py-4 text-white">{{ Str::limit($sv->description, 50) }}</td>
                            <td class="px-6 py-4 text-right flex justify-end gap-2">
                                <a href="{{ route('admin.services.edit', $sv->serviceID) }}" class="text-white hover:text-yellow-300 font-bold">Sửa</a>
                                <span class="text-gray-600">|</span>
                                <form action="{{ route('admin.services.destroy', $sv->serviceID) }}" method="POST" onsubmit="return confirm('Xóa dịch vụ này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-white hover:text-red-400 font-bold">Xóa</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>