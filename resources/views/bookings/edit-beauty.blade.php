<x-client-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-5xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-blue-600 p-6 text-center">
                    <h2 class="text-3xl font-bold text-white mb-1">Đặt lịch làm đẹp</h2>
                    <p class="text-blue-100">Spa & Grooming cho {{ $pet->petName }}</p>
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
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($services as $service)
                                    <div class="service-item">
                                        <label
                                            class="flex items-start p-4 border rounded-xl cursor-pointer hover:border-pink-500 hover:bg-pink-50 transition bg-white shadow-sm">
                                            <input type="checkbox" name="service_ids[]" value="{{ $service->serviceID }}"
                                                class="service-checkbox mt-1 w-5 h-5 text-pink-600 focus:ring-pink-500 rounded border-gray-300">
                                            <div class="ml-3 flex-1">
                                                <div class="font-semibold text-gray-800">{{ $service->serviceName }}</div>
                                                <div class="text-sm text-gray-500">{{ $service->description }}</div>
                                                <div class="text-pink-600 font-bold mt-2">
                                                    {{ number_format($service->price) }}đ
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
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition p-3"
                                required>
                        </div>

                        <div class="mb-8" id="staffSection" style="display: none;">
                        </div>

                        <div class="mb-8">
                            <label class="block font-bold text-gray-800 mb-2">Ghi chú</label>
                            <textarea name="note" rows="3"
                                class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-200 transition p-3"></textarea>
                        </div>

                        <div class="flex gap-4 pt-4 border-t">
                            <a href="{{ route('booking.select-category') }}"
                                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold rounded-lg transition text-center">
                                ← Quay lại
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-lg shadow-lg transition transform hover:-translate-y-0.5">
                                Xác nhận đặt lịch
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const appointmentDate = document.getElementById('appointmentDate');
            
            // Set minimum datetime to current time
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            appointmentDate.min = now.toISOString().slice(0, 16);
        });
    </script>
</x-client-layout>