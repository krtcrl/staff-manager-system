<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FinalManagerController;



// Default Route: Redirect to Login Page
Route::get('/', function () {
    return redirect()->route('login');
});

// Login Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Attachment download routes
Route::get('/download/attachment/{filename}', [StaffController::class, 'downloadAttachment'])
     ->name('download.attachment')
     ->middleware('auth:staff');
Route::get('/download/final-attachment/{filename}', [StaffController::class, 'downloadFinalAttachment'])
     ->name('download.final_attachment')
     ->middleware('auth:staff');

// Request update route
Route::put('/staff/requests/{id}', [StaffController::class, 'update'])
     ->name('staff.requests.update')
     ->middleware('auth:staff');
// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Request Routes

Route::post('/requests/store', [RequestController::class, 'store'])->name('requests.store');
Route::put('/staff/requests/{id}', [RequestController::class, 'update'])->name('staff.requests.update');
Route::post('/requests/{requestId}/approve', [RequestController::class, 'approveRequest']);
Route::post('/convert-pdf-to-excel', [RequestController::class, 'convertPdfToExcel'])->name('convert.pdf.to.excel');
// Staff Dashboard
Route::middleware('auth:staff')->group(function () {
    Route::get('/staff/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/staff/create', [StaffController::class, 'create'])->name('staff.create');
    Route::get('/staff/request/{unique_code}', [StaffController::class, 'showRequestDetails'])->name('staff.request.details');
    Route::get('/staff/main', [StaffController::class, 'index'])->name('staff.main');
    Route::get('/staff/finallist', [StaffController::class, 'finalList'])->name('staff.finallist');
    Route::get('/staff/final/{unique_code}', [StaffController::class, 'showFinalDetails'])->name('staff.final.details');
    Route::get('/staff/request-history', [StaffController::class, 'requestHistory'])->name('staff.request.history');
});

// Manager Routes
Route::middleware(['auth:manager'])->group(function () {
    Route::get('/manager/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard');
    Route::get('/manager/request/{unique_code}', [ManagerController::class, 'show'])->name('manager.request.details');
    Route::post('/manager/request/reject/{unique_code}', [ManagerController::class, 'reject'])->name('manager.request.reject');
    
Route::get('/manager/final-dashboard', [ManagerController::class, 'finalDashboard'])->name('manager.final-dashboard');
Route::get('/manager/finalrequest-list', [ManagerController::class, 'finalRequestList'])->name('manager.finalrequest-list');
// Define the route for viewing final request details

Route::get('/manager/finalrequest/details/{unique_code}', [ManagerController::class, 'finalRequestDetails'])->name('manager.finalrequest.details');
// ✅ Moved inside the manager group
    Route::post('/notifications/mark-as-read', [ManagerController::class, 'markNotificationsAsRead'])->name('notifications.mark-as-read');
    Route::get('/manager/request-list', [ManagerController::class, 'requestList'])->name('manager.request-list');
    Route::post('/manager/request/approve/{uniqueCode}', [ManagerController::class, 'approve'])->name('manager.request.approve');

    // Manager attachment download routes
Route::get('/manager/download/attachment/{filename}', [ManagerController::class, 'downloadAttachment'])
->name('manager.download.attachment')
->middleware('auth:manager');

Route::get('/manager/download/final-attachment/{filename}', [ManagerController::class, 'downloadFinalAttachment'])
->name('manager.download.final_attachment')
->middleware('auth:manager');

});


// Final Request Routes
Route::get('/manager/finalrequests', [FinalManagerController::class, 'index'])->name('manager.finalrequests');
Route::get('/manager/finalrequests/{unique_code}', [FinalManagerController::class, 'finalRequestDetails'])->name('manager.finalrequest.details');
Route::post('/manager/finalrequests/{unique_code}/approve', [FinalManagerController::class, 'approveFinalRequest'])->name('manager.finalrequest.approve');
Route::post('/manager/finalrequests/{unique_code}/reject', [FinalManagerController::class, 'rejectFinalRequest'])->name('manager.finalrequest.reject');
// Authentication Routes
require __DIR__.'/auth.php';
