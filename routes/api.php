<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminServiceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\EmployeeRoleController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| RESTful API cho Pet Spa Booking
| Base URL: /api
|
*/

// ============================================
// PUBLIC ROUTES (Không cần authentication)
// ============================================

// Auth API
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Google Auth API
Route::get('/auth/google/url', [GoogleAuthController::class, 'apiGetRedirectUrl']);
Route::post('/auth/google/callback', [GoogleAuthController::class, 'apiHandleCallback']);

// Home / Public Data
Route::get('/home', [HomeController::class, 'apiIndex']);
Route::get('/home/search', [HomeController::class, 'apiSearch']);

// Services (public)
Route::get('/services', [ServiceController::class, 'apiIndex']);
Route::get('/services/{id}', [ServiceController::class, 'apiShow']);
Route::get('/service-categories', [ServiceController::class, 'apiCategories']);

// ============================================
// PROTECTED ROUTES (Cần authentication)
// ============================================

Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/user', [AuthController::class, 'user']);
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'apiShow']);
    Route::put('/profile', [ProfileController::class, 'apiUpdate']);
    Route::put('/profile/password', [ProfileController::class, 'apiUpdatePassword']);
    Route::delete('/profile', [ProfileController::class, 'apiDestroy']);
    
    // Services - Calculate Price
    Route::post('/services/calculate-price', [ServiceController::class, 'calculatePrice']);
    
    // Pets (CRUD)
    Route::get('/pets', [PetController::class, 'apiIndex']);
    Route::post('/pets', [PetController::class, 'apiStore']);
    Route::get('/pets/{id}', [PetController::class, 'apiShow']);
    Route::put('/pets/{id}', [PetController::class, 'apiUpdate']);
    Route::delete('/pets/{id}', [PetController::class, 'apiDestroy']);
    
    // Bookings (CRUD)
    Route::get('/bookings', [BookingController::class, 'apiIndex']);
    Route::post('/bookings', [BookingController::class, 'apiStore']);
    Route::get('/bookings/{id}', [BookingController::class, 'apiShow']);
    Route::put('/bookings/{id}', [BookingController::class, 'apiUpdate']);
    Route::delete('/bookings/{id}', [BookingController::class, 'apiDestroy']);
    
    // Booking Utilities
    Route::get('/bookings-available-staff', [BookingController::class, 'getAvailableStaff']);
    Route::get('/bookings-doctor-schedule', [BookingController::class, 'getDoctorSchedule']);
    
    // ============================================
    // ADMIN ROUTES (Cần authentication + admin role)
    // ============================================
    
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'apiDashboard']);
        Route::get('/dashboard/revenue', [AdminController::class, 'apiRevenue']);
        
        // Services Management
        Route::get('/services', [AdminServiceController::class, 'apiIndex']);
        Route::post('/services', [AdminServiceController::class, 'apiStore']);
        Route::get('/services/{id}', [AdminServiceController::class, 'apiShow']);
        Route::put('/services/{id}', [AdminServiceController::class, 'apiUpdate']);
        Route::delete('/services/{id}', [AdminServiceController::class, 'apiDestroy']);
        
        // Employees Management
        Route::get('/employees', [EmployeeController::class, 'apiIndex']);
        Route::post('/employees', [EmployeeController::class, 'apiStore']);
        Route::get('/employees/{id}', [EmployeeController::class, 'apiShow']);
        Route::put('/employees/{id}', [EmployeeController::class, 'apiUpdate']);
        Route::delete('/employees/{id}', [EmployeeController::class, 'apiDestroy']);
        Route::post('/employees/{id}/schedules', [EmployeeController::class, 'apiStoreSchedule']);
        Route::put('/employees/{id}/schedules/{scheduleId}', [EmployeeController::class, 'apiUpdateSchedule']);
        Route::delete('/employees/{id}/schedules/{scheduleId}', [EmployeeController::class, 'apiDestroySchedule']);
        
        // Appointments Management
        Route::get('/appointments', [AdminController::class, 'apiAppointments']);
        Route::get('/appointments/{id}', [AdminController::class, 'apiAppointmentShow']);
        Route::patch('/appointments/{id}/status', [AdminController::class, 'apiUpdateStatus']);
        Route::delete('/appointments/{id}', [AdminController::class, 'apiDeleteAppointment']);
        
        // Users Management
        Route::get('/users', [UserController::class, 'apiIndex']);
        Route::get('/users/{id}', [UserController::class, 'apiShow']);
        Route::delete('/users/{id}', [UserController::class, 'apiDestroy']);
        
        // Employee Roles Management
        Route::get('/roles', [EmployeeRoleController::class, 'apiIndex']);
        Route::post('/roles', [EmployeeRoleController::class, 'apiStore']);
        Route::get('/roles/{id}', [EmployeeRoleController::class, 'apiShow']);
        Route::put('/roles/{id}', [EmployeeRoleController::class, 'apiUpdate']);
        Route::delete('/roles/{id}', [EmployeeRoleController::class, 'apiDestroy']);
    });
});
