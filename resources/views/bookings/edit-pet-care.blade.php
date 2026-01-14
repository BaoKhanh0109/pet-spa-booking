<x-client-layout>
    <div class="py-12 bg-blue-100 min-h-screen">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">
                <div class="bg-blue-500 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white mb-2">Chỉnh sửa lịch trông giữ</h2>
                </div>

                <div class="p-8">
                    @if(session('error'))
                        <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg" role="alert">
                            <p class="font-bold">Lỗi</p>
                            <p>{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('booking.update', $appointment->appointmentID) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-8">
                            <label class="block font-bold text-gray-700 mb-4 text-lg">
                                <span class="text-blue-600">1.</span> Chọn thú cưng
                            </label>
                            <select name="petID"
                                class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3"
                                required>
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->petID }}" {{ $appointment->petID == $pet->petID ? 'selected' : '' }}>
                                        {{ $pet->petName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-8 bg-blue-50 rounded-xl p-6">
                            <div class="flex items-start">
                                <div class="text-4xl mr-4"></div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $service->serviceName }}</h3>
                                    <p class="text-gray-600 mb-3">{{ $service->description }}</p>
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="text-blue-700 font-bold text-xl">{{ number_format($service->adjustedPrice) }}đ/ngày</span>
                                    <span class="text-sm bg-blue-100 text-blue-700 px-2 py-1 rounded ml-2">
                                        Size {{ $service->petSize }}
                                    </span>
                                        <span
                                            class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold">
                                            Dịch vụ trông giữ 24/7
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-3 text-lg">
                                Ngày gửi Boss
                            </label>
                            <input type="date" name="startDate" id="startDate"
                                value="{{ \Carbon\Carbon::parse($appointment->appointmentDate)->format('Y-m-d') }}"
                                class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3"
                                required>
                            <p class="text-sm text-gray-600 mt-2">Ngày bạn muốn gửi thú cưng</p>
                        </div>

                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-3 text-lg">
                                Ngày đón Boss về
                            </label>
                            <input type="date" name="endDate" id="endDate"
                                value="{{ $appointment->endDate ? \Carbon\Carbon::parse($appointment->endDate)->format('Y-m-d') : '' }}"
                                class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3"
                                required>
                            <p class="text-sm text-gray-600 mt-2">Ngày bạn đón thú cưng về</p>
                        </div>

                        <div id="summary" class="mb-6 bg-blue-50 rounded-xl p-5" style="display: none;">
                            <h4 class="font-bold text-gray-700 mb-3 flex items-center">
                                Tổng quan đặt chỗ
                            </h4>
                            <div class="space-y-2 text-gray-700">
                                <div class="flex justify-between">
                                    <span>Số ngày trông giữ:</span>
                                    <span id="totalDays" class="font-bold">0 ngày</span>
                                </div>
                                <div class="flex justify-between text-lg border-t-2 border-blue-200 pt-2 mt-2">
                                    <span class="font-bold">Tổng tiền dự kiến:</span>
                                    <span id="totalPrice" class="font-bold text-blue-600">0đ</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block font-bold text-gray-700 mb-2">Ghi chú</label>
                            <textarea name="note" rows="4"
                                class="w-full border-2 border-gray-300 rounded-xl shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 transition p-3"
                                placeholder="Thói quen ăn uống, sở thích, lưu ý đặc biệt...">{{ $appointment->note }}</textarea>
                        </div>

                        <div class="flex gap-4">
                            <a href="{{ route('booking.history') }}"
                                class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-xl text-center transition">
                                ← Quay lại
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl shadow-lg transition transform hover:scale-105">
                                Lưu thay đổi
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

            const today = new Date().toISOString().split('T')[0];
            startDate.min = today;
            endDate.min = today;

            function calculateTotal() {
                if (startDate.value && endDate.value) {
                    const start = new Date(startDate.value);
                    const end = new Date(endDate.value);

                    if (end >= start) {
                        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1;
                        const total = days * pricePerDay;

                        totalDays.textContent = days + ' ngày';
                        totalPrice.textContent = total.toLocaleString('vi-VN') + 'đ';
                        summary.style.display = 'block';
                    } else {
                        summary.style.display = 'none';
                    }
                }
            }

            startDate.addEventListener('change', function () {
                endDate.min = this.value;
                calculateTotal();
            });

            endDate.addEventListener('change', calculateTotal);

            calculateTotal();
        });
    </script>
</x-client-layout>