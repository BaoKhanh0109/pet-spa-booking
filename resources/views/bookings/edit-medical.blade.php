<x-client-layout>
    <div class="py-12 bg-blue-100 min-h-screen">
        <div class="max-w-5xl mx-auto px-4">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-blue-500 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">Chỉnh sửa lịch khám bệnh</h2>
                    <p class="text-blue-100">Chăm sóc sức khỏe cho {{ $appointment->pet->petName }}</p>
                </div>
                
                <div class="p-8">
                    @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                        <p class="font-bold">⚠️ Lỗi</p>
                        <p>{{ session('error') }}</p>
                    </div>
                    @endif

                    <form action="{{ route('booking.update', $appointment->appointmentID) }}" method="POST" id="medicalForm">
                        @csrf
                        @method('PUT')

                        <div class="mb-8">
                            <label class="block font-bold text-gray-700 mb-4 text-lg">
                                Chọn thú cưng
                            </label>
                            <select name="petID" class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3" required>
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->petID }}" {{ $appointment->petID == $pet->petID ? 'selected' : '' }}>
                                        {{ $pet->petName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-8">
                            <label class="block font-bold text-gray-800 mb-4 text-lg">
                                Chọn dịch vụ y tế
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($services as $service)
                                    <label class="flex flex-col p-4 border rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm group h-full">
                                        <div class="flex items-start mb-3">
                                            <input type="radio" name="serviceID" value="{{ $service->serviceID }}" 
                                                   {{ $appointment->serviceID == $service->serviceID ? 'checked' : '' }}
                                                   class="mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300 flex-shrink-0" required>
                                            <div class="ml-3 flex-1">
                                                <div class="font-semibold text-gray-800 group-hover:text-blue-700 leading-tight">
                                                    {{ $service->serviceName }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-500 mb-3 flex-1">{{ $service->description }}</div>
                                        <div class="mt-auto">
                                            <div class="text-blue-600 font-bold text-lg">
                                                {{ number_format($service->adjustedPrice) }}đ
                                            </div>
                                            <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded font-medium">
                                                    Size {{ $service->petSize }}
                                                </span>
                                                <span class="text-xs text-gray-500">{{ $service->duration }} phút</span>
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block font-bold text-gray-800 mb-4 text-lg">
                                Chọn cách đặt lịch
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="booking-method-card flex flex-col p-5 border rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm">
                                    <div class="flex items-center mb-3">
                                        <input type="radio" name="booking_method" value="by_date" class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300" {{ !$appointment->prefer_doctor ? 'checked' : '' }}>
                                        <span class="ml-2 font-bold text-gray-800">Đặt theo ngày</span>
                                    </div>
                                    <div class="text-sm text-gray-500 ml-7">Hệ thống tự động chọn bác sĩ rảnh</div>
                                </label>

                                <label class="booking-method-card flex flex-col p-5 border rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm">
                                    <div class="flex items-center mb-3">
                                        <input type="radio" name="booking_method" value="by_doctor" class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300" {{ $appointment->prefer_doctor ? 'checked' : '' }}>
                                        <span class="ml-2 font-bold text-gray-800">Đặt theo bác sĩ</span>
                                    </div>
                                    <div class="text-sm text-gray-500 ml-7">Chọn bác sĩ và xem lịch rảnh</div>
                                </label>
                            </div>
                        </div>

                        <div id="dateSection" class="mb-8" style="display: {{ !$appointment->prefer_doctor ? 'block' : 'none' }};">
                            <label class="block font-bold text-gray-800 mb-3 text-lg">
                                Chọn ngày và giờ khám
                            </label>
                            <input type="datetime-local" name="appointmentDate" id="appointmentDate" 
                                   value="{{ \Carbon\Carbon::parse($appointment->appointmentDate)->format('Y-m-d\TH:i') }}"
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3" 
                                   {{ !$appointment->prefer_doctor ? 'required' : '' }}>
                            <p class="text-sm text-gray-500 mt-2 italic">Hệ thống sẽ tự động chọn bác sĩ rảnh vào thời gian này.</p>
                        </div>

                        <div id="doctorSection" class="mb-8" style="display: {{ $appointment->prefer_doctor ? 'block' : 'none' }};">
                            <label class="block font-bold text-gray-800 mb-3 text-lg">
                                Chọn bác sĩ
                            </label>
                            <div class="space-y-3 mb-4">
                                @foreach($doctors as $doctor)
                                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm">
                                        <input type="radio" name="employeeID" value="{{ $doctor->employeeID }}" 
                                               {{ $appointment->employeeID == $doctor->employeeID ? 'checked' : '' }}
                                               class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <div class="ml-3 flex-1">
                                            <div class="font-semibold text-gray-800">{{ $doctor->employeeName }}</div>
                                            <div class="text-sm text-gray-500">
                                                @if($doctor->role && isset($doctor->role->roleName))
                                                    {{ $doctor->role->roleName }}
                                                @else
                                                    Bác sĩ
                                                @endif
                                            </div>
                                            @if($doctor->email)
                                                <div class="text-xs text-gray-400 mt-1">{{ $doctor->email }}</div>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                            <div class="mb-4">
                                <label class="block font-semibold text-gray-700 mb-2">Chọn ngày và giờ khám:</label>
                                <input type="datetime-local" id="appointmentDateDoctor" name="appointmentDateDoctor" 
                                       value="{{ \Carbon\Carbon::parse($appointment->appointmentDate)->format('Y-m-d\TH:i') }}"
                                       class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3" 
                                       {{ $appointment->prefer_doctor ? 'required' : '' }}>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block font-bold text-gray-800 mb-2">Ghi chú triệu chứng</label>
                            <textarea name="note" rows="4" 
                                      class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3" 
                                      placeholder="Mô tả triệu chứng hoặc vấn đề sức khỏe...">{{ $appointment->note }}</textarea>
                        </div>

                        <div class="flex gap-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('booking.history') }}" 
                               class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition text-center">
                                Quay lại
                            </a>
                            <button type="submit" 
                                    class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg shadow-lg transition transform hover:-translate-y-0.5">
                                Xác nhận đặt lịch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bookingMethods = document.querySelectorAll('input[name="booking_method"]');
            const dateSection = document.getElementById('dateSection');
            const doctorSection = document.getElementById('doctorSection');
            const appointmentDate = document.getElementById('appointmentDate');
            const appointmentDateDoctor = document.getElementById('appointmentDateDoctor');

            // Set minimum datetime to current time
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            const minDateTime = now.toISOString().slice(0, 16);
            if (appointmentDate) appointmentDate.min = minDateTime;
            if (appointmentDateDoctor) appointmentDateDoctor.min = minDateTime;

            bookingMethods.forEach(method => {
                method.addEventListener('change', function() {
                    if (this.value === 'by_date') {
                        dateSection.style.display = 'block';
                        doctorSection.style.display = 'none';
                        appointmentDate.required = true;
                        appointmentDateDoctor.required = false;
                    } else {
                        dateSection.style.display = 'none';
                        doctorSection.style.display = 'block';
                        appointmentDate.required = false;
                        appointmentDateDoctor.required = true;
                    }
                });
            });
        });
    </script>
</x-client-layout>
