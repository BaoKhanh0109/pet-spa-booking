    <x-client-layout>
    <div class="bg-gradient-to-br from-blue-50 to-white py-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <!-- Hình ảnh dịch vụ -->
                <div class="space-y-6">
                    <div class="rounded-2xl overflow-hidden shadow-2xl border-4 border-white">
                        <img src="{{ $service->serviceImage ? asset('storage/' . $service->serviceImage) : 'https://picsum.photos/seed/' . $service->serviceID . '/800/600' }}"
                            alt="{{ $service->serviceName }}"
                            class="w-full h-96 object-cover">
                    </div>
                    
                    <!-- Thông tin danh mục -->
                    <div class="bg-white rounded-xl p-6 shadow-lg">
                        <h3 class="text-lg font-bold text-gray-800 mb-4">
                            <i class="fas fa-tag text-blue-600 mr-2"></i>Thông tin dịch vụ
                        </h3>   
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Danh mục:</span>
                                <span class="font-semibold">
                                    {{ $service->category->categoryName ?? 'Chung' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Thời gian:</span>
                                <span class="font-semibold text-gray-800">{{ $service->duration }} phút</span>
                            </div>
                        </div>
                    </div>
                    <div class="mb-6">
                        <a href="{{ route('client.services') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Quay lại
                        </a>
                    </div>
                </div>

                <!-- Thông tin chi tiết -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-8 shadow-xl">
                        <h1 class="text-xl font-extrabold text-gray-900 mb-4">
                            {{ $service->serviceName }}
                        </h1>
                        
                        <p class="text-gray-600 leading-relaxed mb-6">
                            {{ $service->description ?? 'Dịch vụ chất lượng cao, thực hiện bởi chuyên viên giàu kinh nghiệm.' }}
                        </p>

                        @auth
                            <!-- Hiển thị giá cơ bản trước -->
                            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6 mb-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-bold text-gray-800">Giá cơ bản:</span>
                                    <span class="text-3xl font-extrabold text-blue-600">{{ number_format($service->price) }}đ</span>
                                </div>
                            </div>
                            
                            <!-- Chọn thú cưng để xem giá chính xác -->
                            <div class="bg-white rounded-xl border-2 border-blue-100 p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4">
                                    <i class="fas fa-paw text-blue-600 mr-2"></i>Chọn thú cưng để xem giá chính xác
                                </h3>
                                
                                @if($pets->count() > 0)
                                    <select id="petSelector" class="w-full px-4 py-3 rounded-lg border-2 border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition mb-4">
                                        <option value="">-- Chọn thú cưng --</option>
                                        @foreach($pets as $pet)
                                            <option value="{{ $pet->petID }}" data-weight="{{ $pet->weight }}">
                                                {{ $pet->petName }} - {{ $pet->species }} ({{ $pet->weight }}kg)
                                            </option>
                                        @endforeach
                                    </select>

                                    <!-- Hiển thị giá sau khi chọn -->
                                    <div id="priceResult" class="hidden">
                                        <div class="bg-blue-50 rounded-lg p-6 border-2 border-blue-200">
                                            <div class="flex items-center justify-between mb-3">
                                                <div>
                                                    <span class="text-sm text-gray-600">Thú cưng:</span>
                                                    <span class="font-bold text-gray-800 ml-2" id="selectedPetName"></span>
                                                </div>
                                                <div class="px-3 py-1 bg-gray-100 rounded-full text-xs font-semibold text-gray-700" id="sizeBadge"></div>
                                            </div>
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="text-sm text-gray-600">Cân nặng:</span>
                                                <span class="font-semibold text-gray-800" id="selectedPetWeight"></span>
                                            </div>
                                            <div class="flex items-center justify-between mb-4">
                                                <span class="text-sm text-gray-600">Chiều dài lưng:</span>
                                                <span class="font-semibold text-gray-800" id="selectedPetBackLength"></span>
                                            </div>
                                            <div class="pt-4 border-t-2 border-blue-200 flex items-center justify-between">
                                                <span class="text-lg font-bold text-gray-800">Giá dịch vụ:</span>
                                                <span class="text-3xl font-extrabold text-blue-600" id="calculatedPrice"></span>
                                            </div>
                                        </div>
                                        
                                        <a href="#" id="bookNowBtn"
                                            class="mt-4 w-full block text-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition duration-300 shadow-lg transform hover:scale-105">
                                            <i class="fas fa-calendar-check mr-2"></i>Đặt Lịch Ngay
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-6">
                                        <p class="text-gray-600 mb-4">Bạn chưa có thú cưng nào trong hệ thống</p>
                                        <a href="{{ route('pets.create') }}" 
                                            class="inline-block px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                                            <i class="fas fa-plus mr-2"></i>Thêm Thú Cưng
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <!-- Hiển thị giá cơ bản cho khách chưa đăng nhập -->
                            <div class="bg-blue-50 border-2 border-blue-200 rounded-xl p-6">
                                <div class="flex items-center justify-between mb-4">
                                    <span class="text-lg font-bold text-gray-800">Giá cơ bản:</span>
                                    <span class="text-3xl font-extrabold text-blue-600">{{ number_format($service->price) }}đ</span>
                                </div>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                    <p class="text-sm text-gray-700">
                                        <i class="fas fa-info-circle text-yellow-600 mr-2"></i>
                                        <strong>Đăng nhập</strong> để xem giá chính xác theo size thú cưng của bạn
                                    </p>
                                </div>
                                <a href="{{ route('login') }}" 
                                    class="block text-center px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Đăng Nhập
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth
    @if($pets->count() > 0)
    <script>
        const petSelector = document.getElementById('petSelector');
        const bookNowBtn = document.getElementById('bookNowBtn');
        let selectedPetID = null;

        petSelector.addEventListener('change', function() {
            const petID = this.value;
            selectedPetID = petID;
            
            if (!petID) {
                document.getElementById('priceResult').classList.add('hidden');
                return;
            }

            // Lấy thông tin từ option được chọn
            const selectedOption = this.options[this.selectedIndex];
            const petName = selectedOption.text;
            const weight = parseFloat(selectedOption.dataset.weight);

            // Gọi API để tính giá
            fetch('{{ route("services.calculate-price") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    serviceID: {{ $service->serviceID }},
                    petID: petID
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị kết quả
                    document.getElementById('selectedPetName').textContent = data.petName;
                    document.getElementById('selectedPetWeight').textContent = data.weight + ' kg';
                    document.getElementById('selectedPetBackLength').textContent = data.backLength ? data.backLength + ' cm' : 'Chưa có';
                    document.getElementById('calculatedPrice').textContent = data.priceFormatted;
                    
                    // Set badge đơn giản
                    const sizeBadge = document.getElementById('sizeBadge');
                    sizeBadge.textContent = 'Size ' + data.size + ' - ' + data.sizeLabel;
                    
                    document.getElementById('priceResult').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi tính giá. Vui lòng thử lại!');
            });
        });

        // Xử lý nút Đặt Lịch Ngay
        bookNowBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (!selectedPetID) {
                alert('Vui lòng chọn thú cưng trước!');
                return;
            }
            
            // Chuyển đến trang booking với petID và serviceID
            const categoryID = {{ $service->categoryID }};
            let bookingUrl = '';
            
            if (categoryID === 1) { // Beauty
                bookingUrl = '{{ route("booking.beauty") }}?petID=' + selectedPetID + '&serviceID={{ $service->serviceID }}';
            } else if (categoryID === 2) { // Medical
                bookingUrl = '{{ route("booking.medical") }}?petID=' + selectedPetID + '&serviceID={{ $service->serviceID }}';
            } else if (categoryID === 3) { // Pet Care
                bookingUrl = '{{ route("booking.pet-care") }}?petID=' + selectedPetID;
            }
            
            window.location.href = bookingUrl;
        });
    </script>
    @endif
    @endauth
</x-client-layout>
