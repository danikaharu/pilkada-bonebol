<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'auth'], function () {

    // Dashboard
    Route::get('/dashboard', ['App\Http\Controllers\Admin\DashboardController', 'index'])->name('dashboard');

    // Electoral District
    Route::resource('/electoraldistrict', App\Http\Controllers\Admin\ElectoralDistrictController::class);

    // Subdistrict
    Route::resource('/subdistrict', App\Http\Controllers\Admin\SubdistrictController::class);

    // Village
    Route::resource('/village', App\Http\Controllers\Admin\VillageController::class);

    // Polling Station
    Route::resource('/pollingstation', App\Http\Controllers\Admin\PollingStationController::class);

    // Candidate
    Route::resource('/candidate', App\Http\Controllers\Admin\CandidateController::class);

    // Polling 
    Route::resource('/polling', App\Http\Controllers\Admin\PollingController::class);
    Route::post('/pollings/fetchSubdistrict', ['App\Http\Controllers\Admin\PollingController', 'fetchSubdistrict'])->name('polling.fetchSubdistrict');
    Route::post('/pollings/fetchVillage', ['App\Http\Controllers\Admin\PollingController', 'fetchVillage'])->name('polling.fetchVillage');
    Route::post('/pollings/fetchPollingStation', ['App\Http\Controllers\Admin\PollingController', 'fetchPollingStation'])->name('polling.fetchPollingStation');
    Route::post('/pollings/fetchCandidate', ['App\Http\Controllers\Admin\PollingController', 'fetchCandidate'])->name('polling.fetchCandidate');
    Route::post('/pollings/fetchPollingResult', ['App\Http\Controllers\Admin\PollingController', 'fetchPollingResult'])->name('polling.fetchPollingResult');
    Route::post('/pollings/fetchPollingGraphic', ['App\Http\Controllers\Admin\PollingController', 'fetchPollingGraphic'])->name('polling.fetchPollingGraphic');
    Route::post('/pollings/verify/{polling}', ['App\Http\Controllers\Admin\PollingController', 'verify'])->name('polling.verify');
    Route::get('/pollings/export/excel', ['App\Http\Controllers\Admin\PollingController', 'exportExcel'])->name('polling.exportExcel');

    // Polling Result
    Route::get('/pollings/result/pollingstation', ['App\Http\Controllers\Admin\PollingController', 'result'])->name('polling.result');
    Route::get('/pollings/result/all', ['App\Http\Controllers\Admin\PollingController', 'resultAll'])->name('polling.resultAll');
    Route::get('/pollings/result/village', ['App\Http\Controllers\Admin\PollingController', 'resultVillage'])->name('polling.resultVillage');
    Route::get('/pollings/result/subdistrict', ['App\Http\Controllers\Admin\PollingController', 'resultSubdistrict'])->name('polling.resultSubdistrict');
    Route::get('/pollings/result/electoraldistrict', ['App\Http\Controllers\Admin\PollingController', 'resultElectoraldistrict'])->name('polling.resultElectoraldistrict');

    // Polling Graphic
    Route::get('/pollings/graphic/all', ['App\Http\Controllers\Admin\PollingController', 'graphicAll'])->name('polling.graphicAll');
    Route::get('/pollings/graphic/electoraldistrict', ['App\Http\Controllers\Admin\PollingController', 'graphicElectoraldistrict'])->name('polling.graphicElectoralDistrict');
    Route::get('/pollings/graphic/subdistrict', ['App\Http\Controllers\Admin\PollingController', 'graphicSubdistrict'])->name('polling.graphicSubdistrict');
    Route::get('/pollings/graphic/village', ['App\Http\Controllers\Admin\PollingController', 'graphicVillage'])->name('polling.graphicVillage');
    Route::get('/pollings/graphic/pollingstation', ['App\Http\Controllers\Admin\PollingController', 'graphic'])->name('polling.graphic');

    // User
    Route::resource('/user', App\Http\Controllers\Admin\UserController::class);

    // Role
    Route::resource('/role', App\Http\Controllers\Admin\RoleController::class);

    // Profile 
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('profile');
});
