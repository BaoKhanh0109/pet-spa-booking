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
                                @php
                                    $selectedServiceId = $appointment->services->first()->serviceID ?? null;
                                @endphp
                                @foreach($services as $service)
                                    <label class="flex flex-col p-4 border rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm group h-full">
                                        <div class="flex items-start mb-3">
                                            <input type="radio" name="serviceID" value="{{ $service->serviceID }}" 
                                                   {{ $selectedServiceId == $service->serviceID ? 'checked' : '' }}
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
                            <div class="bg-gray-100 rounded-xl p-6">
                                <div class="mb-4">
                                    <label class="block font-bold text-gray-800 mb-3 text-lg">Chọn ngày khám:</label>
                                    <input type="date" id="selectDateByDate"
                                        value="{{ \Carbon\Carbon::parse($appointment->appointmentDate)->format('Y-m-d') }}"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3">
                                </div>

                                <div id="timeSlotsByDate" style="display: none;">
                                    <label class="block font-semibold text-gray-700 mb-3">Chọn giờ khám:</label>

                                    <div class="flex gap-4 mb-4 text-xs text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-white border border-blue-400 rounded"></div>
                                            <span>Có thể đặt</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-blue-600 rounded"></div>
                                            <span>Đang chọn</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-gray-300 rounded"></div>
                                            <span>Không khả dụng</span>
                                        </div>
                                    </div>

                                    <div id="availableTimeSlotsByDate" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    </div>

                                    <div id="selectedTimeDisplayByDate"
                                        class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden text-center">
                                        <p class="text-sm text-blue-800">
                                            Đã chọn: <span id="selectedTimeTextByDate"
                                                class="font-bold text-lg block mt-1"></span>
                                        </p>
                                    </div>
                                </div>
                                
                                <p class="text-sm text-gray-500 mt-4 italic">* Hệ thống sẽ tự động chọn bác sĩ rảnh vào thời gian này.</p>
                            </div>
                            
                            <input type="hidden" name="appointmentDate" id="appointmentDate" 
                                   value="{{ \Carbon\Carbon::parse($appointment->appointmentDate)->format('Y-m-d\TH:i') }}"
                                   {{ !$appointment->prefer_doctor ? 'required' : '' }}>
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
                            
                            <div class="bg-gray-100 rounded-xl p-6">
                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-3">Chọn ngày khám:</label>
                                    <input type="date" id="selectDateByDoctor"
                                        value="{{ \Carbon\Carbon::parse($appointment->appointmentDate)->format('Y-m-d') }}"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3">
                                </div>

                                <div id="timeSlotsByDoctor" style="display: none;">
                                    <label class="block font-semibold text-gray-700 mb-3">Chọn giờ khám:</label>

                                    <div class="flex gap-4 mb-4 text-xs text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-white border border-blue-400 rounded"></div>
                                            <span>Có thể đặt</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-blue-600 rounded"></div>
                                            <span>Đang chọn</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-gray-300 rounded"></div>
                                            <span>Không khả dụng</span>
                                        </div>
                                    </div>

                                    <div id="availableTimeSlotsByDoctor" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    </div>

                                    <div id="selectedTimeDisplayByDoctor"
                                        class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden text-center">
                                        <p class="text-sm text-blue-800">
                                            Đã chọn: <span id="selectedTimeTextByDoctor"
                                                class="font-bold text-lg block mt-1"></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <input type="hidden" id="appointmentDateDoctor" name="appointmentDateDoctor" 
                                   value="{{ \Carbon\Carbon::parse($appointment->appointmentDate)->format('Y-m-d\TH:i') }}"
                                   {{ $appointment->prefer_doctor ? 'required' : '' }}>
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

            // Elements for "by date" section
            const selectDateByDate = document.getElementById('selectDateByDate');
            const timeSlotsByDate = document.getElementById('timeSlotsByDate');
            const availableTimeSlotsByDate = document.getElementById('availableTimeSlotsByDate');
            const selectedTimeDisplayByDate = document.getElementById('selectedTimeDisplayByDate');
            const selectedTimeTextByDate = document.getElementById('selectedTimeTextByDate');
            
            const today = new Date().toISOString().split('T')[0];
            if (selectDateByDate) selectDateByDate.min = today;

            // Handle date selection for "by date" method
            if (selectDateByDate) {
                selectDateByDate.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const day = selectedDate.getDay();
                    
                    // Check if Sunday
                    if (day === 0) {
                        availableTimeSlotsByDate.innerHTML = '<div class="col-span-full text-center text-red-500 p-4 bg-red-50 rounded-lg border border-red-100">Chủ nhật không làm việc</div>';
                        timeSlotsByDate.style.display = 'block';
                        return;
                    }
                    
                    // Generate time slots from 09:00 to 17:00 (30 min intervals)
                    const slots = generateTimeSlotsForDate(selectedDate);
                    
                    availableTimeSlotsByDate.innerHTML = '';
                    const currentAppointmentTime = appointmentDate.value;
                    
                    slots.forEach(slot => {
                        const isPast = new Date(slot.datetime) < new Date();
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.dataset.datetime = slot.datetime;
                        
                        // Check if this slot matches the current appointment time
                        const isCurrentSlot = currentAppointmentTime && slot.datetime === currentAppointmentTime;
                        
                        if (isPast) {
                            button.className = 'p-3 rounded-lg text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200 h-14 flex flex-col items-center justify-center';
                            button.disabled = true;
                            button.innerHTML = `<span class="block font-semibold">${slot.time}</span><span class="text-xs">Qua giờ</span>`;
                        } else {
                            if (isCurrentSlot) {
                                button.className = 'p-3 rounded-lg text-sm font-bold bg-blue-600 border border-blue-600 text-white shadow-md cursor-pointer h-14 flex items-center justify-center';
                            } else {
                                button.className = 'p-3 rounded-lg text-sm font-bold bg-white border border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer h-14 flex items-center justify-center';
                            }
                            button.textContent = slot.time;
                            
                            button.addEventListener('click', function() {
                                availableTimeSlotsByDate.querySelectorAll('button:not([disabled])').forEach(btn => {
                                    btn.className = 'p-3 rounded-lg text-sm font-bold bg-white border border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer h-14 flex items-center justify-center';
                                });
                                
                                this.className = 'p-3 rounded-lg text-sm font-bold bg-blue-600 border border-blue-600 text-white shadow-md cursor-pointer h-14 flex items-center justify-center';
                                
                                appointmentDate.value = slot.datetime;
                                
                                const displayDate = new Date(slot.datetime);
                                const weekday = displayDate.toLocaleDateString('vi-VN', { weekday: 'long' });
                                const day = displayDate.getDate();
                                const month = displayDate.getMonth() + 1;
                                const year = displayDate.getFullYear();
                                const hours = String(displayDate.getHours()).padStart(2, '0');
                                const minutes = String(displayDate.getMinutes()).padStart(2, '0');
                                const displayText = `${hours}:${minutes} ${weekday}, ${day} tháng ${month}, ${year}`;
                                selectedTimeTextByDate.textContent = displayText;
                                selectedTimeDisplayByDate.classList.remove('hidden');
                            });
                            
                            // Auto-display selected time on page load
                            if (isCurrentSlot) {
                                const displayDate = new Date(slot.datetime);
                                const weekday = displayDate.toLocaleDateString('vi-VN', { weekday: 'long' });
                                const day = displayDate.getDate();
                                const month = displayDate.getMonth() + 1;
                                const year = displayDate.getFullYear();
                                const hours = String(displayDate.getHours()).padStart(2, '0');
                                const minutes = String(displayDate.getMinutes()).padStart(2, '0');
                                const displayText = `${hours}:${minutes} ${weekday}, ${day} tháng ${month}, ${year}`;
                                selectedTimeTextByDate.textContent = displayText;
                                selectedTimeDisplayByDate.classList.remove('hidden');
                            }
                        }
                        
                        availableTimeSlotsByDate.appendChild(button);
                    });
                    
                    timeSlotsByDate.style.display = 'block';
                });
                
                // Trigger change to load initial time slots if date is already selected
                if (selectDateByDate.value) {
                    selectDateByDate.dispatchEvent(new Event('change'));
                }
            }
            
            function generateTimeSlotsForDate(date) {
                const slots = [];
                const startHour = 9;
                const endHour = 17;
                
                for (let hour = startHour; hour < endHour; hour++) {
                    for (let min of [0, 30]) {
                        const timeStr = `${String(hour).padStart(2, '0')}:${String(min).padStart(2, '0')}`;
                        const dateStr = date.toISOString().split('T')[0];
                        const datetimeStr = `${dateStr}T${timeStr}`;
                        slots.push({ time: timeStr, datetime: datetimeStr });
                    }
                }
                return slots;
            }

            // Elements for "by doctor" section
            const selectDateByDoctor = document.getElementById('selectDateByDoctor');
            const timeSlotsByDoctor = document.getElementById('timeSlotsByDoctor');
            const availableTimeSlotsByDoctor = document.getElementById('availableTimeSlotsByDoctor');
            const selectedTimeDisplayByDoctor = document.getElementById('selectedTimeDisplayByDoctor');
            const selectedTimeTextByDoctor = document.getElementById('selectedTimeTextByDoctor');
            
            if (selectDateByDoctor) selectDateByDoctor.min = today;

            // Handle date selection for "by doctor" method
            if (selectDateByDoctor) {
                selectDateByDoctor.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const day = selectedDate.getDay();
                    
                    // Check if Sunday
                    if (day === 0) {
                        availableTimeSlotsByDoctor.innerHTML = '<div class="col-span-full text-center text-red-500 p-4 bg-red-50 rounded-lg border border-red-100">Chủ nhật không làm việc</div>';
                        timeSlotsByDoctor.style.display = 'block';
                        return;
                    }
                    
                    // Generate time slots from 09:00 to 17:00 (30 min intervals)
                    const slots = generateTimeSlotsForDate(selectedDate);
                    
                    availableTimeSlotsByDoctor.innerHTML = '';
                    const currentAppointmentTimeDoctor = appointmentDateDoctor.value;
                    
                    slots.forEach(slot => {
                        const isPast = new Date(slot.datetime) < new Date();
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.dataset.datetime = slot.datetime;
                        
                        // Check if this slot matches the current appointment time
                        const isCurrentSlot = currentAppointmentTimeDoctor && slot.datetime === currentAppointmentTimeDoctor;
                        
                        if (isPast) {
                            button.className = 'p-3 rounded-lg text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200 h-14 flex flex-col items-center justify-center';
                            button.disabled = true;
                            button.innerHTML = `<span class="block font-semibold">${slot.time}</span><span class="text-xs">Qua giờ</span>`;
                        } else {
                            if (isCurrentSlot) {
                                button.className = 'p-3 rounded-lg text-sm font-bold bg-blue-600 border border-blue-600 text-white shadow-md cursor-pointer h-14 flex items-center justify-center';
                            } else {
                                button.className = 'p-3 rounded-lg text-sm font-bold bg-white border border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer h-14 flex items-center justify-center';
                            }
                            button.textContent = slot.time;
                            
                            button.addEventListener('click', function() {
                                availableTimeSlotsByDoctor.querySelectorAll('button:not([disabled])').forEach(btn => {
                                    btn.className = 'p-3 rounded-lg text-sm font-bold bg-white border border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer h-14 flex items-center justify-center';
                                });
                                
                                this.className = 'p-3 rounded-lg text-sm font-bold bg-blue-600 border border-blue-600 text-white shadow-md cursor-pointer h-14 flex items-center justify-center';
                                
                                appointmentDateDoctor.value = slot.datetime;
                                
                                const displayDate = new Date(slot.datetime);
                                const weekday = displayDate.toLocaleDateString('vi-VN', { weekday: 'long' });
                                const day = displayDate.getDate();
                                const month = displayDate.getMonth() + 1;
                                const year = displayDate.getFullYear();
                                const hours = String(displayDate.getHours()).padStart(2, '0');
                                const minutes = String(displayDate.getMinutes()).padStart(2, '0');
                                const displayText = `${hours}:${minutes} ${weekday}, ${day} tháng ${month}, ${year}`;
                                selectedTimeTextByDoctor.textContent = displayText;
                                selectedTimeDisplayByDoctor.classList.remove('hidden');
                            });
                            
                            // Auto-display selected time on page load
                            if (isCurrentSlot) {
                                const displayDate = new Date(slot.datetime);
                                const weekday = displayDate.toLocaleDateString('vi-VN', { weekday: 'long' });
                                const day = displayDate.getDate();
                                const month = displayDate.getMonth() + 1;
                                const year = displayDate.getFullYear();
                                const hours = String(displayDate.getHours()).padStart(2, '0');
                                const minutes = String(displayDate.getMinutes()).padStart(2, '0');
                                const displayText = `${hours}:${minutes} ${weekday}, ${day} tháng ${month}, ${year}`;
                                selectedTimeTextByDoctor.textContent = displayText;
                                selectedTimeDisplayByDoctor.classList.remove('hidden');
                            }
                        }
                        
                        availableTimeSlotsByDoctor.appendChild(button);
                    });
                    
                    timeSlotsByDoctor.style.display = 'block';
                });
                
                // Trigger change to load initial time slots if date is already selected
                if (selectDateByDoctor.value) {
                    selectDateByDoctor.dispatchEvent(new Event('change'));
                }
            }

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
