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

Route::middleware('guest')->group(function () {
    // Forgot Password
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');
    
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('throttle:3,1')
        ->name('password.email');
    
    // Reset Password
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');
    
    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->name('password.update');
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

        Route::post('/superadmin/staff', [SuperAdminController::class, 'storeStaff'])->name('superadmin.staff.store');
        Route::post('/superadmin/manager', [SuperAdminController::class, 'storeManager'])->name('superadmin.manager.store');
        Route::post('/superadmin/parts', [SuperadminController::class, 'storePart'])->name('superadmin.parts.store');
        Route::post('/superadmin/partprocess', [SuperadminController::class, 'storePartProcess'])->name('superadmin.partprocess.store');


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
     Route::delete('/parts/{part}', [SuperAdminController::class, 'destroyPart'])->name('superadmin.parts.destroy');
     Route::put('/parts/{part}', [SuperAdminController::class, 'updatePart'])->name('superadmin.parts.update');

// Part Process routes
Route::resource('partprocess', SuperAdminController::class)->except(['show']);
Route::get('/partprocess/table', [SuperAdminController::class, 'partProcessTable'])->name('superadmin.partprocess.table');
Route::delete('/partprocess/{partprocess}', [SuperAdminController::class, 'destroyPartProcess'])->name('superadmin.partprocess.destroy');
Route::put('/partprocess/{partprocess}', [SuperAdminController::class, 'updatePartProcess'])->name('superadmin.partprocess.update');

// Request routes
Route::resource('/request', SuperAdminController::class)->except(['show']);
Route::get('/request/table', [SuperAdminController::class, 'requestTable'])->name('superadmin.request.table');
//Route::delete('/request/{request}', [SuperAdminController::class, 'destroyRequest'])->name('superadmin.request.destroy');
//Route::put('/request/{request}', [SuperAdminController::class, 'updateRequest'])->name('superadmin.request.update');
Route::delete('/{request}', [SuperAdminController::class, 'destroyRequest'])->name('superadmin.request.destroy');
Route::put('/{request}', [SuperAdminController::class, 'updateRequest'])->name('superadmin.request.update');

// Keep the resource route for other CRUD operations if needed
Route::resource('/', SuperAdminController::class)->except(['show', 'destroy', 'update'])
    ->names([
        'index' => 'superadmin.request.index',
        'create' => 'superadmin.request.create',
        'store' => 'superadmin.request.store',
        'edit' => 'superadmin.request.edit',
    ]);


// Final Request routes
Route::resource('finalrequest', SuperAdminController::class)->except(['show']);
Route::get('/finalrequest/table', [SuperAdminController::class, 'finalRequestTable'])->name('superadmin.finalrequest.table');
    Route::delete('/finalrequest/{finalrequest}', [SuperAdminController::class, 'destroyFinalRequest'])->name('superadmin.finalrequest.destroy');
    Route::put('/finalrequest/{finalrequest}', [SuperAdminController::class, 'updateFinalRequest'])->name('superadmin.finalrequest.update');


// Request History routes
Route::resource('requesthistory', SuperAdminController::class)->except(['show']);
Route::get('/requesthistory/table', [SuperAdminController::class, 'requestHistoryTable'])->name('superadmin.requesthistory.table');
Route::delete('/requesthistory/{requesthistory}', [SuperAdminController::class, 'destroyRequestHistory'])->name('superadmin.requesthistory.destroy');

});

// ====================== Staff Routes ======================
Route::prefix('staff')->middleware(['auth:staff'])->group(function () {
    Route::get('/staff/page', [StaffController::class, 'index'])->name('staff.page'); // Now points to the statistics page (index method)

    Route::get('/dashboard', [StaffController::class, 'index'])->name('staff.dashboard'); // Now points to the parts and requests page (show method)
    
    Route::get('/prelist', [StaffController::class, 'preList'])->name('staff.prelist'); // Now points to the parts and requests page (show method)
    
    Route::get('/staff/change-password', [StaffController::class, 'showChangePasswordForm'])
    ->name('staff.password.change.form');
    Route::post('/staff/change-password', [StaffController::class, 'changePassword'])
    ->name('staff.password.change')
    ->middleware('auth:staff');



    
    Route::get('/create', [StaffController::class, 'create'])->name('staff.create');
    Route::get('/request/{unique_code}', [StaffController::class, 'showRequestDetails'])->name('staff.request.details');
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
//Route::get('/download/attachment/{filename}', [StaffController::class, 'downloadAttachment'])
 //    ->name('download.attachment')
 //    ->middleware('auth:staff');

 Route::get('/download-attachment/{filename}', [StaffController::class, 'downloadAttachment'])
 ->name('staff.download.attachment');
 
Route::get('/download/final-attachment/{filename}', [StaffController::class, 'downloadFinalAttachment'])
     ->name('download.final_attachment')
     ->middleware('auth:staff');

// ====================== Manager Routes ======================
Route::prefix('manager')->middleware(['auth:manager'])->group(function () {

    Route::get('/manager/change-password', [ManagerController::class, 'showChangePasswordForm'])
        ->name('manager.password.change.form');
    
    Route::post('/manager/change-password', [ManagerController::class, 'changePassword'])
        ->name('manager.password.change')
        ->middleware('auth:manager');

    Route::get('/dashboard', [ManagerController::class, 'index'])->name('manager.dashboard');
    Route::get('/request/{unique_code}', [ManagerController::class, 'show'])->name('manager.request.details');
    Route::post('/request/reject/{unique_code}', [ManagerController::class, 'reject'])->name('manager.request.reject');
    
    Route::get('/final-dashboard', [ManagerController::class, 'finalDashboard'])->name('manager.final-dashboard');
    Route::get('/finalrequest-list', [ManagerController::class, 'finalRequestList'])->name('manager.finalrequest-list');
    Route::get('/finalrequest/details/{unique_code}', [ManagerController::class, 'finalRequestDetails'])->name('manager.finalrequest.details');
    
    Route::post('/notifications/mark-as-read', [ManagerController::class, 'markNotificationsAsRead'])
        ->name('notifications.mark-as-read');
    
    Route::get('/request-list', [ManagerController::class, 'requestList'])->name('manager.request-list');
    
    Route::post('/request/approve/{unique_code}', [ManagerController::class, 'approve'])->name('manager.request.approve');

    // Silent attachment downloads
    Route::get('/download/attachment/{filename}', [ManagerController::class, 'downloadAttachment'])
        ->name('manager.download.attachment')
        ->middleware('cache.headers:no_store'); // Prevent caching
    
    Route::get('/download/final-attachment/{filename}', [ManagerController::class, 'downloadFinalAttachment'])
        ->name('manager.download.final_attachment')
        ->middleware('cache.headers:no_store'); // Prevent caching

    // New direct download endpoints for silent downloads
    Route::get('/direct-download/attachment/{filename}', [ManagerController::class, 'directDownloadAttachment'])
        ->name('manager.direct.download.attachment')
        ->middleware('cache.headers:no_store');
    
    Route::get('/direct-download/final-attachment/{filename}', [ManagerController::class, 'directDownloadFinalAttachment'])
        ->name('manager.direct.download.final_attachment')
        ->middleware('cache.headers:no_store');
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