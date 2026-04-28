<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApplicantController;

/*
|--------------------------------------------------------------------------
| Applicant Routes
|--------------------------------------------------------------------------
| Public application routes for applicants
*/

Route::prefix('application')->name('applicant.')->group(function () {
    Route::get('/', [ApplicantController::class, 'showApplication'])->name('form');
    Route::post('/', [ApplicantController::class, 'createApplication'])->name('create');
});
