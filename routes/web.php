<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

use App\Livewire\Kelas;
use App\Livewire\Mapel;
use App\Livewire\Siswa;
use App\Livewire\Nilai;
use App\Livewire\Preprocessing;
use App\Livewire\Prediksi;
use App\Livewire\Laporan;
use App\Livewire\Setting;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/kelas', Kelas\Index::class)->name('kelas.index');
    Route::get('/mapel', Mapel\Index::class)->name('mapel.index');
    Route::get('/siswa', Siswa\Index::class)->name('siswa.index');
    Route::get('/nilai', Nilai\Index::class)->name('nilai.index');
    Route::get('/preprocessing', Preprocessing\Index::class)->name('preprocessing.index');
    Route::get('/prediksi', Prediksi\Index::class)->name('prediksi.index');
    Route::get('/laporan', Laporan\Index::class)->name('laporan.index');
});
