<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Teacher;
use App\Models\TeacherAccount;
use App\Models\TeacherOffering;
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
                'academicTerm',
            ])
            ->firstOrFail();

        return view('teacher_portal.input_grade', [
            'teacher' => $teacher,
            'teacherOffering' => $teacherOffering,
        ]);
    }
}
