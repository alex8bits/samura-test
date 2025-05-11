<?php

use App\Http\Controllers\OzonController;
use App\Http\Controllers\PostingController;
use Illuminate\Support\Facades\Route;

/*Route::get('/', [OzonController::class, 'index']);
Route::get('/export', [OzonController::class, 'export'])->name('ozon.product.export');*/

Auth::routes([
    'register' => false, // Registration Routes...
    'reset' => false, // Password Reset Routes...
    'verify' => false, // Email Verification Routes...
]);

Route::get('', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'ozon'], function () {
        Route::get('postings', [PostingController::class, 'index'])->name('ozon.posting.index');
        Route::get('postings/export', [PostingController::class, 'export'])->name('ozon.posting.export');
    });
});
