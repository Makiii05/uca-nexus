<?php

use Illuminate\Support\Facades\Route;
use App\Models\Website;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home route - Landing Page
Route::get('/', function () {
    $websites = Website::all();
    return view('index', ['websites' => $websites]);
})->name('index');

// Navigate route - Department Portal Selection
Route::get('/navigate', function () {
    return view('navigate');
})->name('navigate');

/*
|--------------------------------------------------------------------------
| Module Routes
|--------------------------------------------------------------------------
| Routes are separated by module for better organization and maintainability.
| Each module has its own route file in the routes/modules directory.
*/

// Applicant module - public application routes
require __DIR__.'/modules/applicant.php';

// Registrar module - registrar dashboard and management
require __DIR__.'/modules/registrar.php';

// Accounting module - fees, payments, and cashier
require __DIR__.'/modules/accounting.php';

// Admission module - applicant processing and student admission
require __DIR__.'/modules/admission.php';

// Department module - department-specific management
require __DIR__.'/modules/department.php';

// Admin module - system administration
require __DIR__.'/modules/admin.php';

// Student Portal module - student self-service
require __DIR__.'/modules/student_portal.php';