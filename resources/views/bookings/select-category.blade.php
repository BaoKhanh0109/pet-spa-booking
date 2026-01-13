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
                            @foreach($categories as $category)
                                @php
                                    $routeMap = [
                                        1 => 'booking.beauty',
                                        2 => 'booking.medical',
                                        3 => 'booking.pet-care'
                                    ];
                                    $routeName = $routeMap[$category->categoryID] ?? null;
                                @endphp
                                
                                @if($routeName)
                                    <div class="service-card group cursor-pointer" data-route="{{ $routeName }}">
                                        <div class="border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-blue-500 transition-all duration-300 bg-white hover:bg-blue-50 h-full">
                                            <div class="text-center">
                                                <h3 class="text-xl font-bold mb-2">
                                                    {{ $category->categoryName }}
                                                </h3>
                                                <p class="text-gray-500 text-sm">
                                                    {{ $category->description }}
                                                </p>
                                                
                                                @if($category->capacity)
                                                    <div class="mt-3">
                                                        <span class="inline-block bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-semibold">
                                                            Sức chứa: {{ $category->capacity }} chỗ
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
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
                    
                    const routeName = this.dataset.route;
                    const routes = {
                        'booking.beauty': '{{ route("booking.beauty") }}',
                        'booking.medical': '{{ route("booking.medical") }}',
                        'booking.pet-care': '{{ route("booking.pet-care") }}'
                    };
                    
                    const url = routes[routeName];
                    if (url) {
                        window.location.href = url + '?petID=' + petID;
                    }
                });
            });
        });
    </script>
</x-client-layout>