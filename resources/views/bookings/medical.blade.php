<x-client-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-blue-500 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">Đặt lịch khám bệnh</h2>
                    <p class="text-white">Chăm sóc sức khỏe {{ $pet->petName }}</p>
                </div>

                <div class="p-8">
                    @if(session(key: 'error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-r" role="alert">
                            <p class="font-bold">Lỗi</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="mb-6 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded-r" role="alert">
                            <p class="font-bold">Thành công</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('booking.medical.store') }}" method="POST" id="medicalForm">
                        @csrf
                        <input type="hidden" name="petID" value="{{ $pet->petID }}">

                        <div class="mb-8">
                            <label class="block font-bold text-gray-800 mb-4 text-lg">
                                Chọn dịch vụ y tế
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($services as $service)
                                    <label
                                        class="flex flex-col p-4 border rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm group h-full">
                                        <div class="flex items-start mb-3">
                                            <input type="radio" name="serviceID" value="{{ $service->serviceID }}"
                                                data-duration="{{ $service->duration }}"
                                                class="mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300 flex-shrink-0" 
                                                {{ isset($selectedServiceID) && $selectedServiceID == $service->serviceID ? 'checked' : '' }}
                                                required>
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
                                <label
                                    class="booking-method-card flex flex-col p-5 border rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm">
                                    <div class="flex items-center mb-3">
                                        <input type="radio" name="booking_method" value="by_date"
                                            class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300" checked>
                                        <span class="ml-2 font-bold text-gray-800">Đặt theo ngày</span>
                                    </div>
                                    <div class="text-sm text-gray-500 ml-7">Hệ thống tự động chọn bác sĩ rảnh</div>
                                </label>

                                <label
                                    class="booking-method-card flex flex-col p-5 border rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm">
                                    <div class="flex items-center mb-3">
                                        <input type="radio" name="booking_method" value="by_doctor"
                                            class="w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <span class="ml-2 font-bold text-gray-800">Đặt theo bác sĩ</span>
                                    </div>
                                    <div class="text-sm text-gray-500 ml-7">Chọn bác sĩ và xem lịch rảnh</div>
                                </label>
                            </div>
                        </div>

                        <div id="dateSection" class="mb-8">
                            <div class="bg-gray-100 rounded-xl p-6">
                                <div class="mb-4">
                                    <label class="block font-bold text-gray-800 mb-3 text-lg">Chọn ngày khám:</label>
                                    <input type="date" id="selectDateByDate"
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
                            
                            <input type="hidden" name="appointmentDate" id="appointmentDate" required>
                        </div>

                        <div id="doctorSection" class="mb-8" style="display: none;">
                            <label class="block font-bold text-gray-800 mb-3 text-lg">
                                Chọn bác sĩ
                            </label>
                            <div class="space-y-3 mb-4">
                                @foreach($doctors as $doctor)
                                    <label
                                        class="flex items-center p-4 border rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm">
                                        <input type="radio" name="employeeID" value="{{ $doctor->employeeID }}"
                                            class="doctor-radio w-5 h-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <div class="ml-3 flex-1">
                                            <div class="font-semibold text-gray-800">{{ $doctor->employeeName }}</div>
                                            <div class="text-sm text-gray-500">{{ $doctor->role->roleName ?? 'Bác sĩ' }}</div>
                                            @if($doctor->email)
                                                <div class="text-xs text-gray-400 mt-1">{{ $doctor->email }}</div>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div id="doctorSchedule" class="bg-gray-100 rounded-xl p-6" style="display: none;">
                                <h4 class="font-bold text-gray-800 mb-3">Lịch làm việc của bác sĩ</h4>
                                <div id="scheduleContent"
                                    class="text-sm text-gray-600 mb-6 bg-white p-4 rounded-lg border border-gray-200">
                                    Chọn bác sĩ để xem lịch rảnh...
                                </div>

                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-2">Chọn ngày khám:</label>
                                    <input type="date" id="selectDate"
                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3">
                                </div>

                                <div id="timeSlots" style="display: none;">
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

                                    <div id="availableTimeSlots" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                                    </div>

                                    <div id="selectedTimeDisplay"
                                        class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg hidden text-center">
                                        <p class="text-sm text-blue-800">
                                            Đã chọn: <span id="selectedTimeText"
                                                class="font-bold text-lg block mt-1"></span>
                                        </p>
                                    </div>
                                </div>

                                <input type="hidden" id="appointmentDateDoctor" name="appointmentDateDoctor">
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block font-bold text-gray-800 mb-2">Ghi chú triệu chứng</label>
                            <textarea name="note" rows="4"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3"
                                placeholder="Mô tả triệu chứng hoặc vấn đề sức khỏe..."></textarea>
                        </div>

                        <div class="flex gap-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('booking.select-category') }}"
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
        document.addEventListener('DOMContentLoaded', function () {
            const bookingMethods = document.querySelectorAll('input[name="booking_method"]');
            const dateSection = document.getElementById('dateSection');
            const doctorSection = document.getElementById('doctorSection');
            const appointmentDate = document.getElementById('appointmentDate');
            const appointmentDateDoctor = document.getElementById('appointmentDateDoctor');
            const doctorRadios = document.querySelectorAll('.doctor-radio');
            const doctorSchedule = document.getElementById('doctorSchedule');
            const scheduleContent = document.getElementById('scheduleContent');
            const selectDate = document.getElementById('selectDate');
            const timeSlots = document.getElementById('timeSlots');
            const availableTimeSlots = document.getElementById('availableTimeSlots');
            const selectedTimeDisplay = document.getElementById('selectedTimeDisplay');
            const selectedTimeText = document.getElementById('selectedTimeText');

            let currentDoctorSchedules = [];
            let currentAppointments = [];
            let selectedEmployeeID = null;

            // Set minimum datetime to current time
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            const minDateTime = now.toISOString().slice(0, 16);
            if (appointmentDate) appointmentDate.min = minDateTime;
            
            const today = new Date().toISOString().split('T')[0];
            if (selectDate) selectDate.min = today;

            // Elements for "by date" section
            const selectDateByDate = document.getElementById('selectDateByDate');
            const timeSlotsByDate = document.getElementById('timeSlotsByDate');
            const availableTimeSlotsByDate = document.getElementById('availableTimeSlotsByDate');
            const selectedTimeDisplayByDate = document.getElementById('selectedTimeDisplayByDate');
            const selectedTimeTextByDate = document.getElementById('selectedTimeTextByDate');
            
            if (selectDateByDate) selectDateByDate.min = today;

            // Handle date selection for "by date" method
            if (selectDateByDate) {
                selectDateByDate.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const dayOfWeek = selectedDate.toLocaleDateString('en-US', { weekday: 'long' });
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
                    slots.forEach(slot => {
                        const isPast = new Date(slot.datetime) < new Date();
                        const button = document.createElement('button');
                        button.type = 'button';
                        
                        if (isPast) {
                            button.className = 'p-3 rounded-lg text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200 h-14 flex flex-col items-center justify-center';
                            button.disabled = true;
                            button.innerHTML = `<span class="block font-semibold">${slot.time}</span><span class="text-xs">Qua giờ</span>`;
                        } else {
                            button.className = 'p-3 rounded-lg text-sm font-bold bg-white border border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer h-14 flex items-center justify-center';
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
                        }
                        
                        availableTimeSlotsByDate.appendChild(button);
                    });
                    
                    timeSlotsByDate.style.display = 'block';
                });
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

            bookingMethods.forEach(method => {
                method.addEventListener('change', function () {
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

            doctorRadios.forEach(radio => {
                radio.addEventListener('change', function () {
                    selectedEmployeeID = this.value;
                    doctorSchedule.style.display = 'block';
                    scheduleContent.innerHTML = '<div class="text-center text-gray-500">Đang tải lịch...</div>';

                    fetch('{{ route("booking.doctor-schedule") }}?' + new URLSearchParams({
                        employee_id: selectedEmployeeID,
                        month: new Date().toISOString().slice(0, 7)
                    }))
                        .then(response => {
                            if (!response.ok) throw new Error('Network response was not ok');
                            return response.json();
                        })
                        .then(data => {
                            if (data.error) {
                                scheduleContent.innerHTML = '<div class="text-center text-red-600">Lỗi: ' + data.error + '</div>';
                                return;
                            }

                            currentDoctorSchedules = data.schedules || [];
                            currentAppointments = data.appointments || [];

                            let html = '<div class="mb-2 font-semibold text-gray-700">Lịch làm việc:</div>';

                            if (currentDoctorSchedules.length > 0) {
                                html += '<ul class="space-y-1">';
                                currentDoctorSchedules.forEach(schedule => {
                                    let startTime = schedule.startTime.substring(0, 5);
                                    let endTime = schedule.endTime.substring(0, 5);

                                    html += `<li class="flex items-center text-sm">
                                    <span class="inline-block w-24 font-medium text-gray-800">${schedule.dayOfWeek}</span>
                                    <span class="text-gray-600">${startTime} - ${endTime}</span>
                                </li>`;
                                });
                                html += '</ul>';
                            } else {
                                html += '<p class="text-gray-500 text-sm">Chưa có lịch làm việc</p>';
                            }

                            scheduleContent.innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            scheduleContent.innerHTML = '<div class="text-center text-red-600">Không thể tải lịch làm việc.</div>';
                        });
                });
            });

            if (selectDate) {
                selectDate.addEventListener('change', function () {
                    const selectedDate = new Date(this.value);
                    // Lấy tên ngày bằng tiếng Anh để so sánh với database
                    const dayOfWeek = selectedDate.toLocaleDateString('en-US', { weekday: 'long' });
                    
                    // Tìm lịch làm việc của bác sĩ cho ngày được chọn
                    const schedule = currentDoctorSchedules.find(s => s.dayOfWeek === dayOfWeek);

                    if (!schedule) {
                        availableTimeSlots.innerHTML = '<div class="col-span-full text-center text-red-500 p-4 bg-red-50 rounded-lg border border-red-100">Bác sĩ không làm việc vào ngày này</div>';
                        timeSlots.style.display = 'block';
                        return;
                    }

                    const startTime = schedule.startTime.substring(0, 5);
                    const endTime = schedule.endTime.substring(0, 5);
                    const slots = generateTimeSlots(startTime, endTime, selectedDate);

                    availableTimeSlots.innerHTML = '';
                    slots.forEach(slot => {
                        const isBooked = isTimeSlotBooked(slot.datetime);
                        const isPast = new Date(slot.datetime) < new Date();
                        const isUnavailable = isBooked || isPast;

                        const button = document.createElement('button');
                        button.type = 'button';

                        // Sửa style nút bấm: Xanh dương thay vì xanh lá, và đồng nhất kích thước
                        if (isUnavailable) {
                            button.className = 'p-3 rounded-lg text-sm font-medium bg-gray-100 text-gray-400 cursor-not-allowed border border-gray-200 h-14 flex flex-col items-center justify-center';
                            button.disabled = true;
                            if (isBooked) {
                                button.innerHTML = `<span class="block font-semibold">${slot.time}</span><span class="text-xs">Đã đặt</span>`;
                            } else {
                                button.innerHTML = `<span class="block font-semibold">${slot.time}</span><span class="text-xs">Qua giờ</span>`;
                            }
                        } else {
                            button.className = 'p-3 rounded-lg text-sm font-bold bg-white border border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer h-14 flex items-center justify-center';
                            button.textContent = slot.time;

                            button.addEventListener('click', function () {
                                availableTimeSlots.querySelectorAll('button:not([disabled])').forEach(btn => {
                                    btn.className = 'p-3 rounded-lg text-sm font-bold bg-white border border-blue-200 text-blue-600 hover:bg-blue-50 hover:border-blue-400 transition cursor-pointer h-14 flex items-center justify-center';
                                });

                                // Active state: Blue-600
                                this.className = 'p-3 rounded-lg text-sm font-bold bg-blue-600 border border-blue-600 text-white shadow-md cursor-pointer h-14 flex items-center justify-center';

                                appointmentDateDoctor.value = slot.datetime;

                                const selectedDate = new Date(slot.datetime);
                                // Format thủ công để tránh chữ "lúc"
                                const weekday = selectedDate.toLocaleDateString('vi-VN', { weekday: 'long' });
                                const day = selectedDate.getDate();
                                const month = selectedDate.getMonth() + 1;
                                const year = selectedDate.getFullYear();
                                const hours = String(selectedDate.getHours()).padStart(2, '0');
                                const minutes = String(selectedDate.getMinutes()).padStart(2, '0');
                                const displayText = `${hours}:${minutes} ${weekday}, ${day} tháng ${month}, ${year}`;
                                selectedTimeText.textContent = displayText;
                                selectedTimeDisplay.classList.remove('hidden');
                            });
                        }

                        availableTimeSlots.appendChild(button);
                    });

                    timeSlots.style.display = 'block';
                });
            }

            function generateTimeSlots(startTime, endTime, date) {
                const slots = [];
                const [startHour, startMin] = startTime.split(':').map(Number);
                const [endHour, endMin] = endTime.split(':').map(Number);
                let currentHour = startHour;
                let currentMin = startMin;

                while (currentHour < endHour || (currentHour === endHour && currentMin < endMin)) {
                    const timeStr = `${String(currentHour).padStart(2, '0')}:${String(currentMin).padStart(2, '0')}`;
                    const dateStr = date.toISOString().split('T')[0];
                    const datetimeStr = `${dateStr}T${timeStr}`;
                    slots.push({ time: timeStr, datetime: datetimeStr });
                    currentMin += 30;
                    if (currentMin >= 60) { currentMin = 0; currentHour += 1; }
                }
                return slots;
            }

            function isTimeSlotBooked(datetime) {
                const selectedService = document.querySelector('input[name="serviceID"]:checked');
                if (!selectedService) return false;

                const serviceDuration = parseInt(selectedService.dataset.duration || '30');
                const checkStart = new Date(datetime).getTime();
                const checkEnd = checkStart + (serviceDuration * 60 * 1000);

                return currentAppointments.some(apt => {
                    const aptStart = new Date(apt.appointmentDate).getTime();
                    const aptDuration = parseInt(apt.service?.duration || '30');
                    const aptEnd = aptStart + (aptDuration * 60 * 1000);
                    return !(checkEnd <= aptStart || checkStart >= aptEnd);
                });
            }

            document.getElementById('medicalForm').addEventListener('submit', function (e) {
                const method = document.querySelector('input[name="booking_method"]:checked').value;
                if (method === 'by_doctor') {
                    if (!appointmentDateDoctor.value) {
                        e.preventDefault();
                        alert('Vui lòng chọn giờ khám!');
                        return false;
                    }
                    appointmentDate.value = appointmentDateDoctor.value;
                }
                return true;
            });
        });
    </script>
</x-client-layout>