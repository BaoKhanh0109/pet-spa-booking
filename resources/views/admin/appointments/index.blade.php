<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            Quản lý Lịch Đặt
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-700">
                <table class="min-w-full text-left">
                    <thead class="bg-gray-900 text-white uppercase text-sm font-bold">
                        <tr>
                            <th class="p-4">Ngày Hẹn</th>
                            <th class="p-4">Khách Hàng</th>
                            <th class="p-4">Thú Cưng</th>
                            <th class="p-4">Dịch Vụ</th>
                            <th class="p-4">Trạng Thái</th>
                            <th class="p-4 text-right">Xử Lý</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300 divide-y divide-gray-700">
                        @foreach($appointments as $app)
                        <tr class="hover:bg-gray-700 transition">
                            <td class="p-4 text-white">{{ $app->appointmentDate }}</td>
                            <td class="p-4">
                                <span class="font-bold text-white">{{ $app->user->name ?? 'Khách vãng lai' }}</span> <br>
                                <span class="text-xs text-gray-500">{{ $app->user->phone ?? 'Không có SĐT' }}</span>
                            </td>
                            <td class="p-4 text-white">{{ $app->pet->petName ?? 'N/A' }}</td>
                            <td class="p-4 text-white">{{ $app->service->serviceName ?? 'N/A' }}</td>

                            <td class="p-4">
                                @if($app->status == 'approved')
                                    <span class="px-2 py-1 rounded text-xs font-bold text-white bg-green-600">
                                        Đã Duyệt
                                    </span>
                                @elseif($app->status == 'canceled')
                                    <span class="px-2 py-1 rounded text-xs font-bold text-white bg-red-600">
                                        Đã Hủy
                                    </span>
                                @else
                                    <span class="px-2 py-1 rounded text-xs font-bold text-white bg-yellow-400">
                                        Chờ Duyệt
                                    </span>
                                @endif
                            </td>

                            <td class="p-4 text-right">
                                <div class="flex justify-end gap-2">
                                    @if($app->status == 'Pending' || $app->status == null || $app->status == '')
                                        <a href="{{ route('admin.appointments.status', ['id' => $app->appointmentID, 'status' => 'approved']) }}" 
                                           class="bg-green hover:bg-green-500 text-white font-bold py-1 px-3 rounded text-sm shadow transition">
                                            Duyệt
                                        </a>
                                    @endif

                                    @if($app->status != 'canceled')
                                        <a href="{{ route('admin.appointments.status', ['id' => $app->appointmentID, 'status' => 'canceled']) }}" 
                                           class="bg-gray-600 hover:bg-gray-500 text-white font-bold py-1 px-3 rounded text-sm shadow transition">
                                            Hủy
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
                @if(method_exists($appointments, 'links'))
                    <div class="p-4 bg-gray-800 border-t border-gray-700">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>