<x-client-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-blue-500 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">Đặt lịch trông giữ</h2>
                    <p class="text-blue-100">Dịch vụ lưu trú</p>
                </div>

                <div class="p-8">
                    @if(session('error'))
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-pulse">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="font-bold text-red-800">Không thể đặt lịch</p>
                                    <p class="text-red-700 mt-1">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('booking.pet-care.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="petID" value="{{ $pet->petID }}">

                        <div class="mb-8 bg-blue-50 border border-blue-100 rounded-lg p-6">
                            <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $service->serviceName }}</h3>
                            <p class="text-gray-600 mb-3">{{ $service->description }}</p>
                            <div class="flex items-center justify-between flex-wrap gap-2">
                                <div>
                                    <span class="text-blue-700 font-bold text-xl">{{ number_format($service->adjustedPrice) }}đ/ngày</span>
                                    <span class="text-sm bg-blue-100 text-blue-700 px-2 py-1 rounded ml-2">
                                        Size {{ $service->petSize }}
                                    </span>
                                </div>
                                <div class="flex gap-2">
                                    <span
                                        class="bg-white text-blue-800 px-3 py-1 rounded border border-blue-200 text-sm font-semibold">
                                        Dịch vụ 24/7
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block font-bold text-gray-700 mb-2">
                                    Ngày Gửi
                                </label>
                                <input type="date" name="startDate" id="startDate"
                                    class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3"
                                    required>
                            </div>
                            <div>
                                <label class="block font-bold text-gray-700 mb-2">
                                    Ngày Đón
                                </label>
                                <input type="date" name="endDate" id="endDate"
                                    class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3"
                                    required>
                            </div>
                        </div>

                        <div id="summary" class="mb-6 bg-gray-50 border border-gray-200 rounded-lg p-5"
                            style="display: none;">
                            <h4 class="font-bold text-gray-700 mb-3">Tổng quan</h4>
                            <div class="space-y-2 text-gray-700 text-sm">
                                <div class="flex justify-between">
                                    <span>Thời gian:</span>
                                    <span id="totalDays" class="font-bold">0 ngày</span>
                                </div>
                                <div class="flex justify-between text-base border-t border-gray-200 pt-2 mt-2">
                                    <span class="font-bold">Tổng tiền:</span>
                                    <span id="totalPrice" class="font-bold text-blue-600">0đ</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-2">Ghi chú</label>
                            <textarea name="note" rows="4"
                                class="w-full border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3"
                                placeholder="Thói quen ăn uống, lưu ý..."></textarea>
                        </div>

                        <div class="mb-8 border-t border-gray-100 pt-6">
                            <h4 class="font-bold text-gray-800 mb-3 text-sm uppercase">Dịch vụ bao gồm</h4>
                            <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 text-gray-600 text-sm">
                                <li class="flex items-center"><span
                                        class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-2"></span>Chăm sóc 24/7</li>
                                <li class="flex items-center"><span
                                        class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-2"></span>Thức ăn theo chế độ
                                </li>
                                <li class="flex items-center"><span
                                        class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-2"></span>Vui chơi vận động
                                </li>
                                <li class="flex items-center"><span
                                        class="w-1.5 h-1.5 bg-blue-400 rounded-full mr-2"></span>Cập nhật hình ảnh
                                </li>
                            </ul>
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('booking.select-category') }}"
                                class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-3 rounded-lg text-center transition">
                                Quay lại
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg shadow transition">
                                Xác nhận
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            const summary = document.getElementById('summary');
            const totalDays = document.getElementById('totalDays');
            const totalPrice = document.getElementById('totalPrice');
            const pricePerDay = {{ $service->adjustedPrice }};

            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            startDate.min = today;
            endDate.min = today;

            function calculateTotal() {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);

                if (startDate.value && endDate.value && end >= start) {
                    const diffTime = Math.abs(end - start);
                    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // +1 để tính cả ngày đầu

                    totalDays.textContent = diffDays + ' ngày';
                    totalPrice.textContent = (diffDays * pricePerDay).toLocaleString('vi-VN') + 'đ';
                    summary.style.display = 'block';
                } else {
                    summary.style.display = 'none';
                }
            }

            startDate.addEventListener('change', function () {
                endDate.min = this.value;
                calculateTotal();
            });

            endDate.addEventListener('change', calculateTotal);
        });
    </script>
</x-client-layout>