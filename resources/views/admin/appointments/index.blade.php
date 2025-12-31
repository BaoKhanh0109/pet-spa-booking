<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 text-center md:text-left">Quản lý lịch đặt</h2>
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
                            <th class="py-4 px-6">Ngày Đặt</th>
                            <th class="py-4 px-6">Ngày Hẹn</th>
                            <th class="py-4 px-6">Khách Hàng</th>
                            <th class="py-4 px-6">Thú Cưng</th>
                            <th class="py-4 px-6">Nhân Viên</th>
                            <th class="py-4 px-6">Dịch Vụ</th>
                            <th class="py-4 px-6">Trạng Thái</th>
                            <th class="py-4 px-6 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @foreach($appointments as $app)
                            <tr class="border-b hover:bg-gray-50 transition duration-150">
                                <td class="py-4 px-6 text-gray-600 text-sm">
                                    @if($app->created_at)
                                        {{ \Carbon\Carbon::parse($app->created_at)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-gray-400 italic">Chưa rõ</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($app->appointmentDate)->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-bold text-gray-800">{{ $app->user->name ?? 'Khách vãng lai' }}</span>
                                    <br>
                                    <span class="text-xs text-gray-500">{{ $app->user->phone ?? 'Không có SĐT' }}</span>
                                </td>
                                <td class="py-4 px-6 text-gray-700">{{ $app->pet->petName ?? 'N/A' }}</td>
                                <td class="py-4 px-6">
                                    @if($app->employee)
                                        <span class="font-semibold text-gray-800">{{ $app->employee->employeeName }}</span>
                                        <br>
                                        <span class="text-xs text-gray-500">{{ $app->employee->role ?? 'Nhân viên' }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Chưa phân công</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-gray-700">
                                    @if($app->booking_type === 'beauty' && $app->services->isNotEmpty())
                                        @foreach($app->services as $service)
                                            <span
                                                class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded mb-1">{{ $service->serviceName }}</span>
                                        @endforeach
                                    @else
                                        {{ $app->service->serviceName ?? 'N/A' }}
                                    @endif
                                </td>

                                <td class="py-4 px-6">
                                    @if($app->status == 'approved')
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white bg-green-500 shadow-sm whitespace-nowrap">
                                            Đã Duyệt
                                        </span>
                                    @elseif($app->status == 'canceled')
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white bg-red-500 shadow-sm whitespace-nowrap">
                                            Đã Hủy
                                        </span>
                                    @else
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white bg-yellow-500 shadow-sm whitespace-nowrap">
                                            Chờ Duyệt
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        @if($app->status == 'Pending' || $app->status == null || $app->status == '')
                                            <a href="{{ route('admin.appointments.status', ['id' => $app->appointmentID, 'status' => 'approved']) }}"
                                                class="text-green-500 hover:text-green-600 font-bold transition transform hover:scale-110"
                                                title="Duyệt">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif

                                        @if($app->status != 'canceled')
                                            <a href="{{ route('admin.appointments.status', ['id' => $app->appointmentID, 'status' => 'canceled']) }}"
                                                class="text-red-500 hover:text-red-600 font-bold transition transform hover:scale-110"
                                                title="Hủy" onclick="return confirm('Bạn có chắc muốn hủy lịch hẹn này?')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($appointments->isEmpty())
                    <div class="p-10 text-center text-gray-500">
                        <p class="mb-4">Chưa có lịch hẹn nào trong hệ thống.</p>
                    </div>
                @endif

                @if(method_exists($appointments, 'links'))
                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>