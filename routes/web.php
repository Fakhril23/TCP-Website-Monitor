<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\SkpdController;
use App\Http\Controllers\TestingController;

Route::get('/', function () {
    return view('welcome');
});
// Website

Route::post('/websites/import', [WebsiteController::class, 'import'])
    ->name('websites.import');

Route::resource('websites', WebsiteController::class);
Route::resource('websites', WebsiteController::class);

// SKPD
Route::resource('skpds', SkpdController::class);

// Testing
Route::resource('testings', TestingController::class);

Route::get('/testing', [TestingController::class, 'index']);
Route::post('/testing/check', [TestingController::class, 'check']);
Route::post('/testing/save', [TestingController::class, 'save']);
Route::get('/testings', [TestingController::class, 'index'])
    ->name('testings.index');
Route::post('/testings/check', [TestingController::class, 'check'])
    ->name('testings.check');
Route::post('/testings/save', [TestingController::class, 'save'])
    ->name('testings.save');
