<x-client-layout>
    <div class="bg-blue-50 py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-10 md:mb-0">
                <h1 class="text-5xl font-extrabold text-gray-900 leading-tight mb-6">
                    Chăm sóc thú cưng <br>
                    <span class="text-blue-600">Chuẩn 5 sao ⭐</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-lg">
                    Dịch vụ Spa, Cắt tỉa, Trông giữ và Điều trị với đội ngũ bác sĩ giàu kinh nghiệm. Đặt lịch ngay để
                    nhận ưu đãi!
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('booking.create') }}"
                        class="bg-blue-600 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:bg-blue-700 hover:shadow-xl transition transform hover:-translate-y-1">
                        Đặt Lịch Ngay
                    </a>
                    <a href="{{ route('client.services') }}"
                        class="bg-white text-blue-600 px-8 py-3 rounded-full font-bold shadow hover:bg-gray-50 transition border border-gray-200">
                        Xem Dịch Vụ
                    </a>
                </div>
            </div>
            <div class="md:w-1/2 relative">
                <img src="https://images.unsplash.com/photo-1583337130417-3346a1be7dee?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80"
                    class="rounded-3xl shadow-2xl w-full object-cover transform rotate-2 hover:rotate-0 transition duration-500">
            </div>
        </div>
    </div>

 
</x-client-layout>