<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController; // Nhớ dòng này

// Ở đây chỉ cần định nghĩa đường dẫn gốc '/', vì bên web.php ta sẽ gom nhóm nó vào 'pets'
Route::get('/', [PetController::class, 'index']);       // GET: /pets
Route::get('/create', [PetController::class, 'create']);    // GET: /pets/create
Route::post('/', [PetController::class, 'store']);      // POST: /pets
Route::get('/{id}', [PetController::class, 'show']);    // GET: /pets/1
Route::put('/{id}', [PetController::class, 'update']);  // PUT: /pets/1
Route::delete('/{id}', [PetController::class, 'destroy']); // DELETE: /pets/1