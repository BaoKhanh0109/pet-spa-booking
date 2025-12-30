<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* Ẩn nút tăng giảm mặc định của trình duyệt */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>

    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight" style="font-family: 'Nunito', sans-serif;">
            {{ __('Quản Lý Dịch Vụ') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <h2 class="text-3xl font-bold text-gray-800 mb-2 text-center">Cập Nhật Dịch Vụ</h2>
            <p class="text-center text-gray-500 mb-8">Đang chỉnh sửa: <span class="font-bold text-blue-600">{{ $service->serviceName }}</span></p>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                
                <div class="bg-blue-50 px-8 py-5 border-b border-blue-100">
                    <h3 class="text-blue-700 font-bold uppercase text-sm tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        Thông tin chi tiết
                    </h3>
                </div>

                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                            <p class="font-bold">Vui lòng kiểm tra lại:</p>
                            <ul class="mt-1 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.services.update', $service->serviceID) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') 
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="col-span-1">
                                <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                    Tên dịch vụ
                                </label>
                                <input type="text" name="serviceName" 
                                       value="{{ old('serviceName', $service->serviceName) }}"
                                       class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium" 
                                       required>
                            </div>

                            <div class="col-span-1">
                                <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                    Giá tiền (VNĐ)
                                </label>
                                <div class="relative">
                                    <input type="number" name="price" 
                                           value="{{ old('price', (int)$service->price) }}"
                                           class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium pl-4 appearance-none" 
                                           required>
                                    <span class="absolute right-4 top-3 text-gray-400 font-bold">đ</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                            <label class="block text-gray-700 font-bold mb-3 text-sm uppercase tracking-wider">
                                Hình ảnh dịch vụ
                            </label>

                            <div class="flex flex-col md:flex-row gap-6 items-center">
                                @if($service->serviceImage)
                                    <div class="flex-shrink-0 text-center">
                                        <p class="text-xs text-gray-500 mb-2 font-bold">ẢNH CŨ</p>
                                        <div class="p-1 bg-white rounded-lg shadow-md border border-gray-200 inline-block">
                                            <img src="{{ asset('storage/' . $service->serviceImage) }}" alt="Current Image" class="w-32 h-32 object-cover rounded-md">
                                        </div>
                                    </div>
                                @endif

                                <div class="hidden md:block text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </div>

                                <div class="flex-grow w-full">
                                    <p class="text-xs text-gray-500 mb-2 font-bold md:hidden">CHỌN ẢNH MỚI</p>
                                    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-white hover:bg-blue-50 hover:border-blue-400 transition group relative overflow-hidden">
                                        
                                        <div id="image-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <svg class="w-8 h-8 mb-2 text-gray-400 group-hover:text-blue-500 transition" fill="none" viewBox="0 0 20 16" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                            </svg>
                                            <p class="mb-1 text-sm text-gray-500 group-hover:text-blue-600"><span class="font-bold">Chọn ảnh mới</span> thay thế</p>
                                            <p class="text-xs text-gray-400">Để trống nếu giữ nguyên</p>
                                        </div>

                                        <img id="image-preview" class="hidden w-full h-full object-contain p-2 absolute inset-0 bg-white" alt="New Image Preview" />

                                        <input id="dropzone-file" type="file" name="serviceImage" class="hidden" accept="image/*" onchange="previewImage(this)" />
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                Mô tả chi tiết
                            </label>
                            <textarea name="description" rows="4" 
                                      class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium placeholder-gray-400" 
                                      placeholder="Mô tả về dịch vụ...">{{ old('description', $service->description) }}</textarea>
                        </div>

                        <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                            <a href="{{ route('admin.services.index') }}" class="text-gray-500 hover:text-gray-800 font-bold px-4 py-2 transition duration-200">
                                Hủy bỏ
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform hover:-translate-y-0.5 transition duration-200 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                                Lưu Thay Đổi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            const placeholder = document.getElementById('image-placeholder');
            const preview = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                }

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '#';
                preview.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }
    </script>
</x-app-layout>