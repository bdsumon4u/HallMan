<?php

use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\StudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/students', StudentController::class);
Route::post('/attendance', AttendanceController::class);