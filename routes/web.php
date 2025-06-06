<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\Admin\RoomController;


Route::get('/', function () {
    return view('welcome');
});

Route::resource('barangs', BarangController::class);

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('rooms', RoomController::class);
});