<?php

use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\ContactClickController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PricingPlanController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Frontend\ContactClickController as FrontendContactClickController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ServiceController as FrontendServiceController;
use App\Http\Controllers\LineWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/services/{service:slug}', [FrontendServiceController::class, 'show'])->name('services.show');
Route::post('/contact-clicks', [FrontendContactClickController::class, 'store'])
    ->middleware('throttle:contact-clicks')
    ->name('contact-clicks.store');
Route::post('/line/webhook', LineWebhookController::class)->name('line.webhook');

Route::middleware('guest')->group(function (): void {
    Route::get('/admin/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/admin/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
});

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('contact-clicks', [ContactClickController::class, 'index'])->name('contact-clicks.index');

    Route::resource('services', ServiceController::class)->except(['show']);
    Route::resource('pricing-plans', PricingPlanController::class)->except(['show']);
});
