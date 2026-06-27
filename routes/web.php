<?php

use App\Http\Controllers\FrontController;
use Illuminate\Support\Facades\Route;

// ─── JDIH Public Routes ─────────────────────────────────────────
Route::get('/', [FrontController::class, 'jdih'])->name('front.jdih');
Route::get('/cari', [FrontController::class, 'search'])->name('front.jdih.search');
Route::get('/cari/lanjutan', [FrontController::class, 'advancedSearch'])->name('front.jdih.advanced-search');
Route::get('/api/suggest', [FrontController::class, 'suggest'])->name('front.jdih.suggest');
Route::get('/tematik', [FrontController::class, 'themes'])->name('front.jdih.themes');
Route::get('/tematik/{slug}', [FrontController::class, 'themeShow'])->name('front.jdih.theme-show');
Route::get('/kategori/{slug}', [FrontController::class, 'categoryShow'])->name('front.jdih.category-show');
Route::get('/jenis/{code}', [FrontController::class, 'typeShow'])->name('front.jdih.type-show');
Route::get('/dokumen/{slug}', [FrontController::class, 'jdihShow'])->name('front.jdih.show');
Route::get('/dokumen/{slug}/download/{attachment}', [FrontController::class, 'jdihDownload'])->name('front.jdih.download');

// ─── Public Services (placeholder) ──────────────────────────────
Route::get('/konsultasi', [FrontController::class, 'consultations'])->name('front.jdih.consultations');
Route::get('/public-hearing', [FrontController::class, 'hearings'])->name('front.jdih.hearings');
Route::get('/permintaan-informasi', [FrontController::class, 'infoRequests'])->name('front.jdih.info-requests');

// ─── Legacy Routes ──────────────────────────────────────────────
Route::get('/berita', [FrontController::class, 'berita'])->name('front.berita');
Route::get('/berita/{slug}', [FrontController::class, 'beritaShow'])->name('front.berita.show');
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

// Sitemap
Route::get('/sitemap.xml', [\App\Http\Controllers\SitemapController::class, 'index'])->name('sitemap');

// Auth
require __DIR__.'/auth.php';

// Admin
Route::middleware('auth')
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));

// Static pages catch-all (HARUS di paling bawah)
Route::get('/{slug}', [FrontController::class, 'page'])->name('front.page')->where('slug', '[a-z0-9\-]+');
