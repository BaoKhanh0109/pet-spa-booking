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
                                    @if (Str::contains($employee->role, 'Bác sĩ')) bg-blue-100 text-blue-800
                                    @elseif(Str::contains($employee->role, 'Grooming')) bg-purple-100 text-purple-800
                                    @else bg-green-100 text-green-800 @endif">
                                    {{ $employee->role }}
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
                            <h3 class="text-lg font-bold text-gray-900 mb-4">
                                <i class="fas fa-calendar-alt mr-2 text-green-500"></i>Lịch làm việc
                            </h3>
                            @if ($employee->workSchedules->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach ($employee->workSchedules as $schedule)
                                        <div class="flex items-center justify-between p-3 border rounded-lg">
                                            <div>
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
                                            </div>
                                            <span
                                                class="text-sm text-gray-600">{{ date('H:i', strtotime($schedule->startTime)) }}
                                                - {{ date('H:i', strtotime($schedule->endTime)) }}</span>
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
</x-app-layout>
