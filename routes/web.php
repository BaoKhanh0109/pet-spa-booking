<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminServiceController;

// 1. Trang chủ (Không cần đăng nhập cũng xem được)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dich-vu', [ServiceController::class, 'index'])->name('client.services');
// 2. Nhóm các trang CẦN ĐĂNG NHẬP
Route::middleware(['auth'])->group(function () {
    // ... các route cũ (pets, booking create/store) ...
    Route::get('/dat-lich', [BookingController::class, 'create'])->name('booking.create');

    // 2. Route xử lý lưu lịch hẹn khi bấm nút Gửi
    Route::post('/dat-lich', [BookingController::class, 'store'])->name('booking.store');

    // 3. Route xem lịch sử (như bài trước đã làm)
    Route::get('/lich-su-dat', [BookingController::class, 'history'])->name('booking.history');
    // Thêm route Lịch sử
    Route::get('/booking/history', [BookingController::class, 'history'])->name('booking.history');
});
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.') 
    ->group(function () {
    
    Route::resource('services', App\Http\Controllers\AdminServiceController::class);

    Route::post('/services/store', [App\Http\Controllers\AdminServiceController::class, 'store'])->name('services.store');

    Route::get('/appointments', [App\Http\Controllers\AdminController::class, 'indexAppointments'])
        ->name('appointments.index');

    Route::get('/appointments/{id}/{status}', [App\Http\Controllers\AdminController::class, 'updateStatus'])
        ->name('appointments.status'); 
});


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('/services', ServiceController::class);


Route::middleware(['auth'])->group(function () {
    Route::get('/my-pets', [PetController::class, 'index'])->name('pets.index');
    Route::get('/my-pets/create', [PetController::class, 'create'])->name('pets.create');
    Route::post('/my-pets', [PetController::class, 'store'])->name('pets.store');
    
    // Route sửa và xóa (cần truyền ID)
    Route::get('/my-pets/{id}/edit', [PetController::class, 'edit'])->name('pets.edit');
    Route::put('/my-pets/{id}', [PetController::class, 'update'])->name('pets.update');
    Route::delete('/my-pets/{id}', [PetController::class, 'destroy'])->name('pets.destroy');
});
require __DIR__.'/auth.php';
