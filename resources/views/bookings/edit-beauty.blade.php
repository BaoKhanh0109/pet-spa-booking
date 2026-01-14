<x-client-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-blue-600 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white mb-1">Đặt lịch làm đẹp</h2>
                    <p class="text-blue-100">Spa & Grooming cho {{ $pet->petName }}</p>
                </div>

                <div class="p-8">
                    @if(session('error') || session('success')) ... @endif

                    <form action="{{ route('booking.update', $appointment->appointmentID) }}" method="POST" id="beautyForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="petID" value="{{ $pet->petID }}">

                        <div class="mb-8">
                            <label class="block font-bold text-gray-800 mb-4 text-lg">
                                Chọn dịch vụ
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @php
                                    $selectedServiceIds = $appointment->services->pluck('serviceID')->toArray();
                                @endphp
                                @foreach($services as $service)
                                    <div class="service-item">
                                        <label
                                            class="flex items-start p-4 border rounded-xl cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm">
                                            <input type="checkbox" name="service_ids[]" value="{{ $service->serviceID }}"
                                                class="service-checkbox mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500 rounded border-gray-300"
                                                {{ in_array($service->serviceID, $selectedServiceIds) ? 'checked' : '' }}>
                                            <div class="ml-3 flex-1">
                                                <div class="font-semibold text-gray-800">{{ $service->serviceName }}</div>
                                                <div class="text-sm text-gray-500">{{ $service->description }}</div>
                                                <div class="text-blue-700 font-bold mt-2">
                                                    {{ number_format($service->adjustedPrice) }}đ
                                                    <span class="text-sm bg-blue-100 text-blue-700 px-2 py-1 rounded ml-2">
                                                        Size {{ $service->petSize }}
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block font-bold text-gray-800 mb-3 text-lg">
                                Chọn ngày và giờ hẹn
                            </label>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                                <p class="text-sm text-blue-800">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    <strong>Giờ làm việc:</strong> 09:00 - 17:00, Thứ 2 - Thứ 7.
                                </p>
                            </div>
                            
                            <!-- Date Picker -->
                            <input type="date" id="selectDate" 
                                value="{{ \Carbon\Carbon::parse($appointment->appointmentDate)->format('Y-m-d') }}"
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3 mb-4"
                                required>
                            
                            <!-- Time Slots Grid -->
                            <div id="timeSlots" style="display: none;">
                                <label class="block font-semibold text-gray-700 mb-3">
                                    Chọn giờ hẹn
                                </label>
                                <div id="availableTimeSlots" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 mb-4"></div>
                                
                                <!-- Display selected time -->
                                <div id="selectedTimeDisplay" class="hidden mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <div class="flex items-center text-blue-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-semibold">Thời gian đã chọn: </span>
                                        <span id="selectedTimeText" class="ml-2 font-bold"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Hidden input for form submission -->
                            <input type="hidden" name="appointmentDate" id="appointmentDate"
                                value="{{ \Carbon\Carbon::parse($appointment->appointmentDate)->format('Y-m-d\TH:i') }}"
                                required>
                        </div>

                        <div class="mb-8" id="staffSection" style="display: none;">
                        </div>

                        <div class="mb-8">
                            <label class="block font-bold text-gray-800 mb-2">Ghi chú</label>
                            <textarea name="note" rows="3"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-200 transition p-3">{{ $appointment->note }}</textarea>
                        </div>

                        <div class="flex gap-4 pt-4 border-t">
                            <a href="{{ route('booking.history') }}"
                                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition text-center">
                                ← Quay lại
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg shadow-lg transition transform hover:-translate-y-0.5">
                                Cập nhật lịch hẹn
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const appointmentDate = document.getElementById('appointmentDate');
            
            // Elements for time slot selection
            const selectDate = document.getElementById('selectDate');
            const timeSlots = document.getElementById('timeSlots');
            const availableTimeSlots = document.getElementById('availableTimeSlots');
            const selectedTimeDisplay = document.getElementById('selectedTimeDisplay');
            const selectedTimeText = document.getElementById('selectedTimeText');
            
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            if (selectDate) selectDate.min = today;

            // Handle date selection
            if (selectDate) {
                selectDate.addEventListener('change', function() {
                    const selectedDate = new Date(this.value);
                    const day = selectedDate.getDay();
                    
                    // Check if Sunday
                    if (day === 0) {
                        availableTimeSlots.innerHTML = '<div class="col-span-full text-center text-red-500 p-4 bg-red-50 rounded-lg border border-red-100">Chủ nhật không làm việc</div>';
                        timeSlots.style.display = 'block';
                        return;
                    }
                    
                    // Generate time slots from 09:00 to 17:00 (30 min intervals)
                    const slots = generateTimeSlots(selectedDate);
                    
                    availableTimeSlots.innerHTML = '';
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
                                availableTimeSlots.querySelectorAll('button:not([disabled])').forEach(btn => {
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
                                selectedTimeText.textContent = displayText;
                                selectedTimeDisplay.classList.remove('hidden');
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
                                selectedTimeText.textContent = displayText;
                                selectedTimeDisplay.classList.remove('hidden');
                            }
                        }
                        
                        availableTimeSlots.appendChild(button);
                    });
                    
                    timeSlots.style.display = 'block';
                });
                
                // Trigger change to load initial time slots if date is already selected
                if (selectDate.value) {
                    selectDate.dispatchEvent(new Event('change'));
                }
            }
            
            function generateTimeSlots(date) {
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
        });
    </script>
</x-client-layout>