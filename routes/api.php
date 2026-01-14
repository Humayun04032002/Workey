<?php
use App\Http\Controllers\Api\Auth\WorkerRegisterController; // You'll create this
use App\Http\Controllers\Api\Auth\LoginController;

Route::prefix('worker')->group(function () {
    Route::post('/register/step-1', [WorkerRegisterController::class, 'step1']);
    Route::post('/register/step-2', [WorkerRegisterController::class, 'step2']);
    Route::post('/register/step-3', [WorkerRegisterController::class, 'step3']);
    Route::post('/login', [LoginController::class, 'workerLogin']);
});