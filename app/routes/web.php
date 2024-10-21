<?php

use Illuminate\Support\Facades\Route;

Route::get('/', App\Livewire\Home::class)->name('home');
Route::get('/{slug}', App\Livewire\Detail::class)->name('detail');
Route::get('/daftar/{slug}', App\Livewire\Daftar::class)->name('daftar');
Route::get('/create-symlink', function (){
    symlink(storage_path('/app/public'), public_path('storage'));
    echo "Symlink Created. Thanks";
});