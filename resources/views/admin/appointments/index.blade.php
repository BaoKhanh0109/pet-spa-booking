<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 text-center md:text-left">Quản lý lịch đặt</h2>
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
                            <th class="py-4 px-6">Ngày Đặt</th>
                            <th class="py-4 px-6">Ngày Hẹn</th>
                            <th class="py-4 px-6">Khách Hàng</th>
                            <th class="py-4 px-6">Thú Cưng</th>
                            <th class="py-4 px-6">Nhân Viên</th>
                            <th class="py-4 px-6">Dịch Vụ</th>
                            <th class="py-4 px-6">Trạng Thái</th>
                            <th class="py-4 px-6 text-right">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-600">
                        @foreach($appointments as $app)
                            <tr class="border-b hover:bg-gray-50 transition duration-150">
                                <td class="py-4 px-6 text-gray-600 text-sm">
                                    @if($app->created_at)
                                        {{ \Carbon\Carbon::parse($app->created_at)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-gray-400 italic">Chưa rõ</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 font-semibold text-gray-800">
                                    {{ \Carbon\Carbon::parse($app->appointmentDate)->format('d/m/Y H:i') }}
                                    @if($app->service_categories == 3 && $app->endDate)
                                        <div class="text-xs text-orange-600 mt-1">
                                            <i class="fas fa-calendar-check mr-1"></i>Đón: {{ \Carbon\Carbon::parse($app->endDate)->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    <span class="font-bold text-gray-800">{{ $app->user->name ?? 'Khách vãng lai' }}</span>
                                    <br>
                                    <span class="text-xs text-gray-500">{{ $app->user->phone ?? 'Không có SĐT' }}</span>
                                </td>
                                <td class="py-4 px-6">
                                    @if($app->pet)
                                        <div class="flex items-center gap-2">
                                            <div>
                                                <span class="font-semibold text-gray-800">{{ $app->pet->petName }}</span>
                                                <br>
                                                <span class="text-xs text-gray-500">{{ $app->pet->species ?? 'N/A' }}</span>
                                            </div>
                                            <button onclick="showPetDetail({{ $app->pet->petID }})" 
                                                class="text-blue-500 hover:text-blue-700 transition"
                                                title="Xem chi tiết thú cưng">
                                                <i class="fas fa-info-circle"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">N/A</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($app->employee)
                                        <span class="font-semibold text-gray-800">{{ $app->employee->employeeName }}</span>
                                        <br>
                                        <span class="text-xs text-gray-500">{{ $app->employee->role ? $app->employee->role->roleName : 'Nhân viên' }}</span>
                                    @else
                                        <span class="text-gray-400 italic">Chưa phân công</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-gray-700">
                                    @if($app->services && $app->services->isNotEmpty())
                                        @foreach($app->services as $service)
                                            <span
                                                class="inline-block bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded mb-1">{{ $service->serviceName }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-gray-400 italic">Chưa có dịch vụ</span>
                                    @endif
                                    
                                    @if($app->serviceCategory)
                                        <div class="text-xs text-gray-500 mt-1">
                                            <span class="font-semibold">Loại:</span> {{ $app->serviceCategory->categoryName }}
                                        </div>
                                    @endif
                                </td>

                                <td class="py-4 px-6">
                                    @if($app->status == 'approved')
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white bg-green-500 shadow-sm whitespace-nowrap">
                                            Đã Duyệt
                                        </span>
                                    @elseif($app->status == 'canceled')
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white bg-red-500 shadow-sm whitespace-nowrap">
                                            Đã Hủy
                                        </span>
                                    @else
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white bg-yellow-500 shadow-sm whitespace-nowrap">
                                            Chờ Duyệt
                                        </span>
                                    @endif
                                </td>

                                <td class="py-4 px-6 text-right">
                                    <div class="flex justify-end gap-3">
                                        @if($app->status == 'Pending' || $app->status == null || $app->status == '')
                                            <a href="{{ route('admin.appointments.status', ['id' => $app->appointmentID, 'status' => 'approved']) }}"
                                                class="text-green-500 hover:text-green-600 font-bold transition transform hover:scale-110"
                                                title="Duyệt">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        @endif

                                        @if($app->status != 'canceled')
                                            <a href="{{ route('admin.appointments.status', ['id' => $app->appointmentID, 'status' => 'canceled']) }}"
                                                class="text-red-500 hover:text-red-600 font-bold transition transform hover:scale-110"
                                                title="Hủy" onclick="return confirm('Bạn có chắc muốn hủy lịch hẹn này?')">
                                                <i class="fas fa-times-circle"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                @if($appointments->isEmpty())
                    <div class="p-10 text-center text-gray-500">
                        <p class="mb-4">Chưa có lịch hẹn nào trong hệ thống.</p>
                    </div>
                @endif

                @if(method_exists($appointments, 'links'))
                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                        {{ $appointments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Chi Tiết Thú Cưng -->
    <div id="petDetailModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <!-- Header -->
            <div class="bg-blue-500 p-6 flex justify-between items-center sticky top-0 z-10">
                <h3 class="text-2xl font-bold text-white flex items-center gap-2">
                    Chi Tiết Thú Cưng
                </h3>
                <button onclick="closePetDetailModal()" class="text-white hover:text-gray-300 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Content -->
            <div id="petDetailContent" class="p-6">
                <div class="flex justify-center items-center py-8">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const petData = @json($appointments->pluck('pet')->filter()->keyBy('petID'));

        function showPetDetail(petID) {
            const modal = document.getElementById('petDetailModal');
            const content = document.getElementById('petDetailContent');
            
            modal.classList.remove('hidden');
            
            // Tìm pet trong data
            const pet = Object.values(petData).find(p => p.petID == petID);
            
            if (!pet) {
                content.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-circle text-red-500 text-5xl mb-4"></i>
                        <p class="text-gray-600">Không tìm thấy thông tin thú cưng</p>
                    </div>
                `;
                return;
            }

            // Tính size từ PricingHelper logic
            let size = 'S';
            let sizeLabel = 'Small (Nhỏ)';
            
            if (pet.backLength) {
                if (pet.backLength <= 20) {
                    size = 'XS';
                    sizeLabel = 'Extra Small (Rất nhỏ)';
                } else if (pet.backLength <= 30) {
                    size = 'S';
                    sizeLabel = 'Small (Nhỏ)';
                } else if (pet.backLength <= 40) {
                    size = 'M';
                    sizeLabel = 'Medium (Trung bình)';
                } else if (pet.backLength <= 50) {
                    size = 'L';
                    sizeLabel = 'Large (Lớn)';
                } else if (pet.backLength <= 60) {
                    size = 'XL';
                    sizeLabel = 'Extra Large (Rất lớn)';
                } else {
                    size = 'XXL';
                    sizeLabel = 'Extra Extra Large (Siêu lớn)';
                }
            } else if (pet.weight) {
                if (pet.weight < 5) {
                    size = 'XS';
                    sizeLabel = 'Extra Small (Rất nhỏ)';
                } else if (pet.weight < 10) {
                    size = 'S';
                    sizeLabel = 'Small (Nhỏ)';
                } else if (pet.weight < 20) {
                    size = 'M';
                    sizeLabel = 'Medium (Trung bình)';
                } else if (pet.weight < 30) {
                    size = 'L';
                    sizeLabel = 'Large (Lớn)';
                } else if (pet.weight < 40) {
                    size = 'XL';
                    sizeLabel = 'Extra Large (Rất lớn)';
                } else {
                    size = 'XXL';
                    sizeLabel = 'Extra Extra Large (Siêu lớn)';
                }
            }

            content.innerHTML = `
                <div class="space-y-6">
                    <!-- Ảnh đại diện -->
                    ${pet.petImage ? `
                        <div class="flex justify-center">
                            <img src="/storage/${pet.petImage}" 
                                 alt="${pet.petName}"
                                 class="w-48 h-48 object-cover rounded-full border-4 border-gray-300 shadow-lg">
                        </div>
                    ` : `
                        <div class="flex justify-center">
                            <div class="w-48 h-48 bg-gray-200 rounded-full flex items-center justify-center border-4 border-gray-300">
                                <i class="fas fa-paw text-6xl text-gray-400"></i>
                            </div>
                        </div>
                    `}

                    <!-- Tên và Size Badge -->
                    <div class="text-center">
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">${pet.petName}</h2>
                        <span class="inline-block px-4 py-2 rounded-full bg-gray-100 text-gray-700 font-semibold text-sm">
                            Size ${size} - ${sizeLabel}
                        </span>
                    </div>

                    <!-- Thông tin chi tiết -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Loài</p>
                            <p class="text-lg font-semibold text-gray-800">${pet.species || 'Chưa rõ'}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Giống</p>
                            <p class="text-lg font-semibold text-gray-800">${pet.breed || 'Chưa rõ'}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Tuổi</p>
                            <p class="text-lg font-semibold text-gray-800">${pet.age ? pet.age + ' năm' : 'Chưa rõ'}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Cân nặng</p>
                            <p class="text-lg font-semibold text-gray-800">${pet.weight ? pet.weight + ' kg' : 'Chưa rõ'}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Chiều dài lưng</p>
                            <p class="text-lg font-semibold text-gray-800">${pet.backLength ? pet.backLength + ' cm' : 'Chưa đo'}</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-500 mb-1">Size (dùng để tính giá)</p>
                            <p class="text-lg font-semibold text-gray-800">Size ${size}</p>
                        </div>
                    </div>

                    <!-- Tiền sử bệnh -->
                    ${pet.medicalHistory ? `
                        <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded-r-lg">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-triangle text-gray-600 text-xl mt-1"></i>
                                <div>
                                    <p class="font-bold text-gray-800 mb-1">Tiền sử bệnh / Lưu ý đặc biệt:</p>
                                    <p class="text-gray-700">${pet.medicalHistory}</p>
                                </div>
                            </div>
                        </div>
                    ` : `
                        <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded-r-lg">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-check-circle text-gray-600 text-xl"></i>
                                <p class="text-gray-700 font-semibold">Không có tiền sử bệnh đặc biệt</p>
                            </div>
                        </div>
                    `}

                    <!-- Ghi chú về size -->
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-700">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Lưu ý:</strong> Size được tính ${pet.backLength ? 'dựa trên chiều dài lưng' : 'dựa trên cân nặng'} 
                            để áp dụng bảng giá phù hợp khi đặt dịch vụ.
                        </p>
                    </div>
                </div>
            `;
        }

        function closePetDetailModal() {
            const modal = document.getElementById('petDetailModal');
            modal.classList.add('hidden');
        }

        // Đóng modal khi click bên ngoài
        document.getElementById('petDetailModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePetDetailModal();
            }
        });
    </script>
</x-app-layout>