<?php

use Illuminate\Support\Facades\Route;

Route::get('/', App\Livewire\Home::class);
Route::get('/{slug}', App\Livewire\Detail::class)->name('detail');
Route::get('/daftar/{slug}', App\Livewire\Daftar::class)->name('daftar');
