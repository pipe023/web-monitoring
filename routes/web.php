<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;

// Dashboard (Ping All)
Route::get('/', [WebsiteController::class, 'dashboard'])->name('dashboard');
Route::post('/websites/ping-all', [WebsiteController::class, 'pingAll'])->name('websites.pingAll');

// VAPT Routes (This is the one causing the error)
Route::get('/vapt-status', [WebsiteController::class, 'vapt'])->name('vapt.index');
Route::patch('/websites/{website}/vapt', [WebsiteController::class, 'updateVapt'])->name('websites.updateVapt');

// Archive Routes
Route::get('/archives', [WebsiteController::class, 'archives'])->name('archives.index');
Route::post('/websites/{website}/archive', [WebsiteController::class, 'archive'])->name('websites.archive');

// Standard CRUD for Websites
Route::resource('websites', WebsiteController::class);

// Secure Image Delivery Routes
Route::get('/media/system-logo', [WebsiteController::class, 'serveSystemLogo'])->name('media.systemLogo');
Route::get('/media/website/{website}', [WebsiteController::class, 'serveWebsiteLogo'])->name('media.websiteLogo');

// Auto-ping
Route::post('/websites/auto-ping', [WebsiteController::class, 'autoPing'])->name('websites.autoPing');