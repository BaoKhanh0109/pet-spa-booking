<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PetCare') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
                transform: translateX(100%);
            }
        }
        
        .animate-slide-in-right {
            animation: slideInRight 0.5s ease-out;
        }
        
        .animate-fade-out {
            animation: fadeOut 0.5s ease-out forwards;
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800 antialiased flex flex-col min-h-screen">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center gap-2 cursor-pointer"
                    onclick="window.location='{{ route('home') }}'">
                    <span class="text-3xl">🐾</span>
                    <span class="font-extrabold text-2xl text-blue-600 tracking-tight">PetCare</span>
                </div>

                <div class="hidden md:flex space-x-8 items-center">
                    <a href="{{ route('home') }}"
                        class="text-gray-600 hover:text-blue-600 font-bold transition {{ request()->routeIs('home') ? 'text-blue-600' : '' }}">Trang
                        chủ</a>
                    <a href="{{ route('client.services') }}"
                        class="text-gray-600 hover:text-blue-600 font-bold transition {{ request()->routeIs('client.services') ? 'text-blue-600' : '' }}">Dịch
                        vụ</a>

                    @auth
                        <a href="{{ route('booking.create') }}"
                            class="text-gray-600 hover:text-blue-600 font-bold transition {{ request()->routeIs('booking.create') ? 'text-blue-600' : '' }}">Đặt
                            lịch</a>
                        <a href="{{ route('pets.index') }}"
                            class="text-gray-600 hover:text-blue-600 font-bold transition {{ request()->routeIs('pets.*') ? 'text-blue-600' : '' }}">Thú
                            cưng</a>
                        <a href="{{ route('booking.history') }}"
                            class="text-gray-600 hover:text-blue-600 font-bold transition {{ request()->routeIs('booking.history') ? 'text-blue-600' : '' }}">Lịch
                            sử</a>

                        <div class="relative ml-4 group">
                            <button class="flex items-center text-gray-500 hover:text-blue-600 focus:outline-none py-2">
                                <span class="font-bold mr-1">{{ Auth::user()->name }}</span>
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>

                            <div class="absolute right-0 top-full pt-2 w-48 hidden group-hover:block z-50">

                                <div class="bg-white rounded-md shadow-lg py-1 border border-gray-100">
                                    <a href="{{ route('profile.edit') }}"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 font-semibold">Thông tin cá nhân</a>
                                    
                                    @if(Auth::user()->role == 'admin')
                                        <a href="{{ route('admin.appointments.index') }}"
                                            class="block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 font-bold">Vào trang
                                            Admin</a>
                                    @endif
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Đăng
                                            xuất</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="bg-blue-600 text-white px-5 py-2.5 rounded-full hover:bg-blue-700 shadow-md font-bold transition transform hover:scale-105">Đăng
                            nhập</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div id="flash-message" class="fixed top-24 right-4 z-50 animate-slide-in-right">
            <div class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 max-w-md">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">{{ session('success') }}</span>
                <button onclick="document.getElementById('flash-message').remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div id="flash-message" class="fixed top-24 right-4 z-50 animate-slide-in-right">
            <div class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 max-w-md">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">{{ session('error') }}</span>
                <button onclick="document.getElementById('flash-message').remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    @if(session('info'))
        <div id="flash-message" class="fixed top-24 right-4 z-50 animate-slide-in-right">
            <div class="bg-blue-500 text-white px-6 py-4 rounded-lg shadow-2xl flex items-center gap-3 max-w-md">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="font-semibold">{{ session('info') }}</span>
                <button onclick="document.getElementById('flash-message').remove()" class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <main class="flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-gray-900 text-gray-300 py-10 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h2 class="text-2xl font-bold text-white mb-2">🐾 PetCare</h2>
            <div class="text-sm text-gray-600 pt-6 border-t border-gray-800">
                &copy; 2025 PetCare. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        // Auto-hide flash messages after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                setTimeout(() => {
                    flashMessage.classList.add('animate-fade-out');
                    setTimeout(() => {
                        flashMessage.remove();
                    }, 500);
                }, 5000);
            }
        });
    </script>

</body>

</html>