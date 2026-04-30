<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TeacherAuthController;

/*
|--------------------------------------------------------------------------
| Teacher Portal Routes
|--------------------------------------------------------------------------
| Routes for the teacher portal module
*/

Route::prefix('teacher-portal')->name('teacher_portal.')->group(function () {
    // Public routes
    Route::get('/', [TeacherAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [TeacherAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [TeacherAuthController::class, 'logout'])->name('logout');

    // Protected routes - require teacher portal authentication
    Route::middleware(['teacher.portal.auth'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [TeacherAuthController::class, 'showDashboard'])->name('dashboard');

        // Subject Load
        Route::get('/subject-load', [TeacherAuthController::class, 'showSubjectLoad'])->name('subject_load');

        // Input Grade
        Route::get('/input-grade/{teacherOfferingId}', [TeacherAuthController::class, 'showInputGrade'])->name('input_grade');
    });
});
