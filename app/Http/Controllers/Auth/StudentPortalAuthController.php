<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\StudentAccount;
use App\Models\AcademicTerm;
use App\Models\Status;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class StudentPortalAuthController extends Controller
{
    /**
     * Get the currently logged in student from session.
     */
    protected function getLoggedInStudent()
    {
        $studentId = session('student_portal_student_id');
        if (!$studentId) {
            return null;
        }
        return Student::with(['department', 'program', 'level', 'contact', 'account'])->find($studentId);
    }

    public function showLogin()
    {
        // If already logged in, redirect to dashboard
        if (session('student_portal_student_id')) {
            return redirect()->route('student_portal.dashboard');
        }

        // Check if student portal is enabled
        $portalStatus = Status::getStudentPortalStatus();
        if ($portalStatus !== 'on') {
            return view('student_portal.login', ['portalDisabled' => true]);
        }
        return view('student_portal.login', ['portalDisabled' => false]);
    }

    public function login(Request $request)
    {
        // Check if student portal is enabled
        $portalStatus = Status::getStudentPortalStatus();
        if ($portalStatus !== 'on') {
            throw ValidationException::withMessages([
                'student_number' => 'Student portal is currently disabled.',
            ]);
        }

        $validated = $request->validate([
            'student_number' => 'required|string',
            'password' => 'required|string',
        ]);

        // Find student by student number
        $student = Student::where('student_number', $validated['student_number'])->first();

        if (!$student) {
            throw ValidationException::withMessages([
                'student_number' => 'Student number not found.',
            ]);
        }

        // Get student account
        $account = StudentAccount::where('student_id', $student->id)->first();

        if (!$account) {
            throw ValidationException::withMessages([
                'student_number' => 'No account found for this student. Please contact the registrar.',
            ]);
        }

        // Check if account is active
        if (!$account->isActive()) {
            throw ValidationException::withMessages([
                'student_number' => 'Your account is not yet activated. Please contact the cashier.',
            ]);
        }

        // Verify password
        if (!$account->verifyPassword($validated['password'])) {
            throw ValidationException::withMessages([
                'password' => 'Invalid password.',
            ]);
        }

        // Login successful - store student ID in session
        $request->session()->regenerate();
        session(['student_portal_student_id' => $student->id]);

        return redirect()->route('student_portal.dashboard');
    }

    public function logout(Request $request)
    {
        session()->forget('student_portal_student_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student_portal.login');
    }

    public function showDashboard()
    {
        $student = $this->getLoggedInStudent();
        if (!$student) {
            return redirect()->route('student_portal.login');
        }
        
        return view('student_portal.dashboard', compact('student'));
    }

    public function showLedger()
    {
        $student = $this->getLoggedInStudent();
        if (!$student) {
            return redirect()->route('student_portal.login');
        }
        
        // Get active academic terms for the student's department
        $academicTerms = AcademicTerm::where('department_id', $student->department_id)
            ->where('status', 'active')
            ->orderBy('academic_year', 'desc')
            ->get();
        
        return view('student_portal.ledger', compact('student', 'academicTerms'));
    }

    public function showExaminationPermit()
    {
        $student = $this->getLoggedInStudent();
        if (!$student) {
            return redirect()->route('student_portal.login');
        }
        
        // Load account relationship
        $student->load('account');
        
        return view('student_portal.examination_permit', compact('student'));
    }

    public function showChangePassword()
    {
        $student = $this->getLoggedInStudent();
        if (!$student) {
            return redirect()->route('student_portal.login');
        }
        
        return view('student_portal.change_password', compact('student'));
    }

    public function changePassword(Request $request)
    {
        $student = $this->getLoggedInStudent();
        if (!$student) {
            return redirect()->route('student_portal.login');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $account = $student->account;

        // Verify current password
        if (!$account->verifyPassword($validated['current_password'])) {
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        // Update password
        $account->password = $validated['new_password'];
        $account->save();

        return redirect()->route('student_portal.change_password')
            ->with('success', 'Password changed successfully.');
    }

    /**
     * API: Get ledger data for a specific academic term
     */
    public function getLedgerData(Request $request, $academicTermId)
    {
        $student = $this->getLoggedInStudent();

        if (!$student) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        // Get fees for this student and academic term
        $fees = \App\Models\StudentFee::with('fee')
            ->where('student_id', $student->id)
            ->where('academic_term_id', $academicTermId)
            ->get();

        // Get transactions for this student and academic term
        $transactions = \App\Models\Transaction::with('paymentAccount')
            ->where('student_id', $student->id)
            ->where('academic_term_id', $academicTermId)
            ->orderBy('date', 'asc')
            ->get();

        // Calculate totals
        $totalFees = $fees->sum(function ($studentFee) {
            return $studentFee->fee ? $studentFee->fee->amount : 0;
        });

        $totalPayments = $transactions->sum('amount');
        $balance = $totalFees - $totalPayments;

        return response()->json([
            'success' => true,
            'data' => [
                'fees' => $fees->map(function ($sf) {
                    return [
                        'description' => $sf->fee->description ?? '-',
                        'amount' => $sf->fee->amount ?? 0,
                    ];
                }),
                'transactions' => $transactions->map(function ($t) {
                    return [
                        'description' => $t->paymentAccount->description ?? 'Payment',
                        'amount' => $t->amount,
                        'date' => $t->date->format('M d, Y'),
                        'or_number' => $t->or_number,
                    ];
                }),
                'total_fees' => $totalFees,
                'total_payments' => $totalPayments,
                'balance' => $balance,
            ],
        ]);
    }
}
