<x-client-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Lịch sử đặt hẹn</h2>

            @if(session('success'))
                <div
                    class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r shadow-sm flex items-start justify-between">
                    <div>
                        <p class="font-bold text-blue-700">Thành công</p>
                        <p class="text-sm text-blue-600">{{ session('success') }}</p>
                    </div>
                    <button onclick="this.parentElement.remove()"
                        class="text-blue-400 hover:text-blue-600 font-bold">&times;</button>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-200">
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
                            <th class="py-4 px-6 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600 divide-y divide-gray-100">
                        @foreach($appointments as $app)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-6 text-gray-800">
                                    {{ $app->serviceCategory->categoryName ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-800">{{ $app->pet->petName }}</td>
                                <td class="py-4 px-6">
                                    @if($app->services && $app->services->count() > 0)
                                        @foreach($app->services as $srv)
                                            <div class="text-sm border-b border-dashed border-gray-200 last:border-0 py-1">
                                                {{ $srv->serviceName }}</div>
                                        @endforeach
                                    @else
                                        <span class="text-gray-400 italic">Chưa có dịch vụ</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    {{ $app->employee->employeeName ?? '---' }}
                                </td>
                                <td class="py-4 px-6 text-sm">
                                    <div class="font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($app->appointmentDate)->format('H:i') }}</div>
                                    <div class="text-gray-500">
                                        {{ \Carbon\Carbon::parse($app->appointmentDate)->format('d/m/Y') }}</div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($app->status == 'Pending')
                                        <span
                                            class="inline-block py-1 px-3 rounded text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">Chờ
                                            duyệt</span>
                                    @elseif($app->status == 'approved' || $app->status == 'Confirmed')
                                        <span
                                            class="inline-block py-1 px-3 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">Đã
                                            duyệt</span>
                                    @elseif($app->status == 'Completed')
                                        <span
                                            class="inline-block py-1 px-3 rounded text-xs font-medium bg-blue-600 text-white border border-blue-700">Hoàn
                                            thành</span>
                                    @else
                                        <span
                                            class="inline-block py-1 px-3 rounded text-xs font-medium bg-gray-200 text-gray-500 border border-gray-300">Đã
                                            hủy</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-sm italic">{{ Str::limit($app->note, 20) }}</td>
                                <td class="py-4 px-6 text-center">
                                    @if($app->status == 'Pending')
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('booking.edit', $app->appointmentID) }}"
                                                class="text-blue-600 hover:text-blue-800 font-bold text-sm border border-blue-600 hover:bg-blue-50 px-3 py-1 rounded transition">
                                                Sửa
                                            </a>
                                            <form action="{{ route('booking.destroy', $app->appointmentID) }}" method="POST"
                                                onsubmit="return confirm('Xóa lịch này?')" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-gray-500 hover:text-red-600 font-bold text-sm border border-gray-300 hover:border-red-600 hover:bg-red-50 px-3 py-1 rounded transition">
                                                    Hủy
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-client-layout>