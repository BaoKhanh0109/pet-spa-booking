<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Chi tiết Nhân viên') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('admin.employees.edit', $employee->employeeID) }}"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>Chỉnh sửa
                </a>
                <a href="{{ route('admin.employees.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg transition">
                    <i class="fas fa-arrow-left mr-2"></i>Quay lại
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Thông tin cá nhân -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="text-center">
                                @if ($employee->avatar)
                                    <img src="{{ asset('storage/' . $employee->avatar) }}"
                                        alt="{{ $employee->employeeName }}"
                                        class="w-32 h-32 rounded-full mx-auto object-cover border-4 border-gray-200">
                                @else
                                    <div
                                        class="w-32 h-32 rounded-full mx-auto bg-gray-200 flex items-center justify-center border-4 border-gray-100">
                                        <i class="fas fa-user text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                                <h3 class="mt-4 text-xl font-bold text-gray-900">{{ $employee->employeeName }}</h3>
                                <p class="text-sm text-gray-500 mt-1">ID: {{ $employee->employeeID }}</p>
                                <span
                                    class="inline-block mt-2 px-3 py-1 text-sm font-semibold rounded-full
                                    @if ($employee->role && Str::contains($employee->role->roleName, 'Bác sĩ')) bg-blue-100 text-blue-800
                                    @elseif($employee->role && Str::contains($employee->role->roleName, 'Grooming')) bg-purple-100 text-purple-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $employee->role ? $employee->role->roleName : 'Chưa có chức vụ' }}
                                </span>
                            </div>

                            <div class="mt-6 space-y-3">
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-envelope w-6 text-gray-400"></i>
                                    <span class="text-sm">{{ $employee->email }}</span>
                                </div>
                                <div class="flex items-center text-gray-700">
                                    <i class="fas fa-phone w-6 text-gray-400"></i>
                                    <span class="text-sm">{{ $employee->phoneNumber }}</span>
                                </div>
                            </div>

                            @if ($employee->info)
                                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Thông tin thêm</h4>
                                    <p class="text-sm text-gray-600">{{ $employee->info }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Dịch vụ và Lịch làm việc -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Dịch vụ phụ trách -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                <i class="fas fa-briefcase mr-2 text-blue-500"></i>Dịch vụ phụ trách
                            </h3>
                            @if ($employee->services->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @foreach ($employee->services as $service)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                                            <h4 class="font-semibold text-gray-800">{{ $service->serviceName }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $service->description }}</p>
                                            <div class="flex justify-between items-center mt-3">
                                                <span
                                                    class="text-blue-600 font-bold">{{ number_format($service->price) }}đ</span>
                                                <span class="text-xs text-gray-500">{{ $service->duration }} phút</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic">Chưa được phân công dịch vụ nào.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Lịch làm việc -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-900">
                                    <i class="fas fa-calendar-alt mr-2 text-green-500"></i>Lịch làm việc
                                </h3>
                                <button onclick="openScheduleModal()"
                                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition text-sm">
                                    <i class="fas fa-plus mr-1"></i>Thêm lịch
                                </button>
                            </div>

                            @if (session('success'))
                                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($employee->workSchedules->count() > 0)
                                <div class="space-y-2">
                                    @php
                                        $dayOrder = [
                                            'Monday' => 1,
                                            'Tuesday' => 2,
                                            'Wednesday' => 3,
                                            'Thursday' => 4,
                                            'Friday' => 5,
                                            'Saturday' => 6,
                                            'Sunday' => 7,
                                        ];
                                        $sortedSchedules = $employee->workSchedules->sortBy(function ($schedule) use ($dayOrder) {
                                            return $dayOrder[$schedule->dayOfWeek] ?? 8;
                                        });
                                    @endphp

                                    @foreach ($sortedSchedules as $schedule)
                                        <div
                                            class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50 transition">
                                            <div class="flex-1">
                                                <span class="font-semibold text-gray-800">
                                                    @switch($schedule->dayOfWeek)
                                                        @case('Monday')
                                                            Thứ Hai
                                                        @break

                                                        @case('Tuesday')
                                                            Thứ Ba
                                                        @break

                                                        @case('Wednesday')
                                                            Thứ Tư
                                                        @break

                                                        @case('Thursday')
                                                            Thứ Năm
                                                        @break

                                                        @case('Friday')
                                                            Thứ Sáu
                                                        @break

                                                        @case('Saturday')
                                                            Thứ Bảy
                                                        @break

                                                        @case('Sunday')
                                                            Chủ Nhật
                                                        @break
                                                    @endswitch
                                                </span>
                                                <span
                                                    class="ml-3 text-sm text-gray-600">{{ date('H:i', strtotime($schedule->startTime)) }}
                                                    - {{ date('H:i', strtotime($schedule->endTime)) }}</span>
                                            </div>
                                            <div class="flex gap-2">
                                                <button
                                                    onclick="openEditScheduleModal({{ $schedule->scheduleID }}, '{{ $schedule->dayOfWeek }}', '{{ date('H:i', strtotime($schedule->startTime)) }}', '{{ date('H:i', strtotime($schedule->endTime)) }}')"
                                                    class="text-blue-600 hover:text-blue-800 text-sm">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form
                                                    action="{{ route('admin.employees.schedules.destroy', [$employee->employeeID, $schedule->scheduleID]) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('Bạn có chắc muốn xóa lịch này?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic">Chưa có lịch làm việc.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Lịch hẹn gần đây -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                <i class="fas fa-clock mr-2 text-purple-500"></i>Lịch hẹn gần đây
                            </h3>
                            @if ($employee->appointments->count() > 0)
                                <div class="space-y-3">
                                    @foreach ($employee->appointments->take(5) as $appointment)
                                        <div class="border border-gray-200 rounded-lg p-4">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <p class="font-semibold text-gray-800">
                                                        {{ $appointment->user->name ?? 'N/A' }} -
                                                        {{ $appointment->pet->petName ?? 'N/A' }}
                                                    </p>
                                                    <p class="text-sm text-gray-600 mt-1">
                                                        <i class="far fa-calendar mr-1"></i>
                                                        {{ date('d/m/Y H:i', strtotime($appointment->appointmentDate)) }}
                                                    </p>
                                                    @if ($appointment->services->count() > 0)
                                                        <p class="text-sm text-gray-500 mt-1">
                                                            Dịch vụ: {{ $appointment->services->pluck('serviceName')->join(', ') }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <span
                                                    class="px-3 py-1 text-xs font-semibold rounded-full
                                                    @if ($appointment->status == 'Completed') bg-green-100 text-green-800
                                                    @elseif($appointment->status == 'Pending') bg-yellow-100 text-yellow-800
                                                    @elseif($appointment->status == 'Confirmed') bg-blue-100 text-blue-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ $appointment->status }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 italic">Chưa có lịch hẹn nào.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Thêm Lịch Làm Việc -->
    <div id="scheduleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Thêm Lịch Làm Việc</h3>
                <button onclick="closeScheduleModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.employees.schedules.store', $employee->employeeID) }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày trong tuần <span
                                class="text-red-500">*</span></label>
                        <select name="dayOfWeek" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="">-- Chọn ngày --</option>
                            <option value="Monday">Thứ Hai</option>
                            <option value="Tuesday">Thứ Ba</option>
                            <option value="Wednesday">Thứ Tư</option>
                            <option value="Thursday">Thứ Năm</option>
                            <option value="Friday">Thứ Sáu</option>
                            <option value="Saturday">Thứ Bảy</option>
                            <option value="Sunday">Chủ Nhật</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giờ bắt đầu <span
                                class="text-red-500">*</span></label>
                        <input type="time" name="startTime" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giờ kết thúc <span
                                class="text-red-500">*</span></label>
                        <input type="time" name="endTime" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeScheduleModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition">
                        Thêm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Sửa Lịch Làm Việc -->
    <div id="editScheduleModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Sửa Lịch Làm Việc</h3>
                <button onclick="closeEditScheduleModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editScheduleForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ngày trong tuần <span
                                class="text-red-500">*</span></label>
                        <select id="edit_dayOfWeek" name="dayOfWeek" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Chọn ngày --</option>
                            <option value="Monday">Thứ Hai</option>
                            <option value="Tuesday">Thứ Ba</option>
                            <option value="Wednesday">Thứ Tư</option>
                            <option value="Thursday">Thứ Năm</option>
                            <option value="Friday">Thứ Sáu</option>
                            <option value="Saturday">Thứ Bảy</option>
                            <option value="Sunday">Chủ Nhật</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giờ bắt đầu <span
                                class="text-red-500">*</span></label>
                        <input type="time" id="edit_startTime" name="startTime" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Giờ kết thúc <span
                                class="text-red-500">*</span></label>
                        <input type="time" id="edit_endTime" name="endTime" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="closeEditScheduleModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition">
                        Hủy
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition">
                        Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openScheduleModal() {
            document.getElementById('scheduleModal').classList.remove('hidden');
        }

        function closeScheduleModal() {
            document.getElementById('scheduleModal').classList.add('hidden');
        }

        function openEditScheduleModal(scheduleId, dayOfWeek, startTime, endTime) {
            const form = document.getElementById('editScheduleForm');
            form.action = "{{ route('admin.employees.schedules.update', [$employee->employeeID, ':scheduleId']) }}".replace(
                ':scheduleId', scheduleId);

            document.getElementById('edit_dayOfWeek').value = dayOfWeek;
            document.getElementById('edit_startTime').value = startTime;
            document.getElementById('edit_endTime').value = endTime;

            document.getElementById('editScheduleModal').classList.remove('hidden');
        }

        function closeEditScheduleModal() {
            document.getElementById('editScheduleModal').classList.add('hidden');
        }

        // Đóng modal khi click bên ngoài
        window.onclick = function(event) {
            const scheduleModal = document.getElementById('scheduleModal');
            const editScheduleModal = document.getElementById('editScheduleModal');
            if (event.target == scheduleModal) {
                closeScheduleModal();
            }
            if (event.target == editScheduleModal) {
                closeEditScheduleModal();
            }
        }
    </script>
</x-app-layout>
