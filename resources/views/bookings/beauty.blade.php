<x-client-layout>
    <div class="py-12 bg-blue-100 min-h-screen">
        <div class="max-w-5xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-blue-500 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white mb-1">Đặt lịch làm đẹp</h2>
                    <p class="text-blue-100">Dịch vụ Spa & Grooming</p>
                </div>

                <div class="p-8">
                    @if(session('error') || session('success')) ... @endif

                    <form action="{{ route('booking.beauty.store') }}" method="POST" id="beautyForm">
                        @csrf
                        <input type="hidden" name="petID" value="{{ $pet->petID }}">

                        <div class="mb-8">
                            <label class="block font-bold text-gray-800 mb-4 text-lg">
                                Chọn dịch vụ
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($services as $service)
                                    <div class="service-item">
                                        <label
                                            class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm">
                                            <input type="checkbox" name="service_ids[]" value="{{ $service->serviceID }}"
                                                class="service-checkbox mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500 rounded border-gray-300"
                                                {{ isset($selectedServiceID) && $selectedServiceID == $service->serviceID ? 'checked' : '' }}>
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
                            
                            <!-- Date Picker -->
                            <input type="date" id="selectDate" 
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
                            <input type="hidden" name="appointmentDate" id="appointmentDate" required>
                        </div>

                        <div class="mb-8" id="staffSection" style="display: none;">
                            <label class="block font-bold text-gray-800 mb-3 text-lg">
                                Chọn nhân viên
                            </label>
                            <div id="staffList" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4"></div>
                            <div class="text-center">
                                <label class="inline-flex items-center cursor-pointer p-3 rounded-lg border-2 border-gray-200 hover:border-blue-400 transition" id="autoSelectLabel">
                                    <input type="radio" name="staff_selection" value="auto" id="autoSelectRadio" checked
                                        class="text-blue-600 focus:ring-blue-500 w-5 h-5">
                                    <span class="ml-2 text-gray-700 font-semibold">Để hệ thống tự động chọn</span>
                                </label>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block font-bold text-gray-800 mb-2">Ghi chú</label>
                            <textarea name="note" rows="3"
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-200 transition p-3"></textarea>
                        </div>

                        <div class="flex gap-4 pt-4 border-t border-gray-100">
                            <a href="{{ route('booking.select-category') }}"
                                class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold rounded-lg transition text-center">
                                Quay lại
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg shadow transition">
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
            const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
            const appointmentDate = document.getElementById('appointmentDate');
            const staffSection = document.getElementById('staffSection');
            const staffList = document.getElementById('staffList');

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
                        appointmentDate.value = '';
                        selectedTimeDisplay.classList.add('hidden');
                        return;
                    }
                    
                    // Generate time slots from 09:00 to 17:00 (30 min intervals)
                    const slots = generateTimeSlots(selectedDate);
                    
                    availableTimeSlots.innerHTML = '';
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
                                
                                // Trigger staff loading
                                loadAvailableStaff();
                            });
                        }
                        
                        availableTimeSlots.appendChild(button);
                    });
                    
                    timeSlots.style.display = 'block';
                });
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

            function loadAvailableStaff() {
                const selectedServices = Array.from(serviceCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                const dateTime = appointmentDate.value;

                console.log('Selected services:', selectedServices);
                console.log('Appointment date:', dateTime);

                if (selectedServices.length > 0 && dateTime) {
                    // Hiển thị loading
                    staffSection.style.display = 'block';
                    staffList.innerHTML = '<div class="col-span-2 text-center text-gray-600 p-4">⏳ Đang tải danh sách nhân viên...</div>';

                    // Tạo query string cho array
                    const params = new URLSearchParams();
                    selectedServices.forEach(id => params.append('service_ids[]', id));
                    params.append('appointment_date', dateTime);

                    const url = '{{ route("booking.available-staff") }}?' + params.toString();
                    console.log('Fetching:', url);

                    fetch(url)
                        .then(response => {
                            console.log('Response status:', response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log('Staff data:', data);
                            staffList.innerHTML = '';

                            if (data.length > 0) {
                                data.forEach(staff => {
                                    const staffCard = `
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition">
                                        <input type="radio" name="employeeID" value="${staff.employeeID}" class="staff-radio w-5 h-5 text-blue-600">
                                        <div class="ml-3">
                                            <div class="font-semibold text-gray-800">${staff.employeeName}</div>
                                            <div class="text-sm text-gray-600">${staff.role || 'Nhân viên'}</div>
                                        </div>
                                    </label>
                                `;
                                    staffList.innerHTML += staffCard;
                                });
                                staffSection.style.display = 'block';
                                
                                // Thêm event listener để bỏ check "tự động chọn" khi chọn nhân viên
                                setTimeout(() => {
                                    const autoSelectRadio = document.getElementById('autoSelectRadio');
                                    const autoSelectLabel = document.getElementById('autoSelectLabel');
                                    
                                    document.querySelectorAll('.staff-radio').forEach(radio => {
                                        radio.addEventListener('change', function() {
                                            if (this.checked) {
                                                autoSelectRadio.checked = false;
                                            }
                                        });
                                    });
                                }, 100);
                            } else {
                                staffList.innerHTML = '<div class="col-span-2 text-center text-amber-600 p-4">Không có nhân viên rảnh vào thời gian này.<br><small>Vui lòng chọn thời gian trong giờ làm việc (09:00 - 17:00, Thứ 2 - Thứ 7).<br>Hoặc để hệ thống tự động sắp xếp lịch phù hợp.</small></div>';
                                staffSection.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading staff:', error);
                            staffList.innerHTML = '<div class="col-span-2 text-center text-red-600 p-4">Có lỗi xảy ra khi tải danh sách nhân viên.<br><small>Vui lòng thử lại hoặc để hệ thống tự chọn.</small></div>';
                            staffSection.style.display = 'block';
                        });
                } else {
                    staffSection.style.display = 'none';
                }
            }

            serviceCheckboxes.forEach(cb => {
                cb.addEventListener('change', loadAvailableStaff);
            });

            appointmentDate.addEventListener('change', loadAvailableStaff);

            // Xử lý khi chọn "tự động chọn" thì bỏ chọn nhân viên cụ thể
            document.addEventListener('change', function(e) {
                if (e.target.name === 'staff_selection' && e.target.value === 'auto') {
                    const autoSelectLabel = document.getElementById('autoSelectLabel');
                    
                    // Bỏ chọn tất cả nhân viên
                    document.querySelectorAll('input[name="employeeID"]').forEach(radio => {
                        radio.checked = false;
                    });
                }
            });

            // Validation: ít nhất 1 dịch vụ
            document.getElementById('beautyForm').addEventListener('submit', function (e) {
                const checkedServices = Array.from(serviceCheckboxes).filter(cb => cb.checked);
                if (checkedServices.length === 0) {
                    e.preventDefault();
                    alert('Vui lòng chọn ít nhất 1 dịch vụ!');
                }
            });
        });
    </script>
</x-client-layout>