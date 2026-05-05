<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TeacherAuthController;
use App\Http\Controllers\GradeController;

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

        // Change Password
        Route::get('/change-password', [TeacherAuthController::class, 'showChangePassword'])->name('change_password');
        Route::post('/change-password', [TeacherAuthController::class, 'changePassword'])->name('change_password.submit');

        // Class List
        Route::get('/class-list', [TeacherAuthController::class, 'showClassList'])->name('class_list');
        Route::get('/class-list/{teacherOfferingId}/print', [TeacherAuthController::class, 'printClassList'])->name('class_list.print');

        // Input Grade
        Route::get('/input-grade/{teacherOfferingId}', [TeacherAuthController::class, 'showInputGrade'])->name('input_grade');

        // API routes for dynamic loading
        Route::get('/api/student-grades/{teacherOfferingId}', [TeacherAuthController::class, 'getStudentGrades'])->name('api.student_grades');
        Route::get('/api/subject-offerings/{academicTermId}', [TeacherAuthController::class, 'getSubjectOfferings'])->name('api.subject_offerings');
        Route::get('/api/class-list/{teacherOfferingId}', [TeacherAuthController::class, 'getClassList'])->name('api.class_list');
        Route::get('/api/component-columns/{teacherOfferingId}', [TeacherAuthController::class, 'getComponentColumns'])->name('api.component_columns');
        Route::patch('/api/grade-column/{gradeColumnId}/highest-score', [GradeController::class, 'updateHighestScore'])->name('api.grade_column.highest_score');
        Route::post('/api/grade-column/{teacherOfferingId}', [GradeController::class, 'storeGradeColumn'])->name('api.grade_column.store');
        Route::delete('/api/grade-column/{gradeColumnId}', [GradeController::class, 'deleteGradeColumn'])->name('api.grade_column.delete');
        Route::patch('/api/raw-score/{gradeId}/{gradeColumnId}', [GradeController::class, 'updateRawScore'])->name('api.raw_score.update');
        Route::patch('/api/grade/{gradeId}/status', [GradeController::class, 'updateGradeStatus'])->name('api.grade.update_status');
    });
});
