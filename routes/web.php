<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FinalManagerController;
use App\Http\Controllers\FinalRequestController;
use App\Http\Controllers\SuperAdminController; // Add this line
use Illuminate\Support\Facades\Auth;


// Default Route: Redirect to Login Page
Route::get('/', function () {
    return redirect()->route('login');
});

Route::prefix('superadmin')->group(function () {
    Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showSuperAdminLoginForm'])->name('superadmin.login');
    Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'superAdminLogin']);
    Route::post('/logout', [SuperAdminController::class, 'logout'])->name('superadmin.logout');

});
// Login Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
});

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ====================== Super Admin Routes ======================
// SuperAdmin Routes
Route::prefix('superadmin')->middleware('auth:superadmin')->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])
        ->name('superadmin.dashboard');


    // Staff routes
    Route::resource('staff', SuperAdminController::class)->except(['show']); // Adjusted the route prefix here
    Route::get('/staff/table', [SuperAdminController::class, 'staffTable'])->name('superadmin.staff.table');
    Route::delete('/staff/{staff}', [SuperAdminController::class, 'destroy'])->name('superadmin.staff.destroy');
    Route::put('/staff/{staff}', [SuperAdminController::class, 'update'])->name('superadmin.staff.update');


     // Manager routes
     Route::resource('manager', SuperAdminController::class)->except(['show']);
     Route::get('/manager/table', [SuperAdminController::class, 'managerTable'])->name('superadmin.manager.table');
     Route::delete('/manager/{manager}', [SuperAdminController::class, 'destroyManager'])->name('superadmin.manager.destroy');
     Route::put('/manager/{manager}', [SuperAdminController::class, 'updateManager'])->name('superadmin.manager.update');

     // Parts routes
     Route::resource('parts', SuperAdminController::class)->except(['show']); // Adjusted the route prefix here
     Route::get('/parts/table', [SuperAdminController::class, 'partsTable'])->name('superadmin.parts.table');
     Route::delete('/parts/{parts}', [SuperAdminController::class, 'destroyPart'])->name('superadmin.parts.destroy');
     Route::put('/parts/{parts}', [SuperAdminController::class, 'updatePart'])->name('superadmin.parts.update');

// Part Process routes
Route::resource('partprocess', SuperAdminController::class)->except(['show']);
Route::get('/partprocess/table', [SuperAdminController::class, 'partProcessTable'])->name('superadmin.partprocess.table');
Route::delete('/partprocess/{partprocess}', [SuperAdminController::class, 'destroyPartProcess'])->name('superadmin.partprocess.destroy');
Route::put('/partprocess/{partprocess}', [SuperAdminController::class, 'updatePartProcess'])->name('superadmin.partprocess.update');

// Request routes
Route::resource('request', SuperAdminController::class)->except(['show']);
Route::get('/request/table', [SuperAdminController::class, 'requestTable'])->name('superadmin.request.table');
Route::delete('/request/{request}', [SuperAdminController::class, 'destroyRequest'])->name('superadmin.request.destroy');
Route::put('/request/{request}', [SuperAdminController::class, 'updateRequest'])->name('superadmin.request.update');


// Final Request routes
Route::resource('finalrequest', SuperAdminController::class)->except(['show']);
Route::get('/finalrequest/table', [SuperAdminController::class, 'finalRequestTable'])->name('superadmin.finalrequest.table');
Route::delete('/finalrequest/{finalrequest}', [SuperAdminController::class, 'destroyFinalRequest'])->name('superadmin.finalrequest.destroy');
Route::put('/finalrequest/{finalrequest}', [SuperAdminController::class, 'updateFinalRequest'])->name('superadmin.finalrequest.update');


// Request History routes
Route::resource('requesthistory', SuperAdminController::class)->except(['show']);
Route::get('/requesthistory/table', [SuperAdminController::class, 'requestHistoryTable'])->name('superadmin.requesthistory.table');
Route::delete('/requesthistory/{requesthistory}', [SuperAdminController::class, 'destroyRequestHistory'])->name('superadmin.requesthistory.destroy');
Route::put('/requesthistory/{requesthistory}', [SuperAdminController::class, 'updateRequestHistory'])->name('superadmin.requesthistory.update');

});

// ====================== Staff Routes ======================
Route::prefix('staff')->middleware(['auth:staff'])->group(function () {
    Route::get('/dashboard', [StaffController::class, 'index'])->name('staff.dashboard');
    Route::get('/create', [StaffController::class, 'create'])->name('staff.create');
    Route::get('/request/{unique_code}', [StaffController::class, 'showRequestDetails'])->name('staff.request.details');
    Route::get('/main', [StaffController::class, 'index'])->name('staff.main');
    Route::get('/finallist', [StaffController::class, 'finalList'])->name('staff.finallist');
    Route::get('/final/{unique_code}', [StaffController::class, 'showFinalDetails'])->name('staff.final.details');
    Route::get('/request-history', [StaffController::class, 'requestHistory'])->name('staff.request.history');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'staffIndex'])
         ->name('staff.notifications');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'staffMarkAsRead'])
         ->name('staff.notifications.mark-as-read');
         
    // Request handling
    Route::put('/requests/{id}', [StaffController::class, 'update'])
         ->name('staff.requests.update');
});

// Attachment download routes for staff
Route::get('/download/attachment/{filename}', [StaffController::class, 'downloadAttachment'])
     ->name('download.attachment')
     ->middleware('auth:staff');
Route::get('/download/final-attachment/{filename}', [StaffController::class, 'downloadFinalAttachment'])
     ->name('download.final_attachment')
     ->middleware('auth:staff');

// ====================== Manager Routes ======================
Route::prefix('manager')->middleware(['auth:manager'])->group(function () {
    Route::get('/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard');
    Route::get('/request/{unique_code}', [ManagerController::class, 'show'])->name('manager.request.details');
    Route::post('/request/reject/{unique_code}', [ManagerController::class, 'reject'])->name('manager.request.reject');
    
    Route::get('/final-dashboard', [ManagerController::class, 'finalDashboard'])->name('manager.final-dashboard');
    Route::get('/finalrequest-list', [ManagerController::class, 'finalRequestList'])->name('manager.finalrequest-list');
    Route::get('/finalrequest/details/{unique_code}', [ManagerController::class, 'finalRequestDetails'])->name('manager.finalrequest.details');
    

    Route::post('/notifications/mark-as-read', [ManagerController::class, 'markNotificationsAsRead'])
         ->name('notifications.mark-as-read');
    
    Route::get('/request-list', [ManagerController::class, 'requestList'])->name('manager.request-list');
    Route::post('/request/approve/{uniqueCode}', [ManagerController::class, 'approve'])->name('manager.request.approve');

    // Attachment downloads
    Route::get('/download/attachment/{filename}', [ManagerController::class, 'downloadAttachment'])
        ->name('manager.download.attachment');
    Route::get('/download/final-attachment/{filename}', [ManagerController::class, 'downloadFinalAttachment'])
        ->name('manager.download.final_attachment');
});
// ====================== Notifications Routes ======================
// Notifications
Route::post('/manager/notifications/mark-as-read', [NotificationController::class, 'managerMarkAsRead'])
    ->middleware('auth:manager')
    ->name('manager.notifications.mark-as-read');

Route::get('/notifications', [NotificationController::class, 'managerIndex'])
    ->name('manager.notifications');
    Route::post('/staff/notifications/mark-as-read', [NotificationController::class, 'staffMarkAsRead']);
// ====================== Request Routes ======================
Route::post('/requests/store', [RequestController::class, 'store'])->name('requests.store');
Route::put('/staff/requests/{id}', [RequestController::class, 'update'])->name('staff.requests.update');
Route::post('/requests/{requestId}/approve', [RequestController::class, 'approveRequest']);
Route::post('/convert-pdf-to-excel', [RequestController::class, 'convertPdfToExcel'])->name('convert.pdf.to.excel');


Route::put('/final-requests/{finalRequest}', [FinalRequestController::class, 'update'])->name('staff.finalRequests.update');

// ====================== Final Request Routes ======================
Route::get('/manager/finalrequests', [FinalManagerController::class, 'index'])->name('manager.finalrequests');
Route::get('/manager/finalrequests/{unique_code}', [FinalManagerController::class, 'finalRequestDetails'])->name('manager.finalrequest.details');
Route::post('/manager/finalrequests/{unique_code}/approve', [FinalManagerController::class, 'approveFinalRequest'])->name('manager.finalrequest.approve');
Route::post('/manager/finalrequest/reject/{unique_code}', [FinalManagerController::class, 'rejectFinalRequest'])
    ->name('manager.finalrequest.reject');
Route::put('/staff/finalrequests/{id}', [FinalRequestController::class, 'update'])
    ->name('staff.finalrequests.update');
    
// Authentication Routes
require __DIR__.'/auth.php';