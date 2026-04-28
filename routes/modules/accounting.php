<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\Auth\AccountingAuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\PaymentDetailsController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\StudentPortalStatusController;

/*
|--------------------------------------------------------------------------
| Accounting Routes
|--------------------------------------------------------------------------
| Routes for the accounting module
*/

Route::prefix('accounting')->name('accounting.')->group(function () {
    Route::get('/', [AccountingAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AccountingAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AccountingAuthController::class, 'logout'])->name('logout');

    // Protected routes - require authentication and accounting type
    Route::middleware(['auth', 'can:access-accounting'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [AccountingAuthController::class, 'showDashboard'])->name('dashboard');
        
        // Student Portal Status API
        Route::post('/api/student-portal-status/toggle', [StudentPortalStatusController::class, 'toggle'])->name('api.student-portal-status.toggle');
        Route::get('/api/student-portal-status', [StudentPortalStatusController::class, 'status'])->name('api.student-portal-status');

        // Student Account Status API
        Route::post('/api/student-accounts/deactivate-all', [StudentPortalStatusController::class, 'deactivateAllAccounts'])->name('api.student-accounts.deactivate-all');
        Route::post('/api/student-accounts/{id}/toggle', [StudentPortalStatusController::class, 'toggleAccountStatus'])->name('api.student-accounts.toggle');

        // Examination Permits API
        Route::post('/api/examination-permits/clear-all', [StudentPortalStatusController::class, 'clearAllExaminationPermits'])->name('api.examination-permits.clear-all');

        Route::get('/fee', [FeeController::class, 'showFees'])->name('fee');
        Route::match(['get', 'post'], '/fee/search', [FeeController::class, 'searchFee'])->name('fee.search');
        Route::post('/fee', [FeeController::class, 'createFee'])->name('fee.create');
        Route::post('/fee/{id}/update', [FeeController::class, 'updateFee'])->name('fee.update');
        Route::post('/fee/{id}/delete', [FeeController::class, 'deleteFee'])->name('fee.delete');
        Route::get('/fee/{id}/ledger', [FeeController::class, 'showLedger'])->name('fee.ledger');
        Route::get('/fee/{id}/ledger/print', [PdfController::class, 'printFeeLedger'])->name('fee.ledger.print');

        Route::get('/api/academic-terms', [FeeController::class, 'getAcademicTermsByProgram'])->name('api.academic-terms');

        // Cashier routes
        Route::get('/cashier', [CashierController::class, 'showCashier'])->name('cashier');
        Route::get('/api/students/search', [CashierController::class, 'searchStudents'])->name('api.students.search');
        Route::get('/payment/{id}', [CashierController::class, 'showPayment'])->name('payment');
        Route::get('/api/student-fees/{studentId}/{academicTermId}', [CashierController::class, 'getStudentFees'])->name('api.student.fees');
        Route::get('/api/enlistments/{studentId}/{academicTermId}', [CashierController::class, 'getEnlistments'])->name('api.enlistments');
        
        // Transaction routes
        Route::get('/api/transactions/{studentId}/{academicTermId}', [CashierController::class, 'getTransactions'])->name('api.transactions');
        Route::post('/api/transactions/create', [CashierController::class, 'createTransaction'])->name('api.transactions.create');
        Route::delete('/api/transactions/{id}', [CashierController::class, 'deleteTransaction'])->name('api.transactions.delete');
        Route::get('/api/next-or-number', [CashierController::class, 'getNextOrNumber'])->name('api.next-or-number');

        // Print routes
        Route::get('/print/daily-transactions', [PdfController::class, 'printDailyTransactions'])->name('print.daily-transactions');
        Route::get('/print/sales-invoice/{id}', [PdfController::class, 'printSalesInvoice'])->name('print.sales-invoice');
        Route::get('/print/examination-permit/{studentId}', [PdfController::class, 'printExaminationPermit'])->name('print.examination-permit');

        // Examination Permit API
        Route::post('/api/examination-permit/{accountId}/generate', [StudentPortalStatusController::class, 'generateExaminationPermit'])->name('api.examination-permit.generate');
        Route::post('/api/examination-permit/{accountId}/clear', [StudentPortalStatusController::class, 'clearExaminationPermit'])->name('api.examination-permit.clear');

        // Payment Details routes
        Route::get('/payment-details', [PaymentDetailsController::class, 'index'])->name('payment_details');
        Route::post('/payment-accounts', [PaymentDetailsController::class, 'storePaymentAccount'])->name('payment_accounts.store');
        Route::post('/payment-accounts/{id}/update', [PaymentDetailsController::class, 'updatePaymentAccount'])->name('payment_accounts.update');
        Route::post('/payment-accounts/{id}/delete', [PaymentDetailsController::class, 'deletePaymentAccount'])->name('payment_accounts.delete');
        Route::post('/payment-types', [PaymentDetailsController::class, 'storePaymentType'])->name('payment_types.store');
        Route::post('/payment-types/{id}/update', [PaymentDetailsController::class, 'updatePaymentType'])->name('payment_types.update');
        Route::post('/payment-types/{id}/delete', [PaymentDetailsController::class, 'deletePaymentType'])->name('payment_types.delete');
        Route::get('/api/payment-accounts', [PaymentDetailsController::class, 'getPaymentAccounts'])->name('api.payment_accounts');
        Route::get('/api/payment-types', [PaymentDetailsController::class, 'getPaymentTypes'])->name('api.payment_types');
    });
});
