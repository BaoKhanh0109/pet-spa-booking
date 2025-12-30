<x-app-layout>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">

    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight" style="font-family: 'Nunito', sans-serif;">
            {{ __('Quản Lý Dịch Vụ') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen" style="font-family: 'Nunito', sans-serif;">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Thêm Dịch Vụ Mới</h2>
            
            <div class="bg-white overflow-hidden shadow-lg rounded-xl border border-gray-100">
                
                <div class="bg-blue-50 px-8 py-5 border-b border-blue-100">
                    <h3 class="text-blue-700 font-bold uppercase text-sm tracking-wider flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                        </svg>
                        Thông tin chi tiết
                    </h3>
                </div>

                <div class="p-8">
                    <form action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="col-span-1">
                                <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                    Tên dịch vụ
                                </label>
                                <input type="text" name="serviceName" 
                                       class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium placeholder-gray-400" 
                                       placeholder="VD: Spa Cao Cấp..." required>
                            </div>

                            <div class="col-span-1">
                                <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                    Giá tiền (VNĐ)
                                </label>
                                <div class="relative">
                                    <input type="number" name="price" 
                                           class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 pr-10 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium placeholder-gray-400" 
                                           placeholder="VD: 500000" required>
                                    <span class="absolute right-4 top-3 text-gray-400 font-bold">đ</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                Hình ảnh minh họa
                            </label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-blue-50 hover:border-blue-400 transition group relative overflow-hidden">
                                    
                                    <div id="image-placeholder" class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-blue-500 transition" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 group-hover:text-blue-600"><span class="font-bold">Bấm để chọn ảnh</span> hoặc kéo thả</p>
                                        <p class="text-xs text-gray-400">SVG, PNG, JPG (Max 2MB)</p>
                                    </div>

                                    <img id="image-preview" class="hidden w-full h-full object-contain p-2 absolute inset-0 bg-white" alt="Image preview" />

                                    <input id="dropzone-file" type="file" name="serviceImage" class="hidden" accept="image/*" onchange="previewImage(this)" />
                                </label>
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-gray-700 font-bold mb-2 text-sm uppercase tracking-wider">
                                Mô tả chi tiết
                            </label>
                            <textarea name="description" rows="4" 
                                      class="w-full bg-gray-50 text-gray-900 border border-gray-200 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition shadow-sm font-medium placeholder-gray-400" 
                                      placeholder="Mô tả về quy trình dịch vụ, lợi ích..."></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-4 border-t border-gray-100 pt-6">
                            <a href="{{ route('admin.services.index') }}" class="text-gray-500 hover:text-gray-800 font-bold px-4 py-2 transition duration-200">
                                Hủy bỏ
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transform hover:-translate-y-0.5 transition duration-200 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                Lưu Dịch Vụ
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