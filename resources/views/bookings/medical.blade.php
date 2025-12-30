<x-client-layout>
    <div class="py-12 bg-gradient-to-br from-green-50 to-teal-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-green-500 to-teal-600 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">⚕️ Đặt Lịch Khám Bệnh</h2>
                    <p class="text-green-100">Chăm sóc sức khỏe {{ $pet->petName }}</p>
                </div>
                
                <div class="p-8">
                    <!-- Hiển thị thông báo lỗi -->
                    @if(session('error'))
                    <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                        <p class="font-bold">⚠️ Lỗi</p>
                        <p>{{ session('error') }}</p>
                    </div>
                    @endif

                    <!-- Hiển thị thông báo thành công -->
                    @if(session('success'))
                    <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg" role="alert">
                        <p class="font-bold">✓ Thành công</p>
                        <p>{{ session('success') }}</p>
                    </div>
                    @endif

                    <form action="{{ route('booking.medical.store') }}" method="POST" id="medicalForm">
                        @csrf
                        <input type="hidden" name="petID" value="{{ $pet->petID }}">

                        <!-- Bước 1: Chọn dịch vụ y tế -->
                        <div class="mb-8">
                            <label class="block font-bold text-gray-700 mb-4 text-lg">
                                <span class="text-green-600">1.</span> Chọn dịch vụ y tế
                            </label>
                            <div class="space-y-3">
                                @foreach($services as $service)
                                <label class="flex items-start p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition">
                                    <input type="radio" name="serviceID" value="{{ $service->serviceID }}" 
                                           data-duration="{{ $service->duration }}"
                                           class="mt-1 w-5 h-5 text-green-600" required>
                                    <div class="ml-3 flex-1">
                                        <div class="font-semibold text-gray-800">{{ $service->serviceName }}</div>
                                        <div class="text-sm text-gray-600">{{ $service->description }}</div>
                                        <div class="text-green-600 font-bold mt-2">{{ number_format($service->price) }}đ · ⏱️ {{ $service->duration }} phút</div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bước 2: Chọn phương thức đặt lịch -->
                        <div class="mb-8">
                            <label class="block font-bold text-gray-700 mb-4 text-lg">
                                <span class="text-green-600">2.</span> Chọn cách đặt lịch
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="booking-method-card flex flex-col p-5 border-3 border-green-200 rounded-xl cursor-pointer hover:border-green-500 hover:bg-green-50 transition">
                                    <input type="radio" name="booking_method" value="by_date" class="mb-3 w-5 h-5 text-green-600" checked>
                                    <div class="text-3xl mb-2">📅</div>
                                    <div class="font-bold text-gray-800 mb-1">Đặt theo ngày</div>
                                    <div class="text-sm text-gray-600">Hệ thống tự động chọn bác sĩ rảnh</div>
                                </label>

                                <label class="booking-method-card flex flex-col p-5 border-3 border-green-200 rounded-xl cursor-pointer hover:border-green-500 hover:bg-green-50 transition">
                                    <input type="radio" name="booking_method" value="by_doctor" class="mb-3 w-5 h-5 text-green-600">
                                    <div class="text-3xl mb-2">👨‍⚕️</div>
                                    <div class="font-bold text-gray-800 mb-1">Đặt theo bác sĩ</div>
                                    <div class="text-sm text-gray-600">Chọn bác sĩ và xem lịch rảnh</div>
                                </label>
                            </div>
                        </div>

                        <!-- Bước 3a: Đặt theo ngày -->
                        <div id="dateSection" class="mb-8">
                            <label class="block font-bold text-gray-700 mb-3 text-lg">
                                <span class="text-green-600">3.</span> Chọn ngày và giờ khám
                            </label>
                            <input type="datetime-local" name="appointmentDate" id="appointmentDate" 
                                   class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition p-3" 
                                   required>
                            <p class="text-sm text-gray-600 mt-2">💡 Hệ thống sẽ tự động chọn bác sĩ rảnh vào thời gian này</p>
                        </div>

                        <!-- Bước 3b: Đặt theo bác sĩ -->
                        <div id="doctorSection" class="mb-8" style="display: none;">
                            <label class="block font-bold text-gray-700 mb-3 text-lg">
                                <span class="text-green-600">3.</span> Chọn bác sĩ
                            </label>
                            <div class="space-y-3 mb-4">
                                @foreach($doctors as $doctor)
                                <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition">
                                    <input type="radio" name="employeeID" value="{{ $doctor->employeeID }}" 
                                           class="doctor-radio w-5 h-5 text-green-600">
                                    <div class="ml-3 flex-1">
                                        <div class="font-semibold text-gray-800">{{ $doctor->employeeName }}</div>
                                        <div class="text-sm text-gray-600">{{ $doctor->role }}</div>
                                        @if($doctor->email)
                                        <div class="text-xs text-gray-500 mt-1">📧 {{ $doctor->email }}</div>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            <!-- Lịch rảnh của bác sĩ -->
                            <div id="doctorSchedule" class="bg-green-50 rounded-xl p-4" style="display: none;">
                                <h4 class="font-bold text-gray-700 mb-3">📆 Lịch làm việc của bác sĩ</h4>
                                <div id="scheduleContent" class="text-sm text-gray-600 mb-4">
                                    Chọn bác sĩ để xem lịch rảnh...
                                </div>
                                
                                <!-- Chọn ngày -->
                                <div class="mb-4">
                                    <label class="block font-semibold text-gray-700 mb-2">Chọn ngày khám:</label>
                                    <input type="date" id="selectDate" 
                                           class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition p-3">
                                </div>

                                <!-- Khung giờ có sẵn -->
                                <div id="timeSlots" style="display: none;">
                                    <label class="block font-semibold text-gray-700 mb-3">Chọn giờ khám:</label>
                                    
                                    <!-- Chú thích màu sắc -->
                                    <div class="flex gap-4 mb-4 text-xs">
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-white border-2 border-green-300 rounded"></div>
                                            <span>Có thể đặt</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-green-500 border-2 border-green-500 rounded"></div>
                                            <span>Đang chọn</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <div class="w-4 h-4 bg-gray-300 border-2 border-gray-400 rounded"></div>
                                            <span>Không khả dụng</span>
                                        </div>
                                    </div>
                                    
                                    <div id="availableTimeSlots" class="grid grid-cols-3 gap-2">
                                        <!-- Time slots will be generated here -->
                                    </div>
                                    
                                    <!-- Hiển thị giờ đã chọn -->
                                    <div id="selectedTimeDisplay" class="mt-4 p-3 bg-blue-50 border-l-4 border-blue-500 rounded hidden">
                                        <p class="text-sm text-blue-700">
                                            <span class="font-bold">✓ Đã chọn:</span> 
                                            <span id="selectedTimeText" class="font-semibold"></span>
                                        </p>
                                    </div>
                                </div>

                                <!-- Hidden input để lưu datetime -->
                                <input type="hidden" id="appointmentDateDoctor" name="appointmentDateDoctor">
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-2">Ghi chú triệu chứng</label>
                            <textarea name="note" rows="4" 
                                      class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-green-500 focus:ring-2 focus:ring-green-200 transition p-3" 
                                      placeholder="Mô tả triệu chứng hoặc vấn đề sức khỏe của Boss..."></textarea>
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('booking.select-category') }}" 
                               class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-xl text-center transition">
                                ← Quay lại
                            </a>
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-green-500 to-teal-600 hover:from-green-600 hover:to-teal-700 text-white font-bold py-3 rounded-xl shadow-lg transition transform hover:scale-105">
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

            // Đặt min date = hôm nay
            const today = new Date().toISOString().split('T')[0];
            if (selectDate) selectDate.min = today;

            // Toggle giữa đặt theo ngày và đặt theo bác sĩ
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

            // Load lịch rảnh của bác sĩ khi chọn
            doctorRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    selectedEmployeeID = this.value;
                    doctorSchedule.style.display = 'block';
                    scheduleContent.innerHTML = '<div class="text-center">Đang tải lịch...</div>';

                    console.log('Fetching schedule for employee:', selectedEmployeeID);

                    fetch('{{ route("booking.doctor-schedule") }}?' + new URLSearchParams({
                        employee_id: selectedEmployeeID,
                        month: new Date().toISOString().slice(0, 7)
                    }))
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received data:', data);
                        
                        if (data.error) {
                            scheduleContent.innerHTML = '<div class="text-center text-red-600">❌ Lỗi: ' + data.error + '</div>';
                            return;
                        }
                        
                        currentDoctorSchedules = data.schedules || [];
                        currentAppointments = data.appointments || [];
                        
                        let html = '<div class="mb-3"><strong>Lịch làm việc:</strong></div>';
                        
                        if (currentDoctorSchedules.length > 0) {
                            html += '<ul class="space-y-2">';
                            currentDoctorSchedules.forEach(schedule => {
                                // Format time - xử lý cả HH:MM:SS và HH:MM
                                let startTime = schedule.startTime;
                                let endTime = schedule.endTime;
                                
                                if (startTime && startTime.length > 5) {
                                    startTime = startTime.substring(0, 5);
                                }
                                if (endTime && endTime.length > 5) {
                                    endTime = endTime.substring(0, 5);
                                }
                                
                                html += `<li class="flex items-center">
                                    <span class="inline-block w-24 font-semibold">${schedule.dayOfWeek}</span>
                                    <span class="text-gray-600">${startTime} - ${endTime}</span>
                                </li>`;
                            });
                            html += '</ul>';
                        } else {
                            html += '<p class="text-gray-600">Chưa có lịch làm việc</p>';
                        }

                        if (currentAppointments.length > 0) {
                            html += '<div class="mt-4"><strong>Lịch đã đặt:</strong></div>';
                            html += '<ul class="mt-2 space-y-1 text-red-600 text-xs">';
                            currentAppointments.forEach(apt => {
                                html += `<li>🔴 ${new Date(apt.appointmentDate).toLocaleString('vi-VN')}</li>`;
                            });
                            html += '</ul>';
                        }

                        scheduleContent.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error fetching schedule:', error);
                        scheduleContent.innerHTML = '<div class="text-center text-red-600">❌ Không thể tải lịch làm việc. Vui lòng thử lại.</div>';
                    });
                });
            });

            // Khi chọn ngày, generate time slots
            if (selectDate) {
                selectDate.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const dayOfWeek = selectedDate.toLocaleDateString('en-US', { weekday: 'long' });
                    
                    console.log('Selected date:', this.value);
                    console.log('Day of week:', dayOfWeek);
                    console.log('Doctor schedules:', currentDoctorSchedules);
                    
                    // Tìm lịch làm việc của ngày này
                    const schedule = currentDoctorSchedules.find(s => s.dayOfWeek === dayOfWeek);
                    
                    console.log('Found schedule:', schedule);
                    
                    if (!schedule) {
                        availableTimeSlots.innerHTML = '<div class="col-span-3 text-center text-red-600 p-4">❌ Bác sĩ không làm việc vào ngày này</div>';
                        timeSlots.style.display = 'block';
                        return;
                    }

                    // Generate time slots (mỗi 30 phút)
                    const startTime = schedule.startTime.substring(0, 5); // "08:00:00" -> "08:00"
                    const endTime = schedule.endTime.substring(0, 5);
                    
                    const slots = generateTimeSlots(startTime, endTime, selectedDate);
                    
                    availableTimeSlots.innerHTML = '';
                    slots.forEach(slot => {
                        const isBooked = isTimeSlotBooked(slot.datetime);
                        const isPast = new Date(slot.datetime) < new Date();
                        const isUnavailable = isBooked || isPast;
                        
                        const button = document.createElement('button');
                        button.type = 'button';
                        
                        if (isUnavailable) {
                            // Slot không khả dụng - màu xám, không cho click
                            button.className = 'p-3 rounded-lg text-sm font-semibold bg-gray-300 text-gray-500 cursor-not-allowed border-2 border-gray-400';
                            button.disabled = true;
                            button.title = isBooked ? 'Đã có lịch hẹn' : 'Đã qua giờ';
                            
                            // Thêm icon để rõ ràng hơn
                            if (isBooked) {
                                button.innerHTML = `<span class="block">${slot.time}</span><span class="text-xs">🔒 Đã đặt</span>`;
                            } else {
                                button.innerHTML = `<span class="block">${slot.time}</span><span class="text-xs">⏱️ Đã qua</span>`;
                            }
                        } else {
                            // Slot khả dụng - màu xanh, có thể click
                            button.className = 'p-3 rounded-lg text-sm font-semibold bg-white border-2 border-green-300 text-green-700 hover:bg-green-100 hover:border-green-500 hover:shadow-md transition transform hover:scale-105 cursor-pointer';
                            button.textContent = slot.time;
                            button.title = 'Click để chọn giờ này';
                            
                            button.addEventListener('click', function() {
                                console.log('Time slot clicked:', slot.datetime);
                                
                                // Bỏ chọn các button khác
                                availableTimeSlots.querySelectorAll('button:not([disabled])').forEach(btn => {
                                    btn.classList.remove('bg-green-500', 'text-white', 'border-green-500', 'ring-2', 'ring-green-300', 'shadow-lg');
                                    btn.classList.add('bg-white', 'border-green-300', 'text-green-700');
                                });
                                
                                // Chọn button này với hiệu ứng nổi bật
                                this.classList.remove('bg-white', 'border-green-300', 'text-green-700');
                                this.classList.add('bg-green-500', 'text-white', 'border-green-500', 'ring-2', 'ring-green-300', 'shadow-lg');
                                
                                // Set hidden input
                                appointmentDateDoctor.value = slot.datetime;
                                console.log('Set appointmentDateDoctor to:', appointmentDateDoctor.value);
                                
                                // Hiển thị giờ đã chọn
                                const selectedDate = new Date(slot.datetime);
                                const displayText = selectedDate.toLocaleString('vi-VN', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                });
                                selectedTimeText.textContent = displayText;
                                selectedTimeDisplay.classList.remove('hidden');
                            });
                        }
                        
                        availableTimeSlots.appendChild(button);
                    });
                    
                    timeSlots.style.display = 'block';
                });
            }

            // Helper: Generate time slots
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
                    
                    slots.push({
                        time: timeStr,
                        datetime: datetimeStr
                    });
                    
                    // Tăng 30 phút
                    currentMin += 30;
                    if (currentMin >= 60) {
                        currentMin = 0;
                        currentHour += 1;
                    }
                }
                
                return slots;
            }

            // Helper: Check if time slot is booked (kiểm tra overlap với duration)
            function isTimeSlotBooked(datetime) {
                const selectedService = document.querySelector('input[name="serviceID"]:checked');
                if (!selectedService) {
                    console.warn('No service selected when checking time slot');
                    return false;
                }
                
                const serviceDuration = parseInt(selectedService.dataset.duration || '30');
                const checkStart = new Date(datetime).getTime();
                const checkEnd = checkStart + (serviceDuration * 60 * 1000);
                
                console.log('Checking time slot:', datetime);
                console.log('Service duration:', serviceDuration, 'minutes');
                console.log('Check range:', new Date(checkStart).toISOString(), 'to', new Date(checkEnd).toISOString());
                console.log('Current appointments:', currentAppointments);
                
                // Kiểm tra từng appointment
                const hasConflict = currentAppointments.some(apt => {
                    const aptStart = new Date(apt.appointmentDate).getTime();
                    
                    // Lấy duration của appointment hiện có
                    const aptDuration = parseInt(apt.service?.duration || '30');
                    const aptEnd = aptStart + (aptDuration * 60 * 1000);
                    
                    console.log('Comparing with appointment:', apt.appointmentDate);
                    console.log('Appointment range:', new Date(aptStart).toISOString(), 'to', new Date(aptEnd).toISOString());
                    
                    // Kiểm tra overlap: [checkStart, checkEnd] với [aptStart, aptEnd]
                    // Có overlap nếu: checkStart < aptEnd && checkEnd > aptStart
                    const overlap = !(checkEnd <= aptStart || checkStart >= aptEnd);
                    
                    console.log('Has overlap:', overlap);
                    
                    return overlap;
                });
                
                console.log('Final conflict result:', hasConflict);
                return hasConflict;
            }

            // Sync appointment date và validate
            document.getElementById('medicalForm').addEventListener('submit', function(e) {
                const method = document.querySelector('input[name="booking_method"]:checked').value;
                
                console.log('Form submitting with method:', method);
                
                if (method === 'by_doctor') {
                    const doctorDateTime = appointmentDateDoctor.value;
                    console.log('Doctor date time value:', doctorDateTime);
                    
                    if (!doctorDateTime) {
                        e.preventDefault();
                        alert('Vui lòng chọn giờ khám!');
                        return false;
                    }
                    
                    appointmentDate.value = doctorDateTime;
                    console.log('Set appointmentDate to:', appointmentDate.value);
                }
                
                return true;
            });
        });
    </script>
</x-client-layout>
