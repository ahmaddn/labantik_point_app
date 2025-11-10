<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BKController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\KesiswaanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdminController;

// Auth Routes
Route::get('/', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Kesiswaan Routes
Route::prefix('kesiswaan')
    ->name('kesiswaan.')
    // ✅ tambahin middleware auth
    ->group(function () {
        Route::get('/dashboard', [KesiswaanController::class, 'index'])->name('dashboard');
        Route::get('/recaps', [KesiswaanController::class, 'recaps'])->name('recaps');

        Route::post('/store', [KesiswaanController::class, 'store'])->name('violations.store');
        Route::post('/violations/{student}', [KesiswaanController::class, 'store'])->name('violations.store.student');
    });


// BK Routes
Route::prefix('bk')->name('bk.')->group(function () {
    Route::get('/dashboard', [BKController::class, 'index'])->name('dashboard');
    Route::get('/student-violations/{studentId}', [BKController::class, 'getStudentViolations'])->name('student.violations');
    Route::get('/recaps', [BKController::class, 'recaps'])->name('recaps');
    Route::put('/violation-status/{id}', [BKController::class, 'updateViolationStatus'])->name('violation-status.update');
});

Route::prefix('superadmin')->middleware('auth')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::get('/-violations/{studentId}', [BKController::class, 'getStudentViolations'])->name('student.violations');
    // routes/web.php
    Route::post('/superadmin/store/{student}', [SuperAdminController::class, 'store'])
        ->name('violations.store');


    Route::get('/confirm-recaps', [SuperAdminController::class, 'confirmRecaps'])->name('confirm-recaps');
    Route::put('/violation-status/{id}',  [SuperAdminController::class, 'updateViolationStatus'])->name('violation-status.update');

    //Configs
    Route::get('/configs', [ConfigController::class, 'index'])->name('configs');
    Route::post('/configs/store', [ConfigController::class, 'store'])->name('configs.store');
    Route::put('/configs/update/{id}', [ConfigController::class, 'update'])->name('configs.update');
    Route::put('/configs/deactivate/{id}', [ConfigController::class, 'deactivate'])->name('configs.deactivate');
    Route::put('/configs/activate/{id}', [ConfigController::class, 'activate'])->name('configs.activate');
    Route::delete('/configs/destroy/{id}', [ConfigController::class, 'destroy'])->name('configs.destroy');
});


// Route::prefix('wakel')->name('wakel.')->group(function () {});
