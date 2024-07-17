<?php

use Illuminate\Support\Facades\Route;

Route::get('/attendance', function () {
    return view('attendance');
});