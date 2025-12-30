<x-client-layout>
    <div class="bg-blue-50 py-16 border-b border-blue-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                Dịch Vụ <span class="text-blue-600">Spa & Chăm Sóc</span>
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto mb-8">
                Chúng tôi cung cấp những dịch vụ tốt nhất để Boss của bạn luôn xinh đẹp, khỏe mạnh và hạnh phúc.
            </p>
            
            <form action="{{ route('client.services') }}" method="GET" class="max-w-xl mx-auto relative group">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Bạn đang tìm dịch vụ gì? (VD: Cắt tỉa...)" 
                       class="w-full pl-6 pr-14 py-4 rounded-full border border-gray-200 shadow-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700 outline-none transition">
                <button type="submit" class="absolute right-2 top-2 bg-blue-600 text-white p-2.5 rounded-full hover:bg-blue-700 transition shadow-lg transform group-hover:scale-105">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </form>
        </div>
    </div>

    <div class="py-16 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if($services->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                    @foreach($services as $sv)
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-2xl transition duration-300 border border-gray-100 overflow-hidden flex flex-col h-full hover:-translate-y-1">
                        
                        <div class="h-56 overflow-hidden relative bg-gray-100">
                             <img src="{{ $sv->serviceImage ? asset('storage/' . $sv->serviceImage) : 'https://picsum.photos/seed/'.$sv->serviceID.'/600/400' }}" 
                                  alt="{{ $sv->serviceName }}" 
                                  class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                             
                             </div>

                        <div class="p-8 flex-1 flex flex-col">
                            <h3 class="text-2xl font-bold text-gray-900 mb-3 group-hover:text-blue-600 transition">
                                {{ $sv->serviceName }}
                            </h3>
                            
                            <p class="text-gray-500 mb-6 flex-1 leading-relaxed line-clamp-3">
                                {{ $sv->description ?? 'Dịch vụ chất lượng cao, thực hiện bởi chuyên viên giàu kinh nghiệm.' }}
                            </p>

                            <div class="flex items-center justify-between gap-4 pt-4 border-t border-gray-50 mt-auto">
                                <div class="flex flex-col">
                                    <span class="text-xs text-gray-400 font-bold uppercase tracking-wider">Chi phí</span>
                                    <span class="text-2xl font-extrabold text-blue-600">
                                        {{ number_format($sv->price) }} <span class="text-lg">đ</span>
                                    </span>
                                </div>

                                <a href="{{ route('booking.create') }}" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-900 transition duration-300 shadow-lg transform active:scale-95 whitespace-nowrap">
                                    Đặt Lịch
                                </a>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <h3 class="text-xl font-bold text-gray-800">Không tìm thấy dịch vụ nào</h3>
                    <p class="text-gray-500 mt-2">Thử tìm từ khóa khác xem sao nhé!</p>
                    <a href="{{ route('client.services') }}" class="mt-6 inline-block px-6 py-2 bg-blue-600 text-white rounded-full font-semibold hover:bg-blue-700 transition shadow">Xem tất cả dịch vụ</a>
                </div>
            @endif

        </div>
    </div>
</x-client-layout>