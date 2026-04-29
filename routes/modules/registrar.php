<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurriculumController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProspectusController;
use App\Http\Controllers\Auth\RegistrarAuthController;
use App\Http\Controllers\AcademicTermController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\RegistrarStudentController;
use App\Http\Controllers\ClassListController;
use App\Http\Controllers\EnrollmentStatusController;

/*
|--------------------------------------------------------------------------
| Registrar Routes
|--------------------------------------------------------------------------
| Routes for the registrar module
*/

Route::prefix('registrar')->name('registrar.')->group(function () {
    Route::get('/', [RegistrarAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [RegistrarAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [RegistrarAuthController::class, 'logout'])->name('logout');

    // Protected routes - require authentication and registrar type
    Route::middleware(['auth', 'can:access-registrar'])->group(function () {
        Route::get('/dashboard', [RegistrarAuthController::class, 'showDashboard'])->name('dashboard');

        Route::get('/departments', [DepartmentController::class, 'showDepartment'])->name('department');
        Route::post('/departments', [DepartmentController::class, 'createDepartment'])->name('department.create');
        Route::post('/departments/{id}/update', [DepartmentController::class, 'updateDepartment'])->name('department.update');
        Route::post('/departments/{id}/delete', [DepartmentController::class, 'deleteDepartment'])->name('department.delete');

        Route::get('/programs', [ProgramController::class, 'showProgram'])->name('program');
        Route::post('/programs', [ProgramController::class, 'createProgram'])->name('program.create');
        Route::post('/programs/{id}/update', [ProgramController::class, 'updateProgram'])->name('program.update');
        Route::post('/programs/{id}/delete', [ProgramController::class, 'deleteProgram'])->name('program.delete');

        Route::get('/curricula', [CurriculumController::class, 'showCurriculum'])->name('curriculum');
        Route::post('/curricula', [CurriculumController::class, 'createCurriculum'])->name('curriculum.create');
        Route::post('/curricula/{id}/update', [CurriculumController::class, 'updateCurriculum'])->name('curriculum.update');
        Route::post('/curricula/{id}/delete', [CurriculumController::class, 'deleteCurriculum'])->name('curriculum.delete');

        Route::get('/academic-terms', [AcademicTermController::class, 'showAcademicTerm'])->name('academic_term');
        Route::post('/academic-terms', [AcademicTermController::class, 'createAcademicTerm'])->name('academic_term.create');
        Route::post('/academic-terms/{id}/update', [AcademicTermController::class, 'updateAcademicTerm'])->name('academic_term.update');
        Route::post('/academic-terms/{id}/delete', [AcademicTermController::class, 'deleteAcademicTerm'])->name('academic_term.delete');

        Route::get('/academic-years', [AcademicYearController::class, 'showAcademicYear'])->name('academic_year');
        Route::post('/academic-years', [AcademicYearController::class, 'createAcademicYear'])->name('academic_year.create');
        Route::post('/academic-years/{id}/update', [AcademicYearController::class, 'updateAcademicYear'])->name('academic_year.update');
        Route::post('/academic-years/{id}/delete', [AcademicYearController::class, 'deleteAcademicYear'])->name('academic_year.delete');

        Route::get('/subjects', [SubjectController::class, 'showSubject'])->name('subject');
        Route::post('/subjects', [SubjectController::class, 'createSubject'])->name('subject.create');
        Route::post('/subjects/{id}/update', [SubjectController::class, 'updateSubject'])->name('subject.update');
        Route::post('/subjects/{id}/delete', [SubjectController::class, 'deleteSubject'])->name('subject.delete');

        Route::get('/levels', [LevelController::class, 'showLevel'])->name('level');
        Route::post('/levels', [LevelController::class, 'createLevel'])->name('level.create');
        Route::post('/levels/{id}/update', [LevelController::class, 'updateLevel'])->name('level.update');
        Route::post('/levels/{id}/delete', [LevelController::class, 'deleteLevel'])->name('level.delete');

        Route::get('/prospectuses', [ProspectusController::class, 'showProspectus'])->name('prospectus');
        Route::match(['get', 'post'], '/prospectuses/search', [ProspectusController::class, 'searchProspectus'])->name('prospectus.search');
        Route::post('/prospectuses', [ProspectusController::class, 'createProspectus'])->name('prospectus.create');
        Route::post('/prospectuses/{id}/update', [ProspectusController::class, 'updateProspectus'])->name('prospectus.update');
        Route::post('/prospectuses/{id}/delete', [ProspectusController::class, 'deleteProspectus'])->name('prospectus.delete');
        
        // API routes for dynamic loading
        Route::get('/students', [RegistrarStudentController::class, 'showStudents'])->name('student');
        Route::get('/api/students/search', [RegistrarStudentController::class, 'searchStudents'])->name('api.students.search');
        Route::get('/students/{id}/assessment', [RegistrarStudentController::class, 'showAssessment'])->name('student.assessment');
        Route::get('/students/{id}/print-assessment', [PdfController::class, 'printStudentAssessment'])->name('student.print-assessment');
        Route::post('/students/{id}/update-level', [RegistrarStudentController::class, 'updateLevel'])->name('student.update-level');
        Route::get('/api/enlistments/{studentId}/{academicTermId}', [RegistrarStudentController::class, 'getEnlistments'])->name('api.student.enlistments');
        Route::get('/api/student-fees/{studentId}/{academicTermId}', [RegistrarStudentController::class, 'getStudentFees'])->name('api.student.fees');
        Route::get('/api/existing-fees/{studentId}/{academicTermId}/{group}', [RegistrarStudentController::class, 'getExistingFees'])->name('api.existing.fees');
        Route::post('/api/student-fees/{studentId}/create', [RegistrarStudentController::class, 'createStudentFee'])->name('api.student.fees.create');
        Route::post('/api/student-fees/{studentId}/assign', [RegistrarStudentController::class, 'assignExistingFee'])->name('api.student.fees.assign');
        Route::delete('/api/student-fees/{studentFeeId}', [RegistrarStudentController::class, 'removeStudentFee'])->name('api.student.fees.remove');

        // Assessment history routes
        Route::get('/api/assessment-histories/{studentId}', [RegistrarStudentController::class, 'getAssessmentHistories'])->name('api.assessment-histories');
        Route::delete('/api/assessment-histories/{id}', [RegistrarStudentController::class, 'deleteAssessmentHistory'])->name('api.assessment-histories.delete');

        Route::get('/api/levels-by-department/{departmentId}', [ProspectusController::class, 'getLevelsByDepartment'])->name('api.levels');
        Route::get('/api/curricula-by-department/{departmentId}', [ProspectusController::class, 'getCurriculaByDepartment'])->name('api.curricula');
        Route::get('/api/terms-by-department/{departmentId}', [ProspectusController::class, 'getTermsByDepartment'])->name('api.terms');
        Route::get('/api/prospectuses', [ProspectusController::class, 'getProspectusesApi'])->name('api.prospectuses');

        // Class List routes
        Route::get('/classlist', [ClassListController::class, 'showClassList'])->name('classlist');
        Route::get('/classlist/{id}/enrolled', [ClassListController::class, 'showEnrolledStudents'])->name('classlist.enrolled');
        Route::get('/classlist/{id}/print', [PdfController::class, 'printClassList'])->name('classlist.print');

        // Enrollment Status API
        Route::post('/api/enrollment-status/toggle', [EnrollmentStatusController::class, 'toggle'])->name('api.enrollment-status.toggle');
        Route::get('/api/enrollment-status', [EnrollmentStatusController::class, 'status'])->name('api.enrollment-status');
    });
});
