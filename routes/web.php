<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrukController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/struk/{id}', [StrukController::class, 'cetak'])->name('struk.cetak');
