<x-client-layout>
    <div class="py-12 bg-blue-100 min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-blue-500 p-8 text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">Đặt Lịch Hẹn</h2>
                    <p class="text-blue-100">Chọn loại dịch vụ phù hợp</p>
                </div>

                <div class="p-8">
                    <div class="mb-8">
                        <label class="block font-bold text-gray-700 mb-3 text-lg">
                            Chọn Thú Cưng
                        </label>
                        <select id="petSelect"
                            class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3">
                            <option value="">-- Chọn danh sách --</option>
                            @foreach($pets as $pet)
                                <option value="{{ $pet->petID }}">{{ $pet->petName }} ({{ $pet->species }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-8">
                        <label class="block font-bold text-gray-700 mb-4 text-lg">
                            Chọn Dịch Vụ
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="service-card group cursor-pointer" data-category="beauty">
                                <div
                                    class="border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-blue-500 transition-all duration-300 bg-white h-full">
                                    <div class="text-center">
                                        <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-600">Làm
                                            Đẹp</h3>
                                        <p class="text-gray-500 text-sm">Spa, tắm gội, cắt tỉa lông</p>
                                    </div>
                                </div>
                            </div>

                            <div class="service-card group cursor-pointer" data-category="medical">
                                <div
                                    class="border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-blue-500 transition-all duration-300 bg-white h-full">
                                    <div class="text-center">
                                        <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-600">Y Tế
                                        </h3>
                                        <p class="text-gray-500 text-sm">Khám bệnh, tiêm vaccine</p>
                                    </div>
                                </div>
                            </div>

                            <div class="service-card group cursor-pointer" data-category="pet_care">
                                <div
                                    class="border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-blue-500 transition-all duration-300 bg-white h-full">
                                    <div class="text-center">
                                        <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-600">Trông
                                            Giữ</h3>
                                        <p class="text-gray-500 text-sm">Chăm sóc lưu trú 24/7</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center text-gray-400 text-sm mt-6 border-t pt-4">
                        <p>Vui lòng chọn thú cưng và loại dịch vụ để tiếp tục</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const petSelect = document.getElementById('petSelect');
            const serviceCards = document.querySelectorAll('.service-card');

            serviceCards.forEach(card => {
                card.addEventListener('click', function () {
                    const petID = petSelect.value;
                    if (!petID) {
                        alert('Vui lòng chọn thú cưng trước!');
                        petSelect.focus();
                        return;
                    }
                    const category = this.dataset.category;
                    let url = '';
                    switch (category) {
                        case 'beauty': url = '{{ route("booking.beauty") }}?petID=' + petID; break;
                        case 'medical': url = '{{ route("booking.medical") }}?petID=' + petID; break;
                        case 'pet_care': url = '{{ route("booking.pet-care") }}?petID=' + petID; break;
                    }
                    window.location.href = url;
                });
            });
        });
    </script>
</x-client-layout>