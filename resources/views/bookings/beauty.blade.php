<x-client-layout>
    <div class="py-12 bg-gradient-to-br from-pink-50 to-purple-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-pink-500 to-purple-600 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">💅 Đặt Lịch Làm Đẹp</h2>
                    <p class="text-pink-100">Cho {{ $pet->petName }} xinh xắn hơn mỗi ngày</p>
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

                    <form action="{{ route('booking.beauty.store') }}" method="POST" id="beautyForm">
                        @csrf
                        <input type="hidden" name="petID" value="{{ $pet->petID }}">

                        <!-- Bước 1: Chọn dịch vụ -->
                        <div class="mb-8">
                            <label class="block font-bold text-gray-700 mb-4 text-lg">
                                <span class="text-pink-600">1.</span> Chọn dịch vụ (có thể chọn nhiều)
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($services as $service)
                                <div class="service-item">
                                    <label class="flex items-start p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-pink-400 hover:bg-pink-50 transition">
                                        <input type="checkbox" name="service_ids[]" value="{{ $service->serviceID }}" 
                                               class="service-checkbox mt-1 w-5 h-5 text-pink-600 rounded">
                                        <div class="ml-3 flex-1">
                                            <div class="font-semibold text-gray-800">{{ $service->serviceName }}</div>
                                            <div class="text-sm text-gray-600">{{ $service->description }}</div>
                                            <div class="text-pink-600 font-bold mt-2">{{ number_format($service->price) }}đ</div>
                                        </div>
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bước 2: Chọn ngày giờ -->
                        <div class="mb-8">
                            <label class="block font-bold text-gray-700 mb-3 text-lg">
                                <span class="text-pink-600">2.</span> Chọn ngày và giờ hẹn
                            </label>
                            <input type="datetime-local" name="appointmentDate" id="appointmentDate" 
                                   class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition p-3" 
                                   required>
                        </div>

                        <!-- Bước 3: Hiển thị nhân viên rảnh -->
                        <div class="mb-8" id="staffSection" style="display: none;">
                            <label class="block font-bold text-gray-700 mb-3 text-lg">
                                <span class="text-pink-600">3.</span> Chọn nhân viên (hoặc để hệ thống tự chọn)
                            </label>
                            <div id="staffList" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <!-- Staff will be loaded here via AJAX -->
                            </div>
                            <div class="text-center">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="staff_selection" value="auto" checked class="text-pink-600">
                                    <span class="ml-2 text-gray-700">Để hệ thống tự động chọn nhân viên</span>
                                </label>
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-2">Ghi chú</label>
                            <textarea name="note" rows="3" 
                                      class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-pink-500 focus:ring-2 focus:ring-pink-200 transition p-3" 
                                      placeholder="Yêu cầu đặc biệt..."></textarea>
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('booking.select-category') }}" 
                               class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-xl text-center transition">
                                ← Quay lại
                            </a>
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-pink-500 to-purple-600 hover:from-pink-600 hover:to-purple-700 text-white font-bold py-3 rounded-xl shadow-lg transition transform hover:scale-105">
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
            const serviceCheckboxes = document.querySelectorAll('.service-checkbox');
            const appointmentDate = document.getElementById('appointmentDate');
            const staffSection = document.getElementById('staffSection');
            const staffList = document.getElementById('staffList');

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
                                    <label class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-pink-400 hover:bg-pink-50 transition">
                                        <input type="radio" name="employeeID" value="${staff.employeeID}" class="w-5 h-5 text-pink-600">
                                        <div class="ml-3">
                                            <div class="font-semibold text-gray-800">${staff.employeeName}</div>
                                            <div class="text-sm text-gray-600">${staff.role || 'Nhân viên'}</div>
                                        </div>
                                    </label>
                                `;
                                staffList.innerHTML += staffCard;
                            });
                            staffSection.style.display = 'block';
                        } else {
                            staffList.innerHTML = '<div class="col-span-2 text-center text-gray-600 p-4">⚠️ Không có nhân viên rảnh vào thời gian này.<br><small>Hệ thống sẽ tự động sắp xếp lịch phù hợp.</small></div>';
                            staffSection.style.display = 'block';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading staff:', error);
                        staffList.innerHTML = '<div class="col-span-2 text-center text-red-600 p-4">❌ Có lỗi xảy ra khi tải danh sách nhân viên.<br><small>Vui lòng thử lại hoặc để hệ thống tự chọn.</small></div>';
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

            // Validation: ít nhất 1 dịch vụ
            document.getElementById('beautyForm').addEventListener('submit', function(e) {
                const checkedServices = Array.from(serviceCheckboxes).filter(cb => cb.checked);
                if (checkedServices.length === 0) {
                    e.preventDefault();
                    alert('Vui lòng chọn ít nhất 1 dịch vụ!');
                }
            });
        });
    </script>
</x-client-layout>
