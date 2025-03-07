<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\NotificationController;






// Default Route: Redirect to Login Page
Route::get('/', function () {
    return redirect()->route('login');
});

// Login Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::post('/requests/store', [RequestController::class, 'store'])->name('requests.store');


// Staff Dashboard
Route::middleware('auth:staff')->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard'); // Load from controller

    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
        Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/staff/request/{unique_code}', [StaffController::class, 'showRequestDetails'])->name('staff.request.details');
    

});


Route::middleware(['auth:manager'])->group(function () {
    Route::get('/manager/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard'); // Load from controller
    Route::get('/manager/request/{unique_code}', [ManagerController::class, 'show'])->name('manager.request.details');
    Route::post('/manager/request/approve/{unique_code}', [ManagerController::class, 'approve'])->name('manager.request.approve');
    Route::post('/manager/request/reject/{unique_code}', [ManagerController::class, 'reject'])->name('manager.request.reject');});

    Route::post('/notifications/mark-as-read', [ManagerController::class, 'markNotificationsAsRead'])->name('notifications.mark-as-read');


    

require __DIR__.'/auth.php'; // Make sure this line exists