<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BKController;
use App\Http\Controllers\ConfigController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ViolationController;

// Auth Routes
Route::get('/', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// guru Routes
Route::prefix('guru')
    ->name('guru.')
    // ✅ tambahin middleware auth
    ->group(function () {
        Route::get('/dashboard', [GuruController::class, 'index'])->name('dashboard');
        Route::get('/recaps', [GuruController::class, 'recaps'])->name('recaps');
        Route::get('/recaps/{id}/detail', [GuruController::class, 'detailRecaps'])->name('recaps.detail');
        Route::get('/student-data', [GuruController::class, 'studentData'])->name('student-data');


        Route::post('/store', [GuruController::class, 'store'])->name('violations.store');
        Route::post('/violations/{student}', [GuruController::class, 'store'])->name('violations.store.student');
    });


// BK Routes
Route::prefix('kesiswaan-bk')->name('kesiswaan-bk.')->group(function () {
    Route::get('/dashboard', [BKController::class, 'index'])->name('dashboard');
    Route::get('/student-data', [BKController::class, 'studentData'])->name('student-data');
    Route::post('/violations/store/{student}', [BKController::class, 'store'])->name('violations.store');
    Route::get('/student-violations/{studentId}', [BKController::class, 'getStudentViolations'])->name('student.violations');
    Route::get('/recaps', [BKController::class, 'recaps'])->name('recaps');
    Route::get('/recaps/{id}/detail', [BKController::class, 'detailRecaps'])->name('recaps.detail');
    Route::post('/recaps/{id}/action', [BKController::class, 'storeHandlingAction'])->name('actionConfirm-Recaps');
    Route::put('/violation-status/{id}', [BKController::class, 'updateViolationStatus'])->name('violation-status.update');
});

Route::prefix('superadmin')->middleware('auth')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'index'])->name('dashboard');
    Route::get('/student-data', [SuperAdminController::class, 'studentData'])->name('student-data');
    Route::get('/-violations/{studentId}', [BKController::class, 'getStudentViolations'])->name('student.violations');
    // routes/web.php
    Route::post('/superadmin/store/{student}', [SuperAdminController::class, 'store'])
        ->name('violations.store');

    //Violations
    Route::get('/violations', [ViolationController::class, 'index'])
        ->name('violations');
    Route::post('/violations/add', [ViolationController::class, 'add'])
        ->name('violations.add');
    Route::put('/violations/{id}/update', [ViolationController::class, 'update'])
        ->name('violations.update');
    Route::delete('/violations/{id}/destroy', [ViolationController::class, 'destroy'])
        ->name('violations.destroy');


    Route::get('/confirm-recaps', [SuperAdminController::class, 'confirmRecaps'])->name('confirm-recaps');
    Route::get('/confirm-recaps/{studentAcademicYearId}/detail', [SuperAdminController::class, 'detailConfirmRecaps'])->name('detailConfirm-Recaps');
    Route::post('/confirm-recaps/{id}/action', [SuperAdminController::class, 'storeHandlingAction'])->name('actionConfirm-Recaps');
    Route::delete('/recaps/{id}/delete', [SuperAdminController::class, 'destroyRecap'])->name('recaps.destroy');
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
