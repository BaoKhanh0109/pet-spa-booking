<x-client-layout>
    <div class="py-12 bg-gradient-to-br from-blue-50 to-purple-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">🐾 Đặt Lịch Hẹn</h2>
                    <p class="text-blue-100">Chọn loại dịch vụ phù hợp cho Boss của bạn</p>
                </div>
                
                <div class="p-8">
                    <!-- Chọn thú cưng -->
                    <div class="mb-8">
                        <label class="block font-bold text-gray-700 mb-3 text-lg">
                            <span class="text-blue-600">1.</span> Chọn Boss của bạn
                        </label>
                        <select id="petSelect" class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3">
                            <option value="">-- Chọn thú cưng --</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->petID }}">{{ $pet->petName }} ({{ $pet->species }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Chọn loại dịch vụ -->
                    <div class="mb-8">
                        <label class="block font-bold text-gray-700 mb-4 text-lg">
                            <span class="text-blue-600">2.</span> Chọn loại dịch vụ
                        </label>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Dịch vụ làm đẹp -->
                            <div class="service-card group cursor-pointer" data-category="beauty">
                                <div class="border-3 border-pink-300 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300 hover:scale-105 bg-gradient-to-br from-pink-50 to-pink-100">
                                    <div class="text-center">
                                        <div class="text-6xl mb-3">💅</div>
                                        <h3 class="text-xl font-bold text-pink-600 mb-2">Làm Đẹp</h3>
                                        <p class="text-gray-600 text-sm">
                                            Spa, tắm gội, cắt tỉa lông
                                        </p>
                                        <div class="mt-4">
                                            <span class="inline-block bg-pink-200 text-pink-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Chọn nhiều dịch vụ
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dịch vụ y tế -->
                            <div class="service-card group cursor-pointer" data-category="medical">
                                <div class="border-3 border-green-300 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300 hover:scale-105 bg-gradient-to-br from-green-50 to-green-100">
                                    <div class="text-center">
                                        <div class="text-6xl mb-3">⚕️</div>
                                        <h3 class="text-xl font-bold text-green-600 mb-2">Y Tế</h3>
                                        <p class="text-gray-600 text-sm">
                                            Khám bệnh, tiêm vaccine
                                        </p>
                                        <div class="mt-4">
                                            <span class="inline-block bg-green-200 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Chọn bác sĩ hoặc ngày
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Dịch vụ trông giữ -->
                            <div class="service-card group cursor-pointer" data-category="pet_care">
                                <div class="border-3 border-orange-300 rounded-2xl p-6 hover:shadow-2xl transition-all duration-300 hover:scale-105 bg-gradient-to-br from-orange-50 to-orange-100">
                                    <div class="text-center">
                                        <div class="text-6xl mb-3">🏠</div>
                                        <h3 class="text-xl font-bold text-orange-600 mb-2">Trông Giữ</h3>
                                        <p class="text-gray-600 text-sm">
                                            Chăm sóc, trông giữ thú cưng
                                        </p>
                                        <div class="mt-4">
                                            <span class="inline-block bg-orange-200 text-orange-700 px-3 py-1 rounded-full text-xs font-semibold">
                                                Chọn ngày gửi - trả
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center text-gray-500 text-sm mt-6">
                        <p>💡 Vui lòng chọn Boss và loại dịch vụ để tiếp tục</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const petSelect = document.getElementById('petSelect');
            const serviceCards = document.querySelectorAll('.service-card');

            serviceCards.forEach(card => {
                card.addEventListener('click', function() {
                    const petID = petSelect.value;
                    if (!petID) {
                        alert('Vui lòng chọn thú cưng trước!');
                        petSelect.focus();
                        return;
                    }

                    const category = this.dataset.category;
                    let url = '';
                    
                    switch(category) {
                        case 'beauty':
                            url = '{{ route("booking.beauty") }}?petID=' + petID;
                            break;
                        case 'medical':
                            url = '{{ route("booking.medical") }}?petID=' + petID;
                            break;
                        case 'pet_care':
                            url = '{{ route("booking.pet-care") }}?petID=' + petID;
                            break;
                    }
                    
                    window.location.href = url;
                });
            });
        });
    </script>
</x-client-layout>
