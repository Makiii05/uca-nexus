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
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\RegistrarGradeApprovalController;
use App\Http\Controllers\RegistrarGradeReportController;
use App\Http\Controllers\ClassListController;
use App\Http\Controllers\EnrollmentStatusController;
use App\Http\Controllers\RegistrarOfficialStudentController;

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

        // Subject Fee API routes
        Route::get('/api/subjects/{id}/fees', [SubjectController::class, 'getSubjectFees'])->name('api.subject.fees');
        Route::get('/api/subjects/academic-terms', [SubjectController::class, 'getAcademicTermsByProgram'])->name('api.subject.academic-terms');
        Route::get('/api/subjects/fees-by-term', [SubjectController::class, 'getFeesByTerm'])->name('api.subject.fees-by-term');
        Route::post('/api/subjects/{id}/fees', [SubjectController::class, 'addSubjectFee'])->name('api.subject.fees.add');
        Route::delete('/api/subjects/fees/{subjectFeeId}', [SubjectController::class, 'removeSubjectFee'])->name('api.subject.fees.remove');

        Route::get('/levels', [LevelController::class, 'showLevel'])->name('level');
        Route::post('/levels', [LevelController::class, 'createLevel'])->name('level.create');
        Route::post('/levels/{id}/update', [LevelController::class, 'updateLevel'])->name('level.update');
        Route::post('/levels/{id}/delete', [LevelController::class, 'deleteLevel'])->name('level.delete');

        Route::get('/teachers', [TeacherController::class, 'showTeacher'])->name('teacher');
        Route::post('/teachers', [TeacherController::class, 'createTeacher'])->name('teacher.create');
        Route::post('/teachers/import', [TeacherController::class, 'importTeachers'])->name('teacher.import');
        Route::get('/teachers/import/sample', [TeacherController::class, 'downloadTeacherImportSample'])->name('teacher.import.sample');
        Route::post('/teachers/{id}/update', [TeacherController::class, 'updateTeacher'])->name('teacher.update');
        Route::post('/teachers/{id}/toggle-account', [TeacherController::class, 'toggleTeacherAccountStatus'])->name('teacher.toggle-account');
        Route::post('/teachers/{id}/delete', [TeacherController::class, 'deleteTeacher'])->name('teacher.delete');

        Route::get('/prospectuses', [ProspectusController::class, 'showProspectus'])->name('prospectus');
        Route::match(['get', 'post'], '/prospectuses/search', [ProspectusController::class, 'searchProspectus'])->name('prospectus.search');
        Route::post('/prospectuses', [ProspectusController::class, 'createProspectus'])->name('prospectus.create');
        Route::post('/prospectuses/{id}/update', [ProspectusController::class, 'updateProspectus'])->name('prospectus.update');
        Route::post('/prospectuses/{id}/delete', [ProspectusController::class, 'deleteProspectus'])->name('prospectus.delete');

        Route::get('/grade-approval', [RegistrarGradeApprovalController::class, 'showGradeApproval'])->name('grade_approval');
        Route::get('/api/grade-approval/teachers', [RegistrarGradeApprovalController::class, 'getTeachersByDepartment'])->name('api.grade_approval.teachers');
        Route::get('/api/grade-approval/academic-terms', [RegistrarGradeApprovalController::class, 'getAcademicTermsByDepartment'])->name('api.grade_approval.academic_terms');
        Route::get('/api/grade-approval/subject-offerings', [RegistrarGradeApprovalController::class, 'getSubjectOfferings'])->name('api.grade_approval.subject_offerings');
        Route::get('/api/grade-approval/periods', [RegistrarGradeApprovalController::class, 'getPeriodsByAcademicTerm'])->name('api.grade_approval.periods');
        Route::get('/api/grade-approval/grades', [RegistrarGradeApprovalController::class, 'getSubmittedGrades'])->name('api.grade_approval.grades');
        Route::patch('/api/grade-approval/grades/{gradeId}/status', [RegistrarGradeApprovalController::class, 'updateGradeStatus'])->name('api.grade_approval.update_status');

        // Grade Report routes
        Route::get('/grade-report', [RegistrarGradeReportController::class, 'index'])->name('grade_report');
        Route::get('/grade-report/students/{studentId}/view', [RegistrarGradeReportController::class, 'view'])->name('grade_report.view');
        Route::get('/grade-report/students/{studentId}/pdf', [RegistrarGradeReportController::class, 'pdf'])->name('grade_report.pdf');
        Route::get('/grade-report/pdf', [RegistrarGradeReportController::class, 'pdfAll'])->name('grade_report.pdf_all');

        Route::get('/api/grade-report/academic-terms', [RegistrarGradeReportController::class, 'getAcademicTerms'])->name('api.grade_report.academic_terms');
        Route::get('/api/grade-report/subject-offerings', [RegistrarGradeReportController::class, 'getSubjectOfferings'])->name('api.grade_report.subject_offerings');
        Route::get('/api/grade-report/periods', [RegistrarGradeReportController::class, 'getPeriods'])->name('api.grade_report.periods');
        

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

        // Official Students
        Route::get('/official-students', [RegistrarOfficialStudentController::class, 'index'])->name('official_students');
        Route::get('/official-students/{id}/edit', [RegistrarOfficialStudentController::class, 'edit'])->name('official_students.edit');
        Route::post('/official-students/{id}/update', [RegistrarOfficialStudentController::class, 'update'])->name('official_students.update');
        Route::post('/official-students/{id}/profile-picture', [RegistrarOfficialStudentController::class, 'uploadProfilePicture'])->name('official_students.upload_pfp');
        Route::delete('/official-students/{id}/profile-picture', [RegistrarOfficialStudentController::class, 'deleteProfilePicture'])->name('official_students.delete_pfp');
        Route::get('/api/official-students/search', [RegistrarOfficialStudentController::class, 'search'])->name('api.official_students.search');
        Route::get('/print-official-student/{id}', [PdfController::class, 'printOfficialStudentDetails'])->name('print.official_student');
    });
});
