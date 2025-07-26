<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KatalogController;


Route::get('/', [KatalogController::class, 'index']);

Route::get('/cek-resep', function () {
    return \App\Models\Produk::latest()->first(); // atau ->get() jika ingin banyak
});

Route::prefix('admin')->group(function () {
    Route::resource('produk', App\Http\Controllers\Admin\ProdukController::class);
});