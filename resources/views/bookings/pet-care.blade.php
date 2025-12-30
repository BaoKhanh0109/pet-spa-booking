<x-client-layout>
    <div class="py-12 bg-gradient-to-br from-orange-50 to-amber-50 min-h-screen">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-amber-600 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">🏠 Đặt Lịch Trông Giữ</h2>
                    <p class="text-orange-100">Gửi {{ $pet->petName }} an tâm trong thời gian bạn bận</p>
                </div>
                
                <div class="p-8">
                    <form action="{{ route('booking.pet-care.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="petID" value="{{ $pet->petID }}">

                        <!-- Thông tin dịch vụ -->
                        <div class="mb-8 bg-orange-50 rounded-xl p-6">
                            <div class="flex items-start">
                                <div class="text-4xl mr-4">🏡</div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $service->serviceName }}</h3>
                                    <p class="text-gray-600 mb-3">{{ $service->description }}</p>
                                    <div class="flex items-center justify-between">
                                        <span class="text-orange-600 font-bold text-xl">{{ number_format($service->price) }}đ/ngày</span>
                                        <span class="bg-orange-200 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold">
                                            ⭐ Dịch vụ trông giữ 24/7
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Chọn ngày gửi -->
                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-3 text-lg">
                                <span class="text-orange-600">1.</span> Ngày gửi Boss
                            </label>
                            <input type="date" name="startDate" id="startDate" 
                                   class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition p-3" 
                                   required>
                            <p class="text-sm text-gray-600 mt-2">📅 Ngày bạn muốn gửi thú cưng</p>
                        </div>

                        <!-- Chọn ngày trả -->
                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-3 text-lg">
                                <span class="text-orange-600">2.</span> Ngày đón Boss về
                            </label>
                            <input type="date" name="endDate" id="endDate" 
                                   class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition p-3" 
                                   required>
                            <p class="text-sm text-gray-600 mt-2">📅 Ngày bạn đón thú cưng về</p>
                        </div>

                        <!-- Hiển thị số ngày và tổng tiền -->
                        <div id="summary" class="mb-6 bg-amber-50 rounded-xl p-5" style="display: none;">
                            <h4 class="font-bold text-gray-700 mb-3 flex items-center">
                                <span class="text-2xl mr-2">📋</span>
                                Tổng quan đặt chỗ
                            </h4>
                            <div class="space-y-2 text-gray-700">
                                <div class="flex justify-between">
                                    <span>Số ngày trông giữ:</span>
                                    <span id="totalDays" class="font-bold">0 ngày</span>
                                </div>
                                <div class="flex justify-between text-lg border-t-2 border-orange-200 pt-2 mt-2">
                                    <span class="font-bold">Tổng tiền dự kiến:</span>
                                    <span id="totalPrice" class="font-bold text-orange-600">0đ</span>
                                </div>
                            </div>
                        </div>

                        <!-- Ghi chú -->
                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-2">Ghi chú</label>
                            <textarea name="note" rows="4" 
                                      class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition p-3" 
                                      placeholder="Thói quen ăn uống, sở thích, lưu ý đặc biệt..."></textarea>
                        </div>

                        <!-- Thông tin bao gồm -->
                        <div class="mb-8 bg-gradient-to-r from-orange-100 to-amber-100 rounded-xl p-5">
                            <h4 class="font-bold text-gray-800 mb-3">✨ Dịch vụ bao gồm:</h4>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-center">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span>Chăm sóc 24/7</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span>Thức ăn theo chế độ</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span>Vui chơi và vận động</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span>Theo dõi sức khỏe hàng ngày</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="text-green-600 mr-2">✓</span>
                                    <span>Cập nhật hình ảnh qua app</span>
                                </li>
                            </ul>
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('booking.select-category') }}" 
                               class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-xl text-center transition">
                                ← Quay lại
                            </a>
                            <button type="submit" 
                                    class="flex-1 bg-gradient-to-r from-orange-500 to-amber-600 hover:from-orange-600 hover:to-amber-700 text-white font-bold py-3 rounded-xl shadow-lg transition transform hover:scale-105">
                                Xác nhận đặt chỗ
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startDate = document.getElementById('startDate');
            const endDate = document.getElementById('endDate');
            const summary = document.getElementById('summary');
            const totalDays = document.getElementById('totalDays');
            const totalPrice = document.getElementById('totalPrice');
            const pricePerDay = {{ $service->price }};

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

            startDate.addEventListener('change', function() {
                endDate.min = this.value;
                calculateTotal();
            });

            endDate.addEventListener('change', calculateTotal);
        });
    </script>
</x-client-layout>
