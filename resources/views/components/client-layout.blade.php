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

</body>

</html>