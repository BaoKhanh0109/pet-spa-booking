<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 text-center md:text-left">Quản lý nhân viên</h2>
                <a href="{{ route('admin.employees.create') }}"
                    class="mt-4 md:mt-0 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 px-6 rounded-lg shadow-md transition transform hover:scale-105 flex items-center gap-2">
                    <i class="fas fa-plus"></i>
                    Thêm Nhân Viên
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
                            <th class="py-4 px-6">Avatar</th>
                            <th class="py-4 px-6">Tên nhân viên</th>
                            <th class="py-4 px-6">Chức vụ</th>
                            <th class="py-4 px-6">Liên hệ</th>
                            <th class="py-4 px-6">Dịch vụ phụ trách</th>
                            <th class="py-4 px-6 text-right">Hành động</th>
                        </tr>
                    </thead>

                    <tbody class="text-gray-600">
                        @forelse($employees as $employee)
                            <tr class="border-b hover:bg-gray-50 transition duration-150">
                                <td class="py-4 px-6">
                                    @if($employee->avatar)
                                        <img class="h-16 w-16 rounded-full object-cover shadow-sm border-2 border-gray-200"
                                            src="{{ asset('storage/' . $employee->avatar) }}" 
                                            alt="{{ $employee->employeeName }}">
                                    @else
                                        <div class="h-16 w-16 rounded-full bg-gray-100 flex items-center justify-center text-gray-400 border-2 border-gray-200">
                                            <i class="fas fa-user text-2xl"></i>
                                        </div>
                                    @endif
                                </td>

                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">{{ $employee->employeeName }}</div>
                                    <div class="text-sm text-gray-500">{{ $employee->email }}</div>
                                </td>

                                <td class="py-4 px-6">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if(Str::contains($employee->role, 'Bác sĩ')) bg-blue-100 text-blue-700
                                        @elseif(Str::contains($employee->role, 'Grooming')) bg-purple-100 text-purple-700
                                        @else bg-green-100 text-green-700 @endif">
                                        {{ $employee->role }}
                                    </span>
                                </td>

                                <td class="py-4 px-6">
                                    <div class="text-gray-700">
                                        <i class="fas fa-phone text-gray-400 mr-2"></i>{{ $employee->phoneNumber }}
                                    </div>
                                </td>

                                <td class="py-4 px-6">
                                    @if($employee->services->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($employee->services as $service)
                                                <span class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded font-medium">
                                                    {{ $service->serviceName }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Chưa phân công</span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('admin.employees.show', $employee->employeeID) }}"
                                            class="text-blue-500 hover:text-blue-600 font-bold transition transform hover:scale-110"
                                            title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.employees.edit', $employee->employeeID) }}"
                                            class="text-yellow-500 hover:text-yellow-600 font-bold transition transform hover:scale-110"
                                            title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.employees.destroy', $employee->employeeID) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa nhân viên này?');"
                                            class="inline">
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
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center text-gray-500">
                                    <p class="mb-4">Chưa có nhân viên nào trong hệ thống.</p>
                                    <a href="{{ route('admin.employees.create') }}" class="text-blue-600 font-bold hover:underline">
                                        Thêm ngay!
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($employees->isNotEmpty())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $employees->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
