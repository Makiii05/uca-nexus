<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Applicant;
use App\Models\Admission;
use App\Models\Level;
use App\Models\Schedule;
use App\Models\SubjectOffering;
use App\Models\AcademicTerm;
use App\Models\Department;
use App\Models\Student;
use App\Models\Enlistment;
use App\Models\StudentFee;
use App\Models\AssessmentHistory;
use App\Models\AssessmentHistoryEnlistment;
use App\Models\AssessmentHistoryFee;
use App\Models\AssessmentHistoryStudent;
use App\Models\Transaction;
use App\Models\Fee;
use App\Http\Controllers\DashboardController;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
    public function printAdmissionStats(Request $request)
    {
        $academicYears = DashboardController::getAcademicYearOptions();
        $defaultYear = \App\Models\AcademicYear::getActiveYearLabel() ?? $academicYears->first()?->label ?? '';
        $selectedYear = $request->query('academic_year', $defaultYear);

        // Group by applicants.level (e.g. "College", "Senior High", "Junior High", etc.)
        $levelStats = Applicant::select(
                'applicants.level as level_name',
                DB::raw("COUNT(DISTINCT applicants.id) as total_applicants"),
                DB::raw("SUM(CASE WHEN admissions.interview_result IN ('passed','failed') THEN 1 ELSE 0 END) as total_interviewee"),
                DB::raw("SUM(CASE WHEN admissions.exam_result IN ('passed','failed') THEN 1 ELSE 0 END) as total_examinee"),
                DB::raw("SUM(CASE WHEN admissions.decision IN ('accepted','rejected') THEN 1 ELSE 0 END) as total_evaluatee"),
                DB::raw("SUM(CASE WHEN admissions.decision = 'accepted' THEN 1 ELSE 0 END) as total_admitted")
            )
            ->leftJoin('admissions', 'applicants.id', '=', 'admissions.applicant_id')
            ->where('applicants.academic_year', $selectedYear)
            ->groupBy('applicants.level')
            ->orderBy('applicants.level')
            ->get();

        // Calculate grand totals
        $grandTotals = [
            'total_applicants'  => $levelStats->sum('total_applicants'),
            'total_interviewee' => $levelStats->sum('total_interviewee'),
            'total_examinee'    => $levelStats->sum('total_examinee'),
            'total_evaluatee'   => $levelStats->sum('total_evaluatee'),
            'total_admitted'    => $levelStats->sum('total_admitted'),
        ];
        $grandTotals['variance'] = $grandTotals['total_applicants'] - $grandTotals['total_admitted'];

        $pdf = Pdf::loadView('pdf.print_admission_stats', compact('levelStats', 'grandTotals', 'selectedYear'));
        
        return $pdf->stream('admission_stats_' . date('Y-m-d') . '.pdf');
    }

    public function printApplicantDetails($id)
    {
        $applicants = Applicant::where('id', $id)->get();

        $pdf = Pdf::loadView('pdf.print_applicant_details', compact('applicants'));
        
        return $pdf->stream('applicant_details_' . date('Y-m-d') . '.pdf');
        // return view('pdf.print_applicant_details', compact('applicants'));
    }

    public function printInterviewList(Request $request)
    {
        $levelId = $request->query('level_id');
        $level = null;

        $query = Admission::with(['applicant', 'interviewSchedule'])
            ->whereHas('applicant', function ($query) {
                $query->where('status', 'interview');
            });;
        
        if ($levelId) {
            $level = Level::find($levelId);
            $query->whereHas('applicant', function($q) use ($level) {
                $q->where('year_level', $level->description);
            });
        }
        
        $applicants = $query->get();

        $pdf = Pdf::loadView('pdf.print_interview_list', compact('applicants', 'level'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->stream('interview_list_' . date('Y-m-d') . '.pdf');
    }

    public function printExamList(Request $request)
    {
        $levelId = $request->query('level_id');
        $level = null;
        
        $query = Admission::with(['applicant', 'examSchedule'])
            ->whereHas('applicant', function ($query) {
                $query->where('status', 'exam');
            });;
        if ($levelId) {
            $level = Level::find($levelId);
            $query->whereHas('applicant', function($q) use ($level) {
                $q->where('year_level', $level->description);
            });
        }
        
        $applicants = $query->get();

        $pdf = Pdf::loadView('pdf.print_exam_list', compact('applicants', 'level'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->stream('exam_list_' . date('Y-m-d') . '.pdf');
    }

    public function printEvaluationList(Request $request)
    {
        $levelId = $request->query('level_id');
        $level = null;
        
        $query = Admission::with(['applicant', 'evaluationSchedule'])
            ->whereHas('applicant', function ($query) {
                $query->where('status', 'evaluation');
            });;
        
        if ($levelId) {
            $level = Level::find($levelId);
            $query->whereHas('applicant', function($q) use ($level) {
                $q->where('year_level', $level->description);
            });
        }
        
        $applicants = $query->get();

        $pdf = Pdf::loadView('pdf.print_evaluation_list', compact('applicants', 'level'))
            ->setPaper('a4', 'landscape');
        
        return $pdf->stream('evaluation_list_' . date('Y-m-d') . '.pdf');
    }

    public function printScheduleApplicants($id)
    {
        $schedule = Schedule::where('id', $id)->first();
        $scheduleType = $schedule->process;

        $applicants = Applicant::whereHas('admission', function($q) use ($id, $scheduleType) {
                if ($scheduleType == 'interview') {
                    $q->where('interview_schedule_id', $id);
                } elseif ($scheduleType == 'exam') {
                    $q->where('exam_schedule_id', $id);
                } elseif ($scheduleType == 'evaluation') {
                    $q->where('evaluation_schedule_id', $id);
                }
        })->get();

        $pdf = Pdf::loadView('pdf.print_schedule_applicants', compact('schedule', 'applicants'));
        
        return $pdf->stream('schedule_applicants_' . date('Y-m-d') . '.pdf');
    }

    public function printSubjectOfferings(Request $request)
    {
        $departmentId = $request->user()->department_id;
        $academicTermId = $request->query('academic_term_id');

        $academicTerm = AcademicTerm::findOrFail($academicTermId);
        $department = Department::findOrFail($departmentId);

        $subjectOfferings = SubjectOffering::where('academic_term_id', $academicTermId)
            ->where('department_id', $departmentId)
            ->with('subject')
            ->withCount('enlistments')
            ->orderBy('code')
            ->get();

        $pdf = Pdf::loadView('pdf.print_subject_offerings', compact('subjectOfferings', 'academicTerm', 'department'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('subject_offerings_' . date('Y-m-d') . '.pdf');
    }

    public function printStudentAssessment(Request $request, $studentId)
    {
        $academicTermId = $request->query('academic_term_id');

        $student = Student::with(['department', 'program', 'level', 'contact'])->findOrFail($studentId);
        $academicTerm = $academicTermId ? AcademicTerm::find($academicTermId) : null;

        // Get enlistments
        $enlistments = collect();
        if ($academicTermId) {
            $enlistments = Enlistment::with(['subjectOffering.subject'])
                ->where('student_id', $studentId)
                ->where('academic_term_id', $academicTermId)
                ->get();
        }

        // Get fees grouped
        $fees = ['major' => collect(), 'other' => collect(), 'additional' => collect()];
        if ($academicTermId) {
            $studentFees = StudentFee::with(['fee'])
                ->where('student_id', $studentId)
                ->where('academic_term_id', $academicTermId)
                ->get();

            foreach ($studentFees as $sf) {
                $fee = $sf->fee;
                if ($fee) {
                    $group = $fee->group ?? 'other';
                    if (isset($fees[$group])) {
                        $fees[$group]->push($fee);
                    }
                }
            }
        }

        // Create assessment history record (only for new prints, not reprints)
        $isReprint = $request->query('reprint', false);
        if ($academicTermId && !$isReprint) {
            $assessmentHistory = AssessmentHistory::create([
                'student_id' => $studentId,
                'academic_term_id' => $academicTermId,
                'date_printed' => now(),
            ]);

            // Save student info snapshot
            AssessmentHistoryStudent::create([
                'assessment_history_id' => $assessmentHistory->id,
                'student_number' => $student->student_number,
                'name' => "{$student->last_name}, {$student->first_name} {$student->middle_name}",
                'year_level' => $student->level->description ?? '-',
                'program' => $student->program->code ?? '-',
                'department' => $student->department->code ?? '-',
            ]);

            // Save enlistment snapshot
            foreach ($enlistments as $enlistment) {
                AssessmentHistoryEnlistment::create([
                    'assessment_history_id' => $assessmentHistory->id,
                    'code' => $enlistment->subjectOffering->code ?? '-',
                    'description' => $enlistment->subjectOffering->description ?? '-',
                    'units' => $enlistment->subjectOffering->subject->unit ?? 0,
                ]);
            }

            // Save fees snapshot
            foreach ($fees as $feeType => $feeGroup) {
                foreach ($feeGroup as $fee) {
                    AssessmentHistoryFee::create([
                        'assessment_history_id' => $assessmentHistory->id,
                        'type' => $feeType,
                        'description' => $fee->description,
                        'amount' => $fee->amount,
                    ]);
                }
            }
        }

        $pdf = Pdf::loadView('pdf.print_student_assessment', compact('student', 'academicTerm', 'enlistments', 'fees'));

        return $pdf->stream('student_assessment_' . $student->student_number . '_' . date('Y-m-d') . '.pdf');
    }

    // ── Cashier PDF Methods ──────────────────────────────────────────
    public function printDailyTransactions(Request $request)
    {
        $date = $request->query('date', date('Y-m-d'));
        $cashierId = auth()->id();
        $cashierName = auth()->user()->name;

        $transactions = Transaction::with(['student', 'academicTerm', 'cashier', 'paymentAccount', 'paymentType'])
            ->whereDate('date', $date)
            ->where('cashier_id', $cashierId)
            ->orderBy('created_at')
            ->get();

        $totalAmount = $transactions->sum('amount');

        $pdf = Pdf::loadView('pdf.daily_transactions', compact('transactions', 'date', 'cashierName', 'totalAmount'))
            ->setPaper('a4', 'portrait');
        
        return $pdf->stream('daily_transactions_' . $date . '.pdf');
    }

    public function printSalesInvoice($id)
    {
        $transaction = Transaction::with(['student', 'academicTerm', 'cashier', 'paymentAccount', 'paymentType'])->findOrFail($id);
        $cashierName = $transaction->cashier->name ?? 'N/A';

        $pdf = Pdf::loadView('pdf.sales_invoice', compact('transaction', 'cashierName'))
            ->setPaper([0, 0, 288, 432], 'portrait'); // 4x6 inches (72 points per inch)
        
        return $pdf->stream('sales_invoice_' . $transaction->or_number . '.pdf');
    }

    /**
     * Print examination permit for a student.
     */
    public function printExaminationPermit($studentId)
    {
        $student = Student::with(['account', 'program', 'department', 'level'])->findOrFail($studentId);
        
        $pdf = Pdf::loadView('pdf.examination_permit', compact('student'))
            ->setPaper('a4', 'portrait');
        
        return $pdf->stream('examination_permit_' . $student->student_number . '.pdf');
    }

    // ── Fee Ledger PDF Method ────────────────────────────────────────
    public function printFeeLedger($id)
    {
        // Find the fee with its relationships
        $fee = Fee::with(['academicTerm', 'program'])->findOrFail($id);

        // Get all students who have this fee through StudentFee
        $studentFees = StudentFee::with(['student', 'student.program', 'student.level'])
            ->where('fee_id', $id)
            ->get();

        // Check if this is a unit fee
        $isUnitFee = strtolower($fee->description) === 'unit fee';
        $grandTotal = 0;

        // Calculate totals - for unit fees, we need to get each student's total units
        foreach ($studentFees as $studentFee) {
            if ($isUnitFee) {
                // Get student's enlistments for this academic term and calculate total units
                $totalUnits = Enlistment::where('student_id', $studentFee->student_id)
                    ->where('academic_term_id', $fee->academic_term_id)
                    ->with('subjectOffering.subject')
                    ->get()
                    ->sum(function ($enlistment) {
                        return $enlistment->subjectOffering->subject->unit ?? 0;
                    });
                
                $studentFee->total_units = $totalUnits;
                $studentFee->calculated_amount = $totalUnits * $fee->amount;
                $grandTotal += $studentFee->calculated_amount;
            } else {
                $studentFee->total_units = null;
                $studentFee->calculated_amount = $fee->amount;
                $grandTotal += $fee->amount;
            }
        }

        $pdf = Pdf::loadView('pdf.print_fee_ledger', [
            'fee' => $fee,
            'studentFees' => $studentFees,
            'grandTotal' => $grandTotal,
            'isUnitFee' => $isUnitFee,
        ]);
        
        return $pdf->stream('fee_ledger_' . $fee->id . '_' . date('Y-m-d') . '.pdf');
    }

    /**
     * Print the class list for a specific subject offering.
     */
    public function printClassList($id)
    {
        $subjectOffering = SubjectOffering::with(['subject', 'program', 'program.levels', 'academicTerm'])
            ->findOrFail($id);

        // Get all enlistments for this subject offering with student info
        $enlistments = Enlistment::where('subject_offering_id', $id)
            ->with(['student', 'student.level', 'student.program'])
            ->get();

        // Separate students by sex
        $femaleStudents = $enlistments->filter(function ($enlistment) {
            return strtolower($enlistment->student->sex ?? '') === 'female';
        })->sortBy(function ($enlistment) {
            return $enlistment->student->last_name . $enlistment->student->first_name;
        });

        $maleStudents = $enlistments->filter(function ($enlistment) {
            return strtolower($enlistment->student->sex ?? '') === 'male';
        })->sortBy(function ($enlistment) {
            return $enlistment->student->last_name . $enlistment->student->first_name;
        });

        // Add OR numbers
        $femaleStudents = $this->addOrNumberToStudents($femaleStudents);
        $maleStudents = $this->addOrNumberToStudents($maleStudents);

        $hasTuitionFees = $femaleStudents->contains(fn($e) => $e->or_number !== null) ||
                          $maleStudents->contains(fn($e) => $e->or_number !== null);

        $yearLevel = $subjectOffering->program?->levels?->first()?->description ?? 'N/A';

        $pdf = Pdf::loadView('pdf.classlist', [
            'subjectOffering' => $subjectOffering,
            'femaleStudents' => $femaleStudents,
            'maleStudents' => $maleStudents,
            'hasTuitionFees' => $hasTuitionFees,
            'yearLevel' => $yearLevel,
        ]);

        return $pdf->stream('classlist_' . $subjectOffering->code . '.pdf');
    }

    /**
     * Add OR number column to students who have tuition fee transactions.
     */
    private function addOrNumberToStudents($students)
    {
        return $students->map(function ($enlistment) {
            $latestTuitionTransaction = Transaction::with('paymentType')
                ->where('student_id', $enlistment->student->id)
                ->whereHas('paymentType', function ($query) {
                    $query->where('description', 'like', '%tuition%');
                })
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            $enlistment->or_number = $latestTuitionTransaction?->or_number;
            return $enlistment;
        });
    }
}
