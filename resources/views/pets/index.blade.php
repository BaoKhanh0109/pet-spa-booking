<x-client-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-3xl font-extrabold text-gray-900">Hồ sơ thú cưng</h2>
                </div>
                <a href="{{ route('pets.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg transition transform hover:-translate-y-1 flex items-center gap-2">
                    Thêm thú cưng
                </a>
            </div>

            @if(session('success'))
                <div
                    class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm flex items-center justify-between">
                    <div>
                        <span class="font-bold">Thành công!</span> {{ session('success') }}
                    </div>
                    <button onclick="this.parentElement.style.display='none'"
                        class="text-green-700 font-bold">&times;</button>
                </div>
            @endif

            @if(session('error'))
                <div
                    class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm flex items-center justify-between">
                    <div>
                        <span class="font-bold"></span>Lỗi!</span> {{ session('error') }}
                    </div>
                    <button onclick="this.parentElement.style.display='none'"
                        class="text-red-700 font-bold">&times;</button>
                </div>
            @endif

            @if($pets->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center border border-gray-100">
                    <h3 class="text-xl font-bold text-gray-800">Bạn chưa có thú cưng nào</h3>
                    <p class="text-gray-500 mt-2 mb-6">Hãy thêm hồ sơ cho Boss ngay nhé!</p>
                    <a href="{{ route('pets.create') }}" class="inline-block text-blue-600 font-bold hover:underline">Thêm
                        ngay &rarr;</a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($pets as $pet)
                        <div
                            class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden border border-gray-100 flex flex-col group">

                            <div class="h-56 bg-gray-200 flex items-center justify-center relative overflow-hidden group">
                                @if($pet->petImage)
                                    <img src="{{ asset('storage/' . $pet->petImage) }}" onclick="openImageModal(this.src)"
                                        class="w-full h-full object-contain transition duration-500 group-hover:scale-105 cursor-zoom-in"
                                        alt="{{ $pet->petName }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-300 bg-gray-50">
                                        <span class="text-6xl">🐾</span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-2xl font-bold text-gray-800">{{ $pet->petName }}</h3>
                                    <span
                                        class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded-md">{{ $pet->breed ?? 'Chưa rõ giống' }}</span>
                                </div>

                                <div class="space-y-2 text-gray-600 text-sm mb-6 flex-1">
                                    <p class="flex items-center gap-2">
                                        Tuổi: <span class="font-semibold text-gray-800">{{ $pet->age ?? '?' }}</span>
                                    </p>
                                    <p class="flex items-center gap-2">
                                        Cân nặng: <span class="font-semibold text-gray-800">{{ $pet->weight ?? '?' }} kg</span>
                                    </p>
                                    @if($pet->medicalHistory)
                                        <p class="flex items-start gap-2 mt-2 pt-2 border-t border-gray-100">
                                            <span class="italic">{{ Str::limit($pet->medicalHistory, 40) }}</span>
                                        </p>
                                    @endif
                                </div>

                                <div class="flex gap-3 mt-auto pt-4 border-t border-gray-100">
                                    <a href="{{ route('pets.edit', $pet->petID) }}"
                                        class="flex-1 bg-yellow-500 text-white hover:bg-yellow-600 border font-bold py-2 rounded-lg text-center transition text-sm">
                                        Sửa
                                    </a>

                                    <form action="{{ route('pets.destroy', $pet->petID) }}" method="POST" class="flex-1"
                                        onsubmit="return confirm('Bạn chắc chắn muốn xóa bé {{ $pet->petName }}?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="w-full bg-red-500 text-white hover:bg-red-600 border border-red-600 hover:border-red-700 font-bold py-2 rounded-lg text-center transition text-sm">
                                            Xóa
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-client-layout>