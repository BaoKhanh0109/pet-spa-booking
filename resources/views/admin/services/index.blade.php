<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 text-center md:text-left">Quản lý dịch vụ</h2>
                <a href="{{ route('admin.services.create') }}"
                    class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition transform hover:scale-105 flex items-center gap-2">
                    Thêm Dịch Vụ
                </a>
            </div>

            @if(session('success'))
                <div
                    class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start justify-between animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">✅</span>
                        <div>
                            <p class="font-bold text-green-700">Thành công!</p>
                            <p class="text-sm text-green-600">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()"
                        class="text-green-400 hover:text-green-600 text-xl font-bold">
                        &times;
                    </button>
                </div>

                <script>
                    setTimeout(function () {
                        let alert = document.querySelector('.bg-green-50');
                        if (alert) alert.remove();
                    }, 5000);
                </script>
            @endif

            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                <table class="min-w-full text-left">
                    <thead class="bg-blue-50 text-blue-700 uppercase font-bold text-sm">
                        <tr>
                            <th class="py-4 px-6">Hình ảnh</th>
                            <th class="py-4 px-6">Tên dịch vụ</th>
                            <th class="py-4 px-6">Giá (VNĐ)</th>
                            <th class="py-4 px-6">Mô tả ngắn</th>
                            <th class="py-4 px-6 text-right">Hành động</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-600">
                        @foreach($services as $sv)
                            <tr class="border-b hover:bg-gray-50 transition duration-150">

                                <td class="py-4 px-6">
                                    <div class="h-16 w-16">
                                        @if($sv->serviceImage)
                                            <img class="h-16 w-16 rounded-lg object-cover shadow-sm border border-gray-200"
                                                src="{{ asset('storage/' . $sv->serviceImage) }}" alt="{{ $sv->serviceName }}">
                                        @else
                                            <div
                                                class="h-16 w-16 rounded-lg bg-gray-100 flex items-center justify-center text-gray-400 border border-gray-200">
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-6 font-bold text-gray-800">
                                    {{ $sv->serviceName }}
                                </td>

                                <td class="py-4 px-6">
                                    <span class="text-blue-600 font-bold border-blue-100">
                                        {{ number_format($sv->price) }} đ
                                    </span>
                                </td>

                                <td class="py-4 px-6 text-sm max-w-xs truncate">
                                    {{ Str::limit($sv->description, 50) }}
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.services.edit', $sv->serviceID) }}"
                                            class="text-yellow-500 hover:text-yellow-600 font-bold transition transform hover:scale-110"
                                            title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.services.destroy', $sv->serviceID) }}" method="POST"
                                            onsubmit="return confirm('Xóa dịch vụ này? Hành động không thể hoàn tác!');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-600 font-bold transition transform hover:scale-110"
                                                title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($services->isEmpty())
                    <div class="p-10 text-center text-gray-500">
                        <p class="mb-4">Chưa có dịch vụ nào trong hệ thống.</p>
                        <a href="{{ route('admin.services.create') }}" class="text-blue-600 font-bold hover:underline">Thêm
                            ngay!</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>