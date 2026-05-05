<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\DepartmentAuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DepartmentComponentController;
use App\Http\Controllers\DepartmentGradingSystemController;
use App\Http\Controllers\SubjectOfferingController;
use App\Http\Controllers\TeacherOfferingController;
use App\Http\Controllers\EnlistmentController;
use App\Http\Controllers\DepartmentGradeReportController;
use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Department Routes
|--------------------------------------------------------------------------
| Routes for the department module
*/

Route::prefix('department')->name('department.')->group(function () {
    Route::get('/', [DepartmentAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [DepartmentAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [DepartmentAuthController::class, 'logout'])->name('logout');

    // Protected routes - require authentication and department type
    Route::middleware(['auth', 'can:access-department'])->group(function () {
        Route::get('/dashboard', [DepartmentAuthController::class, 'showDashboard'])->name('dashboard');
        
        Route::get('/student', [StudentController::class, 'showDepartmentStudents'])->name('student');
        Route::get('/api/students/search', [StudentController::class, 'searchDepartmentStudents'])->name('api.students.search');
        Route::get('/student/{id}/edit', [StudentController::class, 'editStudent'])->name('student.edit');
        Route::post('/student/{id}/update', [StudentController::class, 'updateStudent'])->name('student.update');

        Route::get('/enlistment', [EnlistmentController::class, 'showEnlistment'])->name('enlistment');
        Route::get('/enlistment/{student_id}/{academic_term_id}', [EnlistmentController::class, 'viewStudentSubjects'])->name('student.subjects');
        Route::post('/enlistment/{id}/update', [EnlistmentController::class, 'updateStudentEnlistmentApi'])->name('enlistment.update');
        
        // Enlistment API routes
        Route::get('/api/subject-offerings/{academicTermId}', [EnlistmentController::class, 'getSubjectOfferingsApi'])->name('api.enlistment.offerings');
        Route::get('/api/sections/{academicTermId}', [EnlistmentController::class, 'getSectionsApi'])->name('api.enlistment.sections');
        Route::get('/api/enlistments/{studentId}/{academicTermId}', [EnlistmentController::class, 'getStudentEnlistmentsApi'])->name('api.enlistment.list');
        Route::post('/api/enlistment/add', [EnlistmentController::class, 'addEnlistmentApi'])->name('api.enlistment.add');
        Route::post('/api/enlistment/add-section', [EnlistmentController::class, 'addEnlistmentBySectionApi'])->name('api.enlistment.add-section');
        Route::delete('/api/enlistment/{id}/remove', [EnlistmentController::class, 'removeEnlistmentApi'])->name('api.enlistment.remove');

        Route::get('/subject-offering', [SubjectOfferingController::class, 'showSubjectOffering'])->name('subject_offering');
        Route::post('/subject-offering/search', [SubjectOfferingController::class, 'searchOffering'])->name('subject_offering.search');
        Route::post('/subject-offering/add', [SubjectOfferingController::class, 'addSubjectOffering'])->name('subject_offering.add');
        Route::delete('/subject-offering/{id}/remove', [SubjectOfferingController::class, 'removeSubjectOffering'])->name('subject_offering.remove');
        Route::get('/print-subject-offerings', [PdfController::class, 'printSubjectOfferings'])->name('print.subject_offerings');
        Route::get('/api/curricula-by-department/{departmentId}', [SubjectOfferingController::class, 'getCurriculaByDepartment'])->name('api.curricula');
        Route::get('/api/subject-offering/{academicTermId}/{departmentId}', [SubjectOfferingController::class, 'getSubjectOffering'])->name('api.subject_offering');
        Route::get('/api/subjects/search', [SubjectOfferingController::class, 'searchSubjects'])->name('api.subjects.search');
        Route::get('/api/levels-by-program/{programId}', [SubjectOfferingController::class, 'getLevelsByProgram'])->name('api.levels.by-program');

        // Grading Components
        Route::get('/grading-components', [DepartmentComponentController::class, 'index'])->name('grading_components.index');
        Route::post('/grading-components', [DepartmentComponentController::class, 'store'])->name('grading_components.store');
        Route::post('/grading-components/{id}/update', [DepartmentComponentController::class, 'update'])->name('grading_components.update');
        Route::post('/grading-components/{id}/delete', [DepartmentComponentController::class, 'destroy'])->name('grading_components.delete');

        // Grading Systems
        Route::get('/grading-systems', [DepartmentGradingSystemController::class, 'index'])->name('grading_systems.index');
        Route::post('/grading-systems', [DepartmentGradingSystemController::class, 'store'])->name('grading_systems.store');
        Route::post('/grading-systems/{id}/update', [DepartmentGradingSystemController::class, 'update'])->name('grading_systems.update');
        Route::post('/grading-systems/{id}/delete', [DepartmentGradingSystemController::class, 'destroy'])->name('grading_systems.delete');
        Route::get('/api/grading-systems-by-department/{departmentId}', [SubjectOfferingController::class, 'getGradingSystemsByDepartment'])->name('api.grading_systems');

        // Teacher Loading
        Route::get('/teacher-loading', [TeacherOfferingController::class, 'showTeacherLoading'])->name('teacher_loading');
        Route::post('/teacher-loading/assign', [TeacherOfferingController::class, 'createTeacherOffering'])->name('teacher_loading.assign');
        Route::get('/teacher-loading/teachers/{teacherId}/subjects', [TeacherOfferingController::class, 'showTeacherSubjects'])->name('teacher_loading.subjects');

        // Grade Report
        Route::get('/grade-report', [DepartmentGradeReportController::class, 'index'])->name('grade_report');
        Route::get('/grade-report/students/{studentId}/view', [DepartmentGradeReportController::class, 'view'])->name('grade_report.view');
        Route::get('/api/grade-report/academic-terms', [DepartmentGradeReportController::class, 'getAcademicTerms'])->name('api.grade_report.academic_terms');
        Route::get('/api/grade-report/subject-offerings', [DepartmentGradeReportController::class, 'getSubjectOfferings'])->name('api.grade_report.subject_offerings');
        Route::get('/api/grade-report/periods', [DepartmentGradeReportController::class, 'getPeriods'])->name('api.grade_report.periods');
    });
});
