<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {

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
    Route::get('/pollings/graphic', ['App\Http\Controllers\Admin\PollingController', 'graphic'])->name('polling.graphic');
    Route::get('/pollings/result', ['App\Http\Controllers\Admin\PollingController', 'result'])->name('polling.result');
    Route::post('/pollings/fetchSubdistrict', ['App\Http\Controllers\Admin\PollingController', 'fetchSubdistrict'])->name('polling.fetchSubdistrict');
    Route::post('/pollings/fetchVillage', ['App\Http\Controllers\Admin\PollingController', 'fetchVillage'])->name('polling.fetchVillage');
    Route::post('/pollings/fetchPollingStation', ['App\Http\Controllers\Admin\PollingController', 'fetchPollingStation'])->name('polling.fetchPollingStation');
    Route::post('/pollings/fetchCandidate', ['App\Http\Controllers\Admin\PollingController', 'fetchCandidate'])->name('polling.fetchCandidate');
    Route::post('/pollings/fetchPollingResult', ['App\Http\Controllers\Admin\PollingController', 'fetchPollingResult'])->name('polling.fetchPollingResult');
    Route::post('/pollings/fetchPollingGraphic', ['App\Http\Controllers\Admin\PollingController', 'fetchPollingGraphic'])->name('polling.fetchPollingGraphic');
    Route::post('/pollings/verify', ['App\Http\Controllers\Admin\PollingController', 'verify'])->name('polling.verify');
    Route::get('/pollings/export/excel', ['App\Http\Controllers\Admin\PollingController', 'exportExcel'])->name('polling.exportExcel');

    // User
    Route::resource('/user', App\Http\Controllers\Admin\UserController::class);

    // Role
    Route::resource('/role', App\Http\Controllers\Admin\RoleController::class);
});
