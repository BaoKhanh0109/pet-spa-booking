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
                            <th class="py-4 px-6">Loại</th>
                            <th class="py-4 px-6">Thú Cưng</th>
                            <th class="py-4 px-6">Dịch Vụ</th>
                            <th class="py-4 px-6">Nhân viên</th>
                            <th class="py-4 px-6">Thời Gian</th>                            
                            <th class="py-4 px-6 text-center">Trạng Thái</th>
                            <th class="py-4 px-6">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @foreach($appointments as $app)
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="py-4 px-6">
                                @if($app->booking_type == 'beauty')
                                    <span class="bg-pink-100 text-pink-700 py-1 px-2 rounded-full text-xs font-bold">💅 Làm đẹp</span>
                                @elseif($app->booking_type == 'medical')
                                    <span class="bg-green-100 text-green-700 py-1 px-2 rounded-full text-xs font-bold">⚕️ Y tế</span>
                                @else
                                    <span class="bg-orange-100 text-orange-700 py-1 px-2 rounded-full text-xs font-bold">🏠 Trông giữ</span>
                                @endif
                            </td>
                            <td class="py-4 px-6">{{ $app->pet->petName }}</td>
                            <td class="py-4 px-6">
                                @if($app->booking_type == 'beauty' && $app->services->count() > 0)
                                    @foreach($app->services as $srv)
                                        <div>{{ $srv->serviceName }} - <span class="text-xs text-blue-500 font-bold">{{ number_format($srv->price) }}đ</span></div>
                                    @endforeach
                                @else
                                    {{ $app->service ? $app->service->serviceName : 'N/A' }} <br>
                                    @if($app->service)
                                        <span class="text-xs text-blue-500 font-bold">{{ number_format($app->service->price) }}đ</span>
                                    @endif
                                @endif
                            </td>
                            <td class="py-4 px-6">
                                @if($app->employee)
                                    {{ $app->employee->employeeName }}
                                @else
                                    <span class="text-gray-400 text-xs">Chưa phân công</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 font-medium">
                                <div class="text-sm">
                                    📅 {{ \Carbon\Carbon::parse($app->appointmentDate)->format('d/m/Y H:i') }}
                                    @if($app->booking_type == 'pet_care' && $app->endDate)
                                        <br><span class="text-xs text-gray-500">đến {{ \Carbon\Carbon::parse($app->endDate)->format('d/m/Y') }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($app->status == 'Pending')
                                    <span class="bg-yellow-100 text-yellow-700 py-1 px-3 rounded-full text-xs font-bold">⏳ Đang chờ</span>
                                @elseif($app->status == 'approved' || $app->status == 'Confirmed')
                                    <span class="bg-green-100 text-green-700 py-1 px-3 rounded-full text-xs font-bold">✓ Đã duyệt</span>
                                @elseif($app->status == 'Completed')
                                    <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs font-bold">✓ Hoàn thành</span>
                                @else
                                    <span class="bg-red-100 text-red-700 py-1 px-3 rounded-full text-xs font-bold">✗ Đã hủy</span>
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