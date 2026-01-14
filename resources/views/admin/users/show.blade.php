<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-6">
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Quay lại danh sách
                </a>
            </div>

            <div class="bg-white shadow-lg rounded-xl border border-gray-100 p-8 mb-6">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800">Thông tin người dùng</h2>
                        <p class="text-gray-600 mt-1">ID: #{{ $user->userID }}</p>
                    </div>
                    <span class="mt-2 md:mt-0 px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full 
                        {{ $user->role === 'admin' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                        {{ $user->role === 'admin' ? 'Quản trị viên' : 'Khách hàng' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-32 text-gray-500 font-semibold">Họ tên:</div>
                            <div class="flex-1 text-gray-800 font-bold">{{ $user->name }}</div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 text-gray-500 font-semibold">Email:</div>
                            <div class="flex-1 text-gray-800">{{ $user->email }}</div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 text-gray-500 font-semibold">Điện thoại:</div>
                            <div class="flex-1 text-gray-800">{{ $user->phone ?? 'Chưa cập nhật' }}</div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 text-gray-500 font-semibold">Địa chỉ:</div>
                            <div class="flex-1 text-gray-800">{{ $user->address ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="w-32 text-gray-500 font-semibold">Ngày tạo:</div>
                            <div class="flex-1 text-gray-800">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 text-gray-500 font-semibold">Cập nhật:</div>
                            <div class="flex-1 text-gray-800">
                                {{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'N/A' }}
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 text-gray-500 font-semibold">Số thú cưng:</div>
                            <div class="flex-1 text-blue-600 font-bold">{{ $user->pets->count() }}</div>
                        </div>

                        <div class="flex items-start">
                            <div class="w-32 text-gray-500 font-semibold">Số lịch hẹn:</div>
                            <div class="flex-1 text-green-600 font-bold">{{ $user->appointments->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Danh sách thú cưng -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 p-8 mb-6">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-paw text-blue-500 mr-2"></i>Thú cưng ({{ $user->pets->count() }})
                </h3>

                @if($user->pets->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($user->pets as $pet)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex items-center gap-3 mb-2">
                                    @if($pet->petImage)
                                        <img src="{{ asset('storage/' . $pet->petImage) }}" 
                                            alt="{{ $pet->petName }}"
                                            class="w-16 h-16 rounded-full object-cover border-2 border-blue-300">
                                    @else
                                        <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-paw text-blue-500 text-2xl"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <div class="font-bold text-gray-800">{{ $pet->petName }}</div>
                                        <div class="text-sm text-gray-500">{{ $pet->species ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                
                                <div class="space-y-1 text-sm text-gray-600">
                                    @if($pet->breed)
                                        <div><strong>Giống:</strong> {{ $pet->breed }}</div>
                                    @endif
                                    @if($pet->age)
                                        <div><strong>Tuổi:</strong> {{ $pet->age }}</div>
                                    @endif
                                    @if($pet->weight)
                                        <div><strong>Cân nặng:</strong> {{ $pet->weight }} kg</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-paw text-4xl mb-2 text-gray-300"></i>
                        <p>Người dùng chưa đăng ký thú cưng nào.</p>
                    </div>
                @endif
            </div>

            <!-- Lịch sử đặt lịch -->
            <div class="bg-white shadow-lg rounded-xl border border-gray-100 p-8">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-calendar-check text-green-500 mr-2"></i>Lịch sử đặt lịch ({{ $user->appointments->count() }})
                </h3>

                @if($user->appointments->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-left">
                            <thead class="bg-gray-100 text-gray-700 uppercase text-xs font-bold">
                                <tr>
                                    <th class="py-3 px-4">ID</th>
                                    <th class="py-3 px-4">Thú cưng</th>
                                    <th class="py-3 px-4">Danh mục</th>
                                    <th class="py-3 px-4">Ngày hẹn</th>
                                    <th class="py-3 px-4">Trạng thái</th>
                                    <th class="py-3 px-4">Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 text-sm">
                                @foreach($user->appointments->sortByDesc('appointmentDate') as $appointment)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-3 px-4 font-semibold">#{{ $appointment->appointmentID }}</td>
                                        
                                        <td class="py-3 px-4">
                                            {{ $appointment->pet ? $appointment->pet->petName : 'N/A' }}
                                        </td>

                                        <td class="py-3 px-4">
                                            {{ $appointment->service_category ? $appointment->service_category->categoryName : 'N/A' }}
                                        </td>

                                        <td class="py-3 px-4">
                                            {{ $appointment->appointmentDate ? \Carbon\Carbon::parse($appointment->appointmentDate)->format('d/m/Y H:i') : 'N/A' }}
                                        </td>

                                        <td class="py-3 px-4">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                                    'completed' => 'bg-green-100 text-green-700',
                                                    'cancelled' => 'bg-red-100 text-red-700',
                                                ];
                                                $statusLabels = [
                                                    'pending' => 'Chờ xác nhận',
                                                    'confirmed' => 'Đã xác nhận',
                                                    'completed' => 'Hoàn thành',
                                                    'cancelled' => 'Đã hủy',
                                                ];
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColors[$appointment->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ $statusLabels[$appointment->status] ?? $appointment->status }}
                                            </span>
                                        </td>

                                        <td class="py-3 px-4">
                                            {{ $appointment->note ? Str::limit($appointment->note, 30) : 'Không có' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-calendar-times text-4xl mb-2 text-gray-300"></i>
                        <p>Người dùng chưa có lịch hẹn nào.</p>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
