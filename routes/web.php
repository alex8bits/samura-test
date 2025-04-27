<?php

use App\Http\Controllers\OzonController;
use Illuminate\Support\Facades\Route;

Route::get('/', [OzonController::class, 'index']);
Route::get('/export', [OzonController::class, 'export'])->name('ozon.product.export');
