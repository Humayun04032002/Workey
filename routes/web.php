<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Worker\WorkerController;
use App\Http\Controllers\Employer\EmployerController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminAuthController;

// --- Public Routes ---
Route::get('/', function () {
    return view('welcome');
})->name('home');

// --- Admin Login ---
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
});

// --- Unified Auth Routes ---
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- Multi-Step Registration Flow ---
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showStep1')->name('register.step1');
    Route::post('/register', 'postStep1')->name('register.post1');
    Route::get('/register/worker-details/{id}', 'workerDetails')->name('register.worker');
    Route::get('/register/employer-details/{id}', 'employerDetails')->name('register.employer');
    Route::post('/register/employer-save', 'saveEmployerDetails')->name('register.employer.save');
    Route::post('/register/complete', 'completeRegistration')->name('register.complete');
});

// --- Protected Routes ---
Route::middleware(['auth'])->group(function () {
    
    // 1. Worker Side Routes
    Route::prefix('worker')->name('worker.')->controller(WorkerController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::get('/jobs', 'jobs')->name('jobs');
        Route::get('/job/{id}', 'show')->name('show');
        Route::post('/job/apply/{id}', 'applyJob')->name('apply');
        Route::get('/applied', 'applied')->name('applied');
        
        // --- Status & Payment Confirmation ---
        Route::post('/application/{id}/arrived', 'markAsArrived')->name('mark_arrived');
        Route::post('/application/{id}/payment-confirm', 'confirmPayment')->name('payment.confirm');

        // --- Verification ---
        Route::get('/verify', 'showVerifyPage')->name('verify');
        Route::post('/verify/submit', 'submitVerification')->name('verify.submit');

        // --- Wallet ---
        Route::get('/wallet', 'wallet')->name('wallet'); 
        Route::post('/wallet/deposit', 'deposit')->name('deposit');
        Route::get('/wallet/history', 'transactionHistory')->name('wallet.history');

        // --- Income & Receipts ---
        Route::get('/income-history', 'incomeHistory')->name('income.history');
        // সংশোধন: এখানে নাম শুধু 'income.receipt' হবে কারণ প্রিফিক্সে 'worker.' আছে
        Route::get('/income-receipt/{id}', 'showReceipt')->name('income.receipt');
        
        // --- Profile Management ---
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', 'profile')->name('index');      
            Route::get('/edit', 'editProfile')->name('edit'); 
            Route::put('/update', 'update')->name('update'); 
        });

        Route::get('/notifications', 'notifications')->name('notifications');
    });

    Route::get('/mark-as-read', [WorkerController::class, 'markRead'])->name('markRead');

    // 2. Employer Side Routes
    Route::prefix('employer')->name('employer.')->controller(EmployerController::class)->group(function () {
        Route::get('/home', 'index')->name('home');
        Route::get('/post-job', 'createJob')->name('job.create');
        Route::post('/post-job', 'storeJob')->name('job.store');
        Route::get('/my-jobs', 'myJobs')->name('job.list');
        Route::get('/applicants', 'applicants')->name('applicants');
        
        Route::get('/jobs/ongoing', 'ongoingJobs')->name('jobs.ongoing');
        Route::get('/jobs/history', 'jobHistory')->name('jobs.history');
        
        Route::get('/wallet', 'wallet')->name('wallet');
        Route::post('/deposit', 'storeDeposit')->name('deposit');

        Route::post('/application/{id}/status/{status}', 'updateStatus')->name('app.status');
        Route::post('/application/{id}/complete', 'completeJob')->name('app.complete');
        Route::get('/worker-profile/{id}', 'viewWorkerProfile')->name('worker.profile');
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/application/{id}/review', 'storeReview')->name('app.review');
        Route::get('/profile/edit', 'editProfile')->name('profile.edit');
        Route::put('/profile/update', 'updateProfile')->name('profile.update');
        Route::get('/notifications', 'notifications')->name('notifications.index');
    });

    // 3. Admin Panel Routes
    Route::middleware(['admin'])->prefix('admin')->name('admin.')->controller(AdminController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/users', 'users')->name('users');
        Route::get('/users/{id}', 'showUser')->name('user.details'); 
        Route::get('/user-profile/{id}', 'showUser')->name('users.show'); 
        Route::patch('/users/{id}/toggle-ban', 'toggleBan')->name('users.toggle-ban');
        Route::post('/user/{id}/status', 'updateUserStatus')->name('user.status.update');
        Route::get('/jobs', 'allJobs')->name('jobs');
        Route::post('/job/{id}/action', 'jobAction')->name('job.action');
        Route::get('/categories', 'categories')->name('categories');
        Route::get('/verifications', 'verifications')->name('verifications');
        Route::post('/verify/{id}/{status}', 'verifyUser')->name('verify.action');
        Route::get('/payments', 'payments')->name('payments');
        Route::post('/deposit-handle/{id}', 'handleDeposit')->name('deposit.handle');
        Route::post('/payment/{id}/update', 'updateTransactionStatus')->name('payment.update');
        Route::get('/revenue', 'revenue')->name('revenue');
        Route::get('/settings', 'settings')->name('settings');
        Route::post('/settings/update', 'updateSettings')->name('settings.update');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
    });
});