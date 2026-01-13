<x-client-layout>
    <div class="py-12 bg-blue-100 min-h-screen">
        <div class="max-w-3xl mx-auto px-4">
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
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($services as $service)
                                    <div class="service-item">
                                        <label
                                            class="flex items-start p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition bg-white shadow-sm">
                                            <input type="checkbox" name="service_ids[]" value="{{ $service->serviceID }}"
                                                class="service-checkbox mt-1 w-5 h-5 text-blue-600 focus:ring-blue-500 rounded border-gray-300">
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
                            <input type="datetime-local" name="appointmentDate" id="appointmentDate"
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3"
                                required>
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
                                        <input type="radio" name="employeeID" value="${staff.employeeID}" class="staff-radio w-5 h-5 text-pink-600">
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
                                                autoSelectLabel.classList.remove('border-blue-500', 'bg-blue-50');
                                                autoSelectLabel.classList.add('border-gray-200');
                                                
                                                // Highlight selected staff
                                                document.querySelectorAll('.staff-radio').forEach(r => {
                                                    r.closest('label').classList.remove('border-pink-400', 'bg-pink-50');
                                                    r.closest('label').classList.add('border-gray-200');
                                                });
                                                this.closest('label').classList.remove('border-gray-200');
                                                this.closest('label').classList.add('border-pink-400', 'bg-pink-50');
                                            }
                                        });
                                    });
                                }, 100);
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

            // Xử lý khi chọn "tự động chọn" thì bỏ chọn nhân viên cụ thể
            document.addEventListener('change', function(e) {
                if (e.target.name === 'staff_selection' && e.target.value === 'auto') {
                    const autoSelectLabel = document.getElementById('autoSelectLabel');
                    
                    // Bỏ chọn tất cả nhân viên
                    document.querySelectorAll('input[name="employeeID"]').forEach(radio => {
                        radio.checked = false;
                        radio.closest('label').classList.remove('border-pink-400', 'bg-pink-50');
                        radio.closest('label').classList.add('border-gray-200');
                    });
                    
                    // Highlight "tự động chọn"
                    autoSelectLabel.classList.remove('border-gray-200');
                    autoSelectLabel.classList.add('border-blue-500', 'bg-blue-50');
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