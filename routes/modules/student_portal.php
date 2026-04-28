<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\StudentPortalAuthController;

/*
|--------------------------------------------------------------------------
| Student Portal Routes
|--------------------------------------------------------------------------
| Routes for the student portal module
*/

Route::prefix('student-portal')->name('student_portal.')->group(function () {
    // Public routes
    Route::get('/', [StudentPortalAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [StudentPortalAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [StudentPortalAuthController::class, 'logout'])->name('logout');

    // Protected routes - require student portal authentication
    Route::middleware(['student.portal.auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [StudentPortalAuthController::class, 'showDashboard'])->name('dashboard');
        
        // Ledger
        Route::get('/ledger', [StudentPortalAuthController::class, 'showLedger'])->name('ledger');
        Route::get('/api/ledger/{academicTermId}', [StudentPortalAuthController::class, 'getLedgerData'])->name('api.ledger');
        
        // Examination Permit
        Route::get('/examination-permit', [StudentPortalAuthController::class, 'showExaminationPermit'])->name('examination_permit');

        // Change Password
        Route::get('/change-password', [StudentPortalAuthController::class, 'showChangePassword'])->name('change_password');
        Route::post('/change-password', [StudentPortalAuthController::class, 'changePassword'])->name('change_password.submit');
    });
});
