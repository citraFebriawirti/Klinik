<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\PasienComponent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/poli', function () {
    return view('poli.index');
})->name('poli');


Route::get('/obat', function () {
    return view('obat.index');
})->name('obat');



Route::get('/pasien', function () {
    return view('pasien.index');
})->name('pasien');

Route::get('/dokter', function () {
    return view('dokter.index');
})->name('dokter');

Route::get('/janjitemu', function () {
    return view('janji-temu.index');
})->name('janjitemu');

Route::get('/buku', function () {
    return view('buku.index');
})->name('buku');

Route::get('/pendaftaran', function () {
    return view('pendaftaran-pasien.index');
})->name('pendaftaran');

Route::get('/pemeriksaan', function () {
    return view('pemeriksaan-pasien.index');
})->name('pemeriksaan');