<x-client-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-2xl mx-auto px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-blue-600 p-6 text-center">
                    <h2 class="text-2xl font-bold text-white">📅 Đặt Lịch Hẹn</h2>
                    <p class="text-blue-100">Điền thông tin bên dưới để đặt chỗ cho Boss nhé!</p>
                </div>
                
                <div class="p-8">
                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        <div class="mb-5">
                            <label class="block font-bold text-gray-700 mb-2">Chọn Boss</label>
                            <select name="petID" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->petID }}">{{ $pet->petName }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-5">
                             <label class="block font-bold text-gray-700 mb-2">Dịch vụ</label>
                             <select name="serviceID" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                                @foreach($services as $sv)
                                    <option value="{{ $sv->serviceID }}">{{ $sv->serviceName }} - {{ number_format($sv->price) }}đ</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-5">
                            <label class="block font-bold text-gray-700 mb-2">Ngày giờ hẹn</label>
                            <input type="datetime-local" name="appointmentDate" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition" required>
                        </div>

                        <div class="mb-5">
                            <label class="block font-bold text-gray-700 mb-2">Ghi chú</label>
                            <textarea name="note" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 transition"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow transition transform hover:scale-105">
                            Xác nhận đặt Lịch
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-client-layout>