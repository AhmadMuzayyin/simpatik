<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';

use App\Livewire\KategoriNilaiHarian;
use App\Livewire\Kelas;
use App\Livewire\Laporan;
use App\Livewire\Mapel;
use App\Livewire\Nilai;
use App\Livewire\Prediksi;
use App\Livewire\Preprocessing;
use App\Livewire\Siswa;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/kelas', Kelas\Index::class)->name('kelas.index');
    Route::get('/mapel', Mapel\Index::class)->name('mapel.index');
    Route::get('/siswa', Siswa\Index::class)->name('siswa.index');
    Route::get('/kategori-nilai-harian', KategoriNilaiHarian\Index::class)->name('kategori-nilai-harian.index');
    Route::get('/nilai', Nilai\Index::class)->name('nilai.index');
    Route::get('/preprocessing', Preprocessing\Index::class)->name('preprocessing.index');
    Route::get('/prediksi', Prediksi\Index::class)->name('prediksi.index');
    Route::get('/laporan', Laporan\Index::class)->name('laporan.index');
});
