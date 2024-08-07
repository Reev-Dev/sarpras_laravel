<?php

use App\Http\Controllers\DormPurchaseController;
use App\Http\Controllers\SchoolPurchaseController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('/sarpras', 'admin.sarpras')->name('sarpras');
    Route::view('/school-purchase', 'sekolah')->name('school-purchases.index');
    Route::view('/dorm-purchases', 'asrama')->name('dorm-purchases.index');
    Route::view('goodItems', 'goodItems')->name('good-items-school');
    Route::view('damagedItems', 'damagedItems')->name('damaged-items-school');
    Route::view('print', 'print')->name('print');
    Route::view('profile', 'profile')->name('profile');
});

// Routes for SchoolPurchase
// Route::resource('school-purchases', SchoolPurchaseController::class);

Route::controller(SchoolPurchaseController::class)->group(function () {
    Route::get('/school-purchase', 'index')->name('school-purchases.index');
    Route::get('/good-items-school', 'goodItems')->name('good-items-school');
    Route::get('/damaged-items-school', 'damagedItems')->name('damaged-items-school');

    Route::post('/school-purchases', 'store')->name('school-purchases.store');
    Route::get('/school-purchases/{id}/download', 'download')->name('school-purchases.download');

    Route::get('/school-purchases/create', 'create')->name('school-purchases.create');
    Route::get('/school-purchases/{id}/edit', 'edit')->name('school-purchases.edit');
    Route::put('/school-purchases/{id}', 'update')->name('school-purchases.update');
    Route::delete('/school-purchases/{id}', 'destroy')->name('school-purchases.destroy');
    Route::get('/school-purchases/print', 'print')->name('school-purchases.print');

    Route::get('/damaged-items-school/{$id}', 'getDamaged')->name('damaged-items-school.getDamaged');
    Route::get('/damaged-items-school/{id}/edit', 'edit')->name('damaged-items-school.edit');
    // Route::get('/items/{id}', 'show')->name('items.show');
    Route::put('/damaged-items-school/{id}', 'damaged')->name('damaged-items-school.damaged');
});

Route::controller(DormPurchaseController::class)->group(function () {
    Route::get('/dorm-purchase', 'index')->name('dorm-purchases.index');
    Route::get('/good-items-dorm', 'goodItems')->name('good-items-dorm');
    Route::get('/damaged-items-dorm', 'damagedItems')->name('damaged-items-dorm');

    Route::post('/dorm-purchases', 'store')->name('dorm-purchases.store');
    Route::get('/dorm-purchases/{id}/download', 'download')->name('dorm-purchases.download');

    Route::get('/dorm-purchases/create', 'create')->name('dorm-purchases.create');
    Route::get('/dorm-purchases/{id}/edit', 'edit')->name('dorm-purchases.edit');
    Route::put('/dorm-purchases/{id}', 'update')->name('dorm-purchases.update');
    Route::delete('/dorm-purchases/{id}', 'destroy')->name('dorm-purchases.destroy');
    Route::get('/dorm-purchases/print', 'print')->name('dorm-purchases.print');

    Route::get('/damaged-items-dorm/{$id}', 'getDamaged')->name('damaged-items-dorm.getDamaged');
    Route::get('/damaged-items-dorm/{id}/edit', 'edit')->name('damaged-items-dorm.edit');
    // Route::get('/items/{id}', 'show')->name('items.show');
    Route::put('/damaged-items-dorm/{id}', 'damaged')->name('damaged-items-dorm.damaged');
});

Route::get('zip-file', [SchoolPurchaseController::class, 'zip']);

require __DIR__ . '/auth.php';
