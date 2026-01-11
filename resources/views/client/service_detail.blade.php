    <x-client-layout>
    <div class="bg-gradient-to-br from-blue-50 to-white py-16 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumb -->
            <div class="mb-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('home') }}" class="text-gray-600 hover:text-blue-600">
                                <i class="fas fa-home mr-2"></i>Trang chủ
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <a href="{{ route('client.services') }}" class="text-gray-600 hover:text-blue-600">Dịch vụ</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-gray-800 font-semibold">{{ $service->serviceName }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

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
                                <span class="px-4 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                    {{ $service->category->categoryName ?? 'Chung' }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">Thời gian:</span>
                                <span class="font-semibold text-gray-800">{{ $service->duration }} phút</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thông tin chi tiết -->
                <div class="space-y-6">
                    <div class="bg-white rounded-2xl p-8 shadow-xl">
                        <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                            {{ $service->serviceName }}
                        </h1>
                        
                        <p class="text-gray-600 leading-relaxed mb-6">
                            {{ $service->description ?? 'Dịch vụ chất lượng cao, thực hiện bởi chuyên viên giàu kinh nghiệm.' }}
                        </p>

                        <!-- Bảng giá theo size -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 mb-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">
                                <i class="fas fa-dollar-sign text-blue-600 mr-2"></i>Bảng giá theo kích cỡ
                            </h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                                <div class="bg-white rounded-lg p-3 text-center border-2 border-gray-200 hover:border-blue-400 transition">
                                    <div class="text-sm text-gray-600 font-semibold mb-1">Size XS</div>
                                    <div class="text-xs text-gray-500 mb-2">≤ 3kg / ≤ 20cm</div>
                                    <div class="text-base font-bold text-gray-800">
                                        {{ number_format($prices['XS'], 0, ',', '.') }}đ
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border-2 border-gray-200 hover:border-blue-400 transition">
                                    <div class="text-sm text-gray-600 font-semibold mb-1">Size S</div>
                                    <div class="text-xs text-gray-500 mb-2">3-10kg / 20-30cm</div>
                                    <div class="text-base font-bold text-gray-800">
                                        {{ number_format($prices['S'], 0, ',', '.') }}đ
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border-2 border-gray-200 hover:border-blue-400 transition">
                                    <div class="text-sm text-gray-600 font-semibold mb-1">Size M</div>
                                    <div class="text-xs text-gray-500 mb-2">10-20kg / 30-40cm</div>
                                    <div class="text-base font-bold text-gray-800">
                                        {{ number_format($prices['M'], 0, ',', '.') }}đ
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border-2 border-gray-200 hover:border-blue-400 transition">
                                    <div class="text-sm text-gray-600 font-semibold mb-1">Size L</div>
                                    <div class="text-xs text-gray-500 mb-2">20-30kg / 40-50cm</div>
                                    <div class="text-base font-bold text-gray-800">
                                        {{ number_format($prices['L'], 0, ',', '.') }}đ
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border-2 border-gray-200 hover:border-blue-400 transition">
                                    <div class="text-sm text-gray-600 font-semibold mb-1">Size XL</div>
                                    <div class="text-xs text-gray-500 mb-2">30-40kg / 50-60cm</div>
                                    <div class="text-base font-bold text-gray-800">
                                        {{ number_format($prices['XL'], 0, ',', '.') }}đ
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 text-center border-2 border-gray-200 hover:border-blue-400 transition">
                                    <div class="text-sm text-gray-600 font-semibold mb-1">Size XXL</div>
                                    <div class="text-xs text-gray-500 mb-2">> 40kg / > 60cm</div>
                                    <div class="text-base font-bold text-gray-800">
                                        {{ number_format($prices['XXL'], 0, ',', '.') }}đ
                                    </div>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-4 text-center italic">
                                * Size được xác định dựa trên cân nặng và chiều dài lưng của thú cưng
                            </p>
                        </div>

                        @auth
                            <!-- Chọn thú cưng để xem giá chính xác -->
                            <div class="bg-white rounded-xl border-2 border-blue-100 p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-4">
                                    <i class="fas fa-paw text-blue-600 mr-2"></i>Chọn thú cưng của bạn
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
                                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-6 border-2 border-blue-200">
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
                                        
                                        <a href="{{ route('booking.select-category') }}" 
                                            class="mt-4 w-full block text-center px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition duration-300 shadow-lg transform hover:scale-105">
                                            <i class="fas fa-calendar-check mr-2"></i>Đặt Lịch Ngay
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center py-6">
                                        <p class="text-gray-600 mb-4">Bạn chưa có thú cưng nào trong hệ thống</p>
                                        <a href="{{ route('pets.create') }}" 
                                            class="inline-block px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition">
                                            <i class="fas fa-plus mr-2"></i>Thêm Thú Cưng
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-xl p-6 text-center">
                                <i class="fas fa-info-circle text-yellow-600 text-3xl mb-3"></i>
                                <p class="text-gray-700 mb-4">Đăng nhập để xem giá chính xác cho thú cưng của bạn!</p>
                                <a href="{{ route('login') }}" 
                                    class="inline-block px-8 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg">
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
        document.getElementById('petSelector').addEventListener('change', function() {
            const petID = this.value;
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
    </script>
    @endif
    @endauth
</x-client-layout>
