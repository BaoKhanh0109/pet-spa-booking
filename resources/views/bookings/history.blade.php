<x-client-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Lịch Sử Đặt Hẹn</h2>
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-r shadow-sm flex items-start justify-between animate-fade-in-down">
                    <div class="flex items-center gap-3">
                        <span class="text-2xl">✅</span>
                        <div>
                            <p class="font-bold text-green-700">Thành công!</p>
                            <p class="text-sm text-green-600">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-green-400 hover:text-green-600 text-xl font-bold">
                        &times;
                    </button>
                </div>
                
                <script>
                    setTimeout(function() {
                        let alert = document.querySelector('.bg-green-50');
                        if(alert) alert.remove();
                    }, 5000);
                </script>
            @endif
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                <table class="min-w-full text-left">
                    <thead class="bg-blue-50 text-blue-700 uppercase font-bold text-sm">
                        <tr>
                            <th class="py-4 px-6">Thú Cưng</th>
                            <th class="py-4 px-6">Dịch Vụ</th>
                            <th class="py-4 px-6">Ngày Đặt</th>
                            <th class="py-4 px-6">Ngày Hẹn</th>                            
                            <th class="py-4 px-6 text-center">Trạng Thái</th>
                            <th class="py-4 px-6">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @foreach($appointments as $app)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-4 px-6">{{ $app->pet->petName }}</td>
                            <td class="py-4 px-6">
                                {{ $app->service->serviceName }} <br>
                                <span class="text-xs text-blue-500 font-bold">{{ number_format($app->service->price) }}đ</span>
                            </td>
                            <td class="py-4 px-6 font-medium">{{ \Carbon\Carbon::parse($app->created_at)->format('d/m/Y H:i') }}</td>
                            <td class="py-4 px-6 font-medium">{{ \Carbon\Carbon::parse($app->appointmentDate)->format('d/m/Y H:i') }}</td>
                            <td class="py-4 px-6 text-center">
                                @if($app->status == 'Pending')
                                    <span class="bg-yellow-100 text-yellow-700 py-1 px-3 rounded-full text-xs font-bold">Đang chờ</span>
                                @elseif($app->status == 'approved')
                                    <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-bold">Đã duyệt</span>
                                @else
                                    <span class="bg-red-100 text-red-700 py-1 px-3 rounded-full text-xs font-bold">Đã hủy</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-sm">{{ $app->note }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @if($appointments->isEmpty())
                    <div class="p-10 text-center text-gray-500">
                        Chưa có lịch sử nào. <a href="{{ route('booking.create') }}" class="text-blue-600 font-bold hover:underline">Đặt ngay!</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-client-layout>