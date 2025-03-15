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

// Request Routes
Route::post('/requests/store', [RequestController::class, 'store'])->name('requests.store');
Route::put('/staff/requests/{id}', [RequestController::class, 'update'])->name('staff.requests.update');

// Staff Dashboard
Route::middleware('auth:staff')->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::get('/staff/request/{unique_code}', [StaffController::class, 'showRequestDetails'])->name('staff.request.details');
    Route::get('/staff/main', [StaffController::class, 'index'])->name('staff.main');
});

// Manager Routes
Route::middleware(['auth:manager'])->group(function () {
    Route::get('/manager/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard');
    Route::get('/manager/request/{unique_code}', [ManagerController::class, 'show'])->name('manager.request.details');
    Route::post('/manager/request/approve/{unique_code}', [ManagerController::class, 'approve'])->name('manager.request.approve');
    Route::post('/manager/request/reject/{unique_code}', [ManagerController::class, 'reject'])->name('manager.request.reject');
    
    // ✅ Moved inside the manager group
    Route::post('/notifications/mark-as-read', [ManagerController::class, 'markNotificationsAsRead'])->name('notifications.mark-as-read');
    
    Route::get('/manager/request-list', [ManagerController::class, 'requestList'])->name('manager.request-list');
});

Route::middleware(['auth:finalmanager'])->get('/finalmanager/dashboard', function () {
    return view('finalmanager.finalmanager_main'); // Ensure the view file exists
})->name('finalmanager.dashboard');

// Authentication Routes
require __DIR__.'/auth.php';
