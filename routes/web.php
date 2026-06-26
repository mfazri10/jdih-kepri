<?php

use App\Http\Controllers\FrontController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

// ─── Landing Page Routes ───
Route::get('/', [FrontController::class, 'index'])->name('home');
Route::get('/berita', [FrontController::class, 'berita'])->name('front.berita');
Route::get('/berita/{slug}', [FrontController::class, 'beritaShow'])->name('front.berita.show');
Route::get('/search', [FrontController::class, 'search'])->name('search.ajax');

Route::get('/anggota', [FrontController::class, 'anggota'])->name('front.anggota');
Route::get('/akd', [FrontController::class, 'akd'])->name('front.akd');
Route::get('/akd/{slug}', [FrontController::class, 'akdShow'])->name('front.akd.show');
Route::get('/fraksi', [FrontController::class, 'fraksi'])->name('front.fraksi');

Route::get('/agenda', [FrontController::class, 'agenda'])->name('front.agenda');
Route::get('/pengumuman', [FrontController::class, 'pengumuman'])->name('front.pengumuman');

Route::get('/aspirasi', [FrontController::class, 'aspirasi'])->name('front.aspirasi');
Route::post('/aspirasi', [FrontController::class, 'aspirasiStore'])->middleware('throttle:5,1')->name('front.aspirasi.store');

Route::get('/kunjungan', [FrontController::class, 'kunjungan'])->name('front.kunjungan');
Route::post('/kunjungan', [FrontController::class, 'kunjunganStore'])->middleware('throttle:5,1')->name('front.kunjungan.store');
Route::get('/api/regencies/{province_id}', [FrontController::class, 'getRegencies'])->name('api.regencies');

Route::get('/jdih', [FrontController::class, 'jdih'])->name('front.jdih');
Route::get('/jdih/{slug}', [FrontController::class, 'jdihShow'])->name('front.jdih.show');
Route::get('/jdih/download/{slug}', [FrontController::class, 'jdihDownload'])->name('front.jdih.download');

// Legacy route compatibility
Route::get('/post/{slug}', fn($slug) => redirect()->route('front.berita.show', $slug, 301));

Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

require __DIR__.'/auth.php';

Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));

// Halaman statis (catch-all, HARUS di paling bawah)
Route::get('/{slug}', [FrontController::class, 'page'])->name('front.page')->where('slug', '[a-z0-9\-]+');
