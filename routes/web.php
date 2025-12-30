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
    // Routes đặt lịch mới theo danh mục
    Route::get('/dat-lich/chon-danh-muc', [BookingController::class, 'selectCategory'])->name('booking.select-category');
    
    // Routes đặt lịch làm đẹp
    Route::get('/dat-lich/lam-dep', [BookingController::class, 'createBeauty'])->name('booking.beauty');
    Route::post('/dat-lich/lam-dep', [BookingController::class, 'storeBeauty'])->name('booking.beauty.store');
    
    // Routes đặt lịch y tế
    Route::get('/dat-lich/y-te', [BookingController::class, 'createMedical'])->name('booking.medical');
    Route::post('/dat-lich/y-te', [BookingController::class, 'storeMedical'])->name('booking.medical.store');
    
    // Routes đặt lịch trông giữ
    Route::get('/dat-lich/trong-giu', [BookingController::class, 'createPetCare'])->name('booking.pet-care');
    Route::post('/dat-lich/trong-giu', [BookingController::class, 'storePetCare'])->name('booking.pet-care.store');
    
    // API routes
    Route::get('/api/available-staff', [BookingController::class, 'getAvailableStaff'])->name('booking.available-staff');
    Route::get('/api/doctor-schedule', [BookingController::class, 'getDoctorSchedule'])->name('booking.doctor-schedule');
    
    // Routes cũ (giữ lại để tương thích ngược)
    Route::get('/dat-lich', [BookingController::class, 'selectCategory'])->name('booking.create');
    Route::post('/dat-lich', [BookingController::class, 'store'])->name('booking.store');

    // Lịch sử đặt lịch
    Route::get('/lich-su-dat', [BookingController::class, 'history'])->name('booking.history');
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
