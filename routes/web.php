<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;

Route::get('/', function () {
    return view('welcome');
});
Route::prefix('pets')->group(function () {
    require __DIR__ . '/petRoute.php';
});