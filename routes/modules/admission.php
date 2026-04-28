<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\Auth\AdmissionAuthController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdmissionProcessController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PdfController;

/*
|--------------------------------------------------------------------------
| Admission Routes
|--------------------------------------------------------------------------
| Routes for the admission module
*/

Route::prefix('admission')->name('admission.')->group(function () {
    Route::get('/', [AdmissionAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdmissionAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdmissionAuthController::class, 'logout'])->name('logout');

    // Protected routes - require authentication and admission type
    Route::middleware(['auth', 'can:access-admission'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'admissionDashboard'])->name('dashboard');

        Route::get('/applicant', [ApplicantController::class, 'showApplicant'])->name('applicant');
        Route::get('/applicant/{id}/edit', [ApplicantController::class, 'editApplicant'])->name('applicant.edit');
        Route::post('/applicant/{id}/update', [ApplicantController::class, 'updateApplicant'])->name('applicant.update');
        Route::get('/api/applicants/search', [ApplicantController::class, 'searchApplicants'])->name('api.applicants.search');
        
        Route::get('/schedules', [ScheduleController::class, 'showSchedule'])->name('schedule');
        Route::post('/schedules', [ScheduleController::class, 'createSchedule'])->name('schedule.create');
        Route::post('/schedules/{id}/update', [ScheduleController::class, 'updateSchedule'])->name('schedule.update');
        Route::post('/schedules/{id}/delete', [ScheduleController::class, 'deleteSchedule'])->name('schedule.delete');
        Route::get('/api/schedules', [ScheduleController::class, 'getSchedulesByProcess'])->name('api.schedules');
        
        Route::get('/interview', [AdmissionProcessController::class, 'showInterview'])->name('interview');
        Route::get('/exam', [AdmissionProcessController::class, 'showExam'])->name('exam');
        Route::get('/evaluation', [AdmissionProcessController::class, 'showEvaluation'])->name('evaluation');
        
        Route::post('/interview/{id}/update', [AdmissionProcessController::class, 'updateInterview'])->name('interview.update');
        Route::post('/exam/{id}/update', [AdmissionProcessController::class, 'updateExam'])->name('exam.update');
        Route::post('/evaluation/{id}/update', [AdmissionProcessController::class, 'updateEvaluation'])->name('evaluation.update');
        
        Route::post('/applicant/mark-interview', [ApplicantController::class, 'createApplicantProcess'])->name('applicant.mark-interview');
        Route::post('/applicant/delete', [ApplicantController::class, 'deleteApplicants'])->name('applicant.delete');
        Route::post('/interview/process-action', [AdmissionProcessController::class, 'processInterviewAction'])->name('interview.process-action');
        Route::post('/exam/process-action', [AdmissionProcessController::class, 'processExamAction'])->name('exam.process-action');
        Route::post('/evaluation/process-action', [AdmissionProcessController::class, 'processEvaluationAction'])->name('evaluation.process-action');
        Route::post('/evaluation/admit', [AdmissionProcessController::class, 'admitStudents'])->name('evaluation.admit');
        
        Route::get('/student', [StudentController::class, 'showStudent'])->name('student');
        Route::get('/api/students/search', [StudentController::class, 'searchStudents'])->name('api.students.search');
        Route::get('/student/{id}/edit', [StudentController::class, 'editStudent'])->name('student.edit');
        Route::post('/student/{id}/update', [StudentController::class, 'updateStudent'])->name('student.update');
        
        Route::get('/print-admission-stats', [PdfController::class, 'printAdmissionStats'])->name('print.admission.stats');
        Route::get('/print-applicant-details/{id}', [PdfController::class, 'printApplicantDetails'])->name('print.applicant.details');
        Route::get('/print-interview-list', [PdfController::class, 'printInterviewList'])->name('print.interview.list');
        Route::get('/print-exam-list', [PdfController::class, 'printExamList'])->name('print.exam.list');
        Route::get('/print-evaluation-list', [PdfController::class, 'printEvaluationList'])->name('print.evaluation.list');
        Route::get('/print-schedule-applicants/{id}', [PdfController::class, 'printScheduleApplicants'])->name('print.schedule.applicants');
    });
});
