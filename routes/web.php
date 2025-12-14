<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceController; // Nhớ dòng này để gọi Controller

Route::get('/', function () {
    return view('welcome');
});
// Đường dẫn sẽ là: http://127.0.0.1:8000/services
Route::resource('services', ServiceController::class);