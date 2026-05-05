<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Teacher;
use App\Models\TeacherAccount;
use App\Models\Enlistment;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\Grade;
use App\Models\GradeColumn;
use App\Models\RawScore;
use App\Models\TeacherOffering;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TeacherAuthController extends Controller
{
    /**
     * Get the currently logged in teacher from session.
     */
    protected function getLoggedInTeacher(): ?Teacher
    {
        $teacherId = session('teacher_portal_teacher_id');
        if (!$teacherId) {
            return null;
        }

        $teacher = Teacher::with('account')->find($teacherId);

        if (!$teacher) {
            session()->forget('teacher_portal_teacher_id');
            return null;
        }

        if ($teacher->status !== 'active') {
            session()->forget('teacher_portal_teacher_id');
            return null;
        }

        if (!$teacher->account || $teacher->account->status !== 'on') {
            session()->forget('teacher_portal_teacher_id');
            return null;
        }

        return $teacher;
    }

    public function showLogin()
    {
        $teacher = $this->getLoggedInTeacher();

        if ($teacher) {
            return redirect()->route('teacher_portal.dashboard');
        }

        return view('teacher_portal.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'password' => 'required|string',
        ]);

        $code = trim($validated['code']);

        $teacher = Teacher::with('account')->where('code', $code)->first();

        if (!$teacher) {
            throw ValidationException::withMessages([
                'code' => 'Teacher code not found.',
            ]);
        }

        if ($teacher->status !== 'active') {
            throw ValidationException::withMessages([
                'code' => 'Your account is inactive. Please contact the registrar.',
            ]);
        }

        /** @var TeacherAccount|null $account */
        $account = $teacher->account;

        if (!$account) {
            throw ValidationException::withMessages([
                'code' => 'No account found for this teacher. Please contact the registrar.',
            ]);
        }

        if ($account->status !== 'on') {
            throw ValidationException::withMessages([
                'code' => 'Your teacher portal account is closed. Please contact the registrar.',
            ]);
        }

        if (!Hash::check($validated['password'], $account->password)) {
            throw ValidationException::withMessages([
                'password' => 'Invalid password.',
            ]);
        }

        $request->session()->regenerate();
        session(['teacher_portal_teacher_id' => $teacher->id]);

        return redirect()->route('teacher_portal.dashboard');
    }

    public function logout(Request $request)
    {
        session()->forget('teacher_portal_teacher_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('teacher_portal.login');
    }

    public function showDashboard()
    {
        $teacher = $this->getLoggedInTeacher();

        if (!$teacher) {
            return redirect()->route('teacher_portal.login');
        }

        return view('teacher_portal.dashboard', compact('teacher'));
    }

    public function showSubjectLoad(Request $request)
    {
        $teacher = $this->getLoggedInTeacher();

        if (!$teacher) {
            return redirect()->route('teacher_portal.login');
        }

        $departmentIds = TeacherOffering::query()
            ->where('teacher_id', $teacher->id)
            ->join('subject_offerings', 'teacher_offerings.offering_id', '=', 'subject_offerings.id')
            ->whereNotNull('subject_offerings.department_id')
            ->select('subject_offerings.department_id')
            ->distinct()
            ->pluck('subject_offerings.department_id');

        $academicTerms = collect();
        if ($departmentIds->isNotEmpty()) {
            $academicTerms = AcademicTerm::query()
                ->where('status', 'active')
                ->whereIn('department_id', $departmentIds)
                ->orderBy('created_at')
                ->get();
        }

        $academicTermId = $request->query('academic_term_id');
        $academicTerm = null;
        if ($academicTermId) {
            $academicTerm = $academicTerms->firstWhere('id', (int) $academicTermId);
        }

        $teacherOfferings = collect();
        if ($academicTerm) {
            $teacherOfferings = TeacherOffering::query()
                ->where('teacher_id', $teacher->id)
                ->where('academic_term_id', $academicTerm->id)
                ->with([
                    'offering.subject',
                    'offering.program',
                    'offering.level',
                    'academicTerm',
                ])
                ->orderBy('created_at')
                ->get();
        }

        return view('teacher_portal.subject_load', [
            'teacher' => $teacher,
            'academicTerms' => $academicTerms,
            'academicTerm' => $academicTerm,
            'teacherOfferings' => $teacherOfferings,
        ]);
    }

    public function showChangePassword()
    {
        $teacher = $this->getLoggedInTeacher();
        if (!$teacher) {
            return redirect()->route('teacher_portal.login');
        }
        return view('teacher_portal.change_password', compact('teacher'));
    }

    public function changePassword(Request $request)
    {
        $teacher = $this->getLoggedInTeacher();
        if (!$teacher) {
            return redirect()->route('teacher_portal.login');
        }

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $account = $teacher->account;
        if (!$account) {
            return redirect()->route('teacher_portal.change_password')->withErrors(['current_password' => 'No account found for this teacher.']);
        }

        if (!\Illuminate\Support\Facades\Hash::check($validated['current_password'], $account->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect.',
            ]);
        }

        $account->password = $validated['new_password'];
        $account->save();

        return redirect()->route('teacher_portal.change_password')
            ->with('success', 'Password changed successfully.');
    }

    public function showClassList()
    {
        $teacher = $this->getLoggedInTeacher();
        if (!$teacher) {
            return redirect()->route('teacher_portal.login');
        }

        $departmentIds = TeacherOffering::query()
            ->where('teacher_id', $teacher->id)
            ->join('subject_offerings', 'teacher_offerings.offering_id', '=', 'subject_offerings.id')
            ->whereNotNull('subject_offerings.department_id')
            ->select('subject_offerings.department_id')
            ->distinct()
            ->pluck('subject_offerings.department_id');

        $academicTerms = collect();
        if ($departmentIds->isNotEmpty()) {
            $academicTerms = AcademicTerm::query()
                ->where('status', 'active')
                ->whereIn('department_id', $departmentIds)
                ->orderBy('created_at')
                ->get();
        }

        return view('teacher_portal.class_list', [
            'teacher' => $teacher,
            'academicTerms' => $academicTerms,
        ]);
    }

    public function getSubjectOfferings(Request $request, $academicTermId)
    {
        $teacher = $this->getLoggedInTeacher();
        if (!$teacher) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $offerings = TeacherOffering::query()
            ->where('teacher_id', $teacher->id)
            ->where('academic_term_id', $academicTermId)
            ->with(['offering.subject', 'offering.program', 'offering.level'])
            ->orderBy('created_at')
            ->get()
            ->map(function ($to) {
                return [
                    'id' => $to->id,
                    'offering_id' => $to->offering_id,
                    'code' => $to->offering->code ?? '',
                    'subject_name' => $to->offering->subject?->name ?? '',
                    'program_name' => $to->offering->program?->name ?? '',
                    'level_name' => $to->offering->level?->name ?? '',
                ];
            });

        return response()->json(['offerings' => $offerings]);
    }

    public function getClassList(Request $request, $teacherOfferingId)
    {
        $teacher = $this->getLoggedInTeacher();
        if (!$teacher) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $teacherOffering = TeacherOffering::query()
            ->where('id', $teacherOfferingId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $studentIds = Enlistment::query()
            ->where('subject_offering_id', $teacherOffering->offering_id)
            ->where('academic_term_id', $teacherOffering->academic_term_id)
            ->pluck('student_id')
            ->unique()
            ->values();

        $eligibleStudentIds = $this->getDownPaymentStudentIdsFromTransactions($studentIds, (int) $teacherOffering->academic_term_id);

        $students = Student::query()
            ->whereIn('id', $eligibleStudentIds)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($s) {
                $name = trim($s->last_name . ', ' . $s->first_name . ' ' . ($s->middle_name ? substr($s->middle_name, 0, 1) . '.' : ''));
                return [
                    'id' => $s->id,
                    'student_number' => $s->student_number,
                    'student_name' => $name,
                ];
            })->values();

        return response()->json(['students' => $students]);
    }

    /**
     * Print class list PDF for a teacher offering (teacher portal).
     */
    public function printClassList(Request $request, $teacherOfferingId)
    {
        $teacher = $this->getLoggedInTeacher();

        if (!$teacher) {
            return redirect()->route('teacher_portal.login');
        }

        $teacherOffering = TeacherOffering::query()
            ->where('id', $teacherOfferingId)
            ->where('teacher_id', $teacher->id)
            ->with([
                'offering.subject',
                'offering.program',
                'offering.program.levels',
                'offering.academicTerm',
            ])
            ->firstOrFail();

        $subjectOffering = $teacherOffering->offering;
        if (!$subjectOffering) {
            abort(404);
        }

        $enlistedStudentIds = Enlistment::query()
            ->where('subject_offering_id', $subjectOffering->id)
            ->where('academic_term_id', $teacherOffering->academic_term_id)
            ->pluck('student_id')
            ->unique()
            ->values();

        $eligibleStudentIds = $this->getDownPaymentStudentIdsFromTransactions($enlistedStudentIds, (int) $teacherOffering->academic_term_id);

        $enlistments = collect();
        if ($eligibleStudentIds->isNotEmpty()) {
            $enlistments = Enlistment::query()
                ->where('subject_offering_id', $subjectOffering->id)
                ->where('academic_term_id', $teacherOffering->academic_term_id)
                ->whereIn('student_id', $eligibleStudentIds)
                ->with(['student', 'student.level', 'student.program'])
                ->get();
        }

        $femaleStudents = $enlistments->filter(function ($enlistment) {
            return strtolower($enlistment->student->sex ?? '') === 'female';
        })->sortBy(function ($enlistment) {
            return ($enlistment->student->last_name ?? '') . ($enlistment->student->first_name ?? '');
        });

        $maleStudents = $enlistments->filter(function ($enlistment) {
            return strtolower($enlistment->student->sex ?? '') === 'male';
        })->sortBy(function ($enlistment) {
            return ($enlistment->student->last_name ?? '') . ($enlistment->student->first_name ?? '');
        });

        $femaleStudents = $this->addOrNumberToStudents($femaleStudents, (int) $teacherOffering->academic_term_id);
        $maleStudents = $this->addOrNumberToStudents($maleStudents, (int) $teacherOffering->academic_term_id);

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

    public function showInputGrade($teacherOfferingId)
    {
        $teacher = $this->getLoggedInTeacher();

        if (!$teacher) {
            return redirect()->route('teacher_portal.login');
        }

        $teacherOffering = TeacherOffering::query()
            ->where('id', $teacherOfferingId)
            ->where('teacher_id', $teacher->id)
            ->with([
                'offering.subject',
                'offering.program',
                'offering.level',
                'offering.gradingSystem',
                'academicTerm',
            ])
            ->firstOrFail();

        $academicTermType = $teacherOffering->academicTerm->type;
        $periods = $academicTermType === 'semester' ? ['Prelim', 'Midterm', 'Semi-Final', 'Final'] : ($academicTermType === 'full year' ? ['Quarter 1', 'Quarter 2', 'Quarter 3', 'Quarter 4'] : ['Final']);

        $components = $teacherOffering->offering->gradingSystem
            ->components()
            ->orderBy('created_at')
            ->get() ?? collect();

        return view('teacher_portal.input_grade', [
            'teacher' => $teacher,
            'teacherOffering' => $teacherOffering,
            'periods' => $periods,
            'components' => $components,
        ]);
    }

    public function getStudentGrades(Request $request, $teacherOfferingId)
    {
        $teacher = $this->getLoggedInTeacher();

        if (!$teacher) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $teacherOffering = TeacherOffering::query()
            ->where('id', $teacherOfferingId)
            ->where('teacher_id', $teacher->id)
            ->with('offering.gradingSystem.components')
            ->firstOrFail();

        $period = $request->query('period');
        if (!$period) {
            return response()->json(['error' => 'Period is required'], 400);
        }

        // Fetch enrolled student IDs for this subject offering + academic term
        $studentIds = Enlistment::query()
            ->where('subject_offering_id', $teacherOffering->offering_id)
            ->where('academic_term_id', $teacherOffering->academic_term_id)
            ->pluck('student_id')
            ->unique()
            ->values();

        // Ensure a Grade record exists for each student for this teacher offering + period
        foreach ($studentIds as $studentId) {
            Grade::firstOrCreate([
                'teacher_offering_id' => $teacherOfferingId,
                'student_id' => $studentId,
                'period' => $period,
            ], [
                'status' => 'draft',
            ]);
        }

        $grades = Grade::query()
            ->with('student')
            ->where('teacher_offering_id', $teacherOfferingId)
            ->where('period', $period)
            ->orderBy('created_at')
            ->get();

        $students = $grades->map(function ($grade) {
            $student = $grade->student;
            $studentName = trim($student->last_name . ', ' . $student->first_name . ' ' . ($student->middle_name ? substr($student->middle_name, 0, 1) . '.' : ''));

            return [
                'grade_id' => $grade->id,
                'student_id' => $student->id,
                'student_number' => $student->student_number,
                'student_name' => $studentName,
                'initial_grade' => $grade->initial_grade,
                'period_grade' => $grade->period_grade,
                'status' => $grade->status,
            ];
        });

        $components = $teacherOffering->offering->gradingSystem?->components ?? collect();

        $columns = GradeColumn::query()
            ->with('component')
            ->where('teacher_offering_id', $teacherOfferingId)
            ->where('period', $period)
            ->orderBy('column_number')
            ->get()
            ->map(function ($col) {
                return [
                    'id' => $col->id,
                    'component_id' => $col->component_id,
                    'component_code' => $col->component?->code ?? '',
                    'column_number' => $col->column_number,
                    'highest_score' => $col->highest_score,
                    'period' => $col->period,
                ];
            });

        $rawScores = RawScore::query()
            ->whereIn('grade_id', $grades->pluck('id'))
            ->whereIn('grade_column_id', $columns->pluck('id'))
            ->get()
            ->map(function ($rawScore) {
                return [
                    'id' => $rawScore->id,
                    'grade_id' => $rawScore->grade_id,
                    'grade_column_id' => $rawScore->grade_column_id,
                    'score' => $rawScore->score,
                ];
            });

        return response()->json([
            'period' => $period,
            'students' => $students,
            'components' => $components->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'description' => $c->description ?? null,
                'percentage' => $c->percentage ?? null,
            ])->values(),
            'columns' => $columns,
            'rawScores' => $rawScores,
        ]);
    }

    public function getComponentColumns(Request $request, $teacherOfferingId)
    {
        $teacher = $this->getLoggedInTeacher();

        if (!$teacher) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $teacherOffering = TeacherOffering::query()
            ->where('id', $teacherOfferingId)
            ->where('teacher_id', $teacher->id)
            ->firstOrFail();

        $columns = GradeColumn::query()
            ->with('component')
            ->where('teacher_offering_id', $teacherOfferingId)
            ->where('period', $request->query('period'))
            ->orderBy('column_number')
            ->get()
            ->map(function ($col) {
                return [
                    'id' => $col->id,
                    'component_id' => $col->component_id,
                    'component_code' => $col->component?->code ?? '',
                    'column_number' => $col->column_number,
                    'highest_score' => $col->highest_score,
                    'period' => $col->period,
                ];
            });

        return response()->json([
            'columns' => $columns,
        ]);
    }

    /**
     * Get student IDs that have a Down Payment transaction in the selected academic term.
     */
    private function getDownPaymentStudentIdsFromTransactions($studentIds, int $academicTermId)
    {
        $studentIds = collect($studentIds)->filter()->unique()->values();
        if ($studentIds->isEmpty()) {
            return collect();
        }

        return Transaction::query()
            ->join('payment_accounts', 'transactions.description_id', '=', 'payment_accounts.id')
            ->where('transactions.academic_term_id', $academicTermId)
            ->whereIn('transactions.student_id', $studentIds)
            ->whereRaw('LOWER(payment_accounts.description) IN (?, ?)', ['down payment', 'downpayment'])
            ->distinct()
            ->pluck('transactions.student_id')
            ->values();
    }

    /**
     * Add OR number column to students who have tuition fee transactions.
     */
    private function addOrNumberToStudents($students, int $academicTermId)
    {
        return $students->map(function ($enlistment) use ($academicTermId) {
            $latestTuitionTransaction = Transaction::with('paymentType')
                ->where('student_id', $enlistment->student->id)
                ->where('academic_term_id', $academicTermId)
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
