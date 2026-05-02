<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use App\Models\Department;
use App\Models\Enlistment;
use App\Models\Grade;
use App\Models\Student;
use App\Models\SubjectOffering;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class RegistrarGradeReportController extends Controller
{
    public function getAcademicTerms(Request $request)
    {
        $validated = $request->validate([
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ]);

        $academicTerms = AcademicTerm::query()
            ->where('status', 'active')
            ->where('department_id', $validated['department_id'])
            ->orderBy('created_at')
            ->get(['id', 'code', 'description', 'type']);

        return response()->json($academicTerms);
    }

    public function getSubjectOfferings(Request $request)
    {
        $validated = $request->validate([
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'academic_term_id' => ['required', 'integer', 'exists:academic_terms,id'],
        ]);

        $subjectOfferings = SubjectOffering::query()
            ->where('department_id', $validated['department_id'])
            ->where('academic_term_id', $validated['academic_term_id'])
            ->with('subject')
            ->orderBy('code')
            ->get()
            ->map(function ($offering) {
                return [
                    'id' => $offering->id,
                    'code' => $offering->code,
                    'description' => $offering->description,
                    'subject_code' => $offering->subject?->code,
                    'subject_description' => $offering->subject?->description,
                ];
            });

        return response()->json($subjectOfferings);
    }

    public function getPeriods(Request $request)
    {
        $validated = $request->validate([
            'academic_term_id' => ['required', 'integer', 'exists:academic_terms,id'],
        ]);

        $academicTerm = AcademicTerm::query()->findOrFail($validated['academic_term_id']);

        return response()->json([
            'academic_term_id' => $academicTerm->id,
            'academic_term_type' => $academicTerm->type,
            'periods' => self::periodOptionsForAcademicTerm($academicTerm),
        ]);
    }

    public function index(Request $request)
    {
        $departmentId = $request->query('department_id');
        $academicTermId = $request->query('academic_term_id');
        $subjectOfferingId = $request->query('subject_offering_id');
        $period = $request->query('period');

        $departments = Department::query()
            ->where('status', 'active')
            ->orderBy('description')
            ->get(['id', 'code', 'description']);

        $academicTerms = collect();
        if ($departmentId) {
            $academicTerms = AcademicTerm::query()
                ->where('status', 'active')
                ->where('department_id', $departmentId)
                ->orderBy('created_at')
                ->get(['id', 'code', 'description', 'type', 'department_id']);
        }

        $academicTerm = null;
        if ($academicTermId) {
            $academicTerm = AcademicTerm::query()->find($academicTermId);
        }

        $periodOptions = $academicTerm ? self::periodOptionsForAcademicTerm($academicTerm) : [];

        $subjectOfferings = collect();
        if ($departmentId && $academicTermId) {
            $subjectOfferings = SubjectOffering::query()
                ->where('department_id', $departmentId)
                ->where('academic_term_id', $academicTermId)
                ->with('subject')
                ->orderBy('code')
                ->get();
        }

        $selectedSubjectOffering = null;
        if ($subjectOfferingId) {
            $selectedSubjectOffering = SubjectOffering::query()
                ->with(['subject', 'department', 'academicTerm'])
                ->find($subjectOfferingId);
        }

        $students = collect();
        if ($departmentId && $academicTermId && $subjectOfferingId && $period) {
            $subjectOffering = SubjectOffering::query()
                ->where('id', $subjectOfferingId)
                ->where('department_id', $departmentId)
                ->where('academic_term_id', $academicTermId)
                ->first();

            if ($subjectOffering) {
                $studentIds = Enlistment::query()
                    ->select('student_id')
                    ->where('academic_term_id', $academicTermId)
                    ->where('subject_offering_id', $subjectOfferingId);

                $students = Student::query()
                    ->whereIn('id', $studentIds)
                    ->with(['department', 'program', 'level'])
                    ->orderBy('last_name')
                    ->orderBy('first_name')
                    ->get();
            }
        }

        return view('registrar.grade_report', [
            'departments' => $departments,
            'academicTerms' => $academicTerms,
            'subjectOfferings' => $subjectOfferings,
            'periodOptions' => $periodOptions,
            'selectedDepartmentId' => $departmentId,
            'selectedAcademicTermId' => $academicTermId,
            'selectedSubjectOfferingId' => $subjectOfferingId,
            'selectedPeriod' => $period,
            'selectedSubjectOffering' => $selectedSubjectOffering,
            'academicTerm' => $academicTerm,
            'students' => $students,
        ]);
    }

    public function view(Request $request, $studentId)
    {
        $validated = $request->validate([
            'academic_term_id' => ['required', 'integer', 'exists:academic_terms,id'],
            'period' => ['required', 'string'],
        ]);

        $student = Student::query()
            ->with(['department', 'program', 'level'])
            ->findOrFail($studentId);

        $academicTerm = AcademicTerm::query()->findOrFail($validated['academic_term_id']);

        $report = $this->buildReportForStudent($student, $academicTerm, $validated['period']);

        return view('registrar.grade_report_view', [
            'student' => $student,
            'academicTerm' => $academicTerm,
            'period' => $validated['period'],
            'rows' => $report['rows'],
            'generalAverage' => $report['general_average'],
            'totalUnits' => $report['total_units'],
        ]);
    }

    public function pdf(Request $request, $studentId)
    {
        $validated = $request->validate([
            'academic_term_id' => ['required', 'integer', 'exists:academic_terms,id'],
            'period' => ['required', 'string'],
        ]);

        $student = Student::query()
            ->with(['department', 'program', 'level'])
            ->findOrFail($studentId);

        $academicTerm = AcademicTerm::query()->findOrFail($validated['academic_term_id']);

        $report = $this->buildReportForStudent($student, $academicTerm, $validated['period']);

        $pdf = Pdf::loadView('pdf.grade_reports', [
            'reports' => [[
                'student' => $student,
                'academicTerm' => $academicTerm,
                'period' => $validated['period'],
                'rows' => $report['rows'],
                'generalAverage' => $report['general_average'],
                'totalUnits' => $report['total_units'],
            ]],
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('grade_report_' . $student->student_number . '_' . date('Y-m-d') . '.pdf');
    }

    public function pdfAll(Request $request)
    {
        $validated = $request->validate([
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'academic_term_id' => ['required', 'integer', 'exists:academic_terms,id'],
            'subject_offering_id' => ['required', 'integer', 'exists:subject_offerings,id'],
            'period' => ['required', 'string'],
        ]);

        $academicTerm = AcademicTerm::query()
            ->where('id', $validated['academic_term_id'])
            ->where('department_id', $validated['department_id'])
            ->firstOrFail();

        $subjectOffering = SubjectOffering::query()
            ->where('id', $validated['subject_offering_id'])
            ->where('department_id', $validated['department_id'])
            ->where('academic_term_id', $academicTerm->id)
            ->firstOrFail();

        $studentIds = Enlistment::query()
            ->select('student_id')
            ->where('academic_term_id', $academicTerm->id)
            ->where('subject_offering_id', $subjectOffering->id);

        $students = Student::query()
            ->whereIn('id', $studentIds)
            ->with(['department', 'program', 'level'])
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $reports = $this->buildReportsForStudents($students, $academicTerm, $validated['period']);

        $pdf = Pdf::loadView('pdf.grade_reports', [
            'reports' => $reports,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('grade_reports_' . date('Y-m-d') . '.pdf');
    }

    private static function periodOptionsForAcademicTerm(AcademicTerm $academicTerm): array
    {
        $academicTermType = strtolower(trim((string) $academicTerm->type));

        if ($academicTermType === 'semester') {
            return ['Prelim', 'Midterm', 'Semi-Final', 'Final'];
        }

        if ($academicTermType === 'full year') {
            return ['Quarter 1', 'Quarter 2', 'Quarter 3', 'Quarter 4'];
        }

        return ['Final'];
    }

    private function buildReportForStudent(Student $student, AcademicTerm $academicTerm, string $period): array
    {
        $enlistments = Enlistment::query()
            ->where('student_id', $student->id)
            ->where('academic_term_id', $academicTerm->id)
            ->with(['subjectOffering.subject'])
            ->get();

        $grades = Grade::query()
            ->where('student_id', $student->id)
            ->where('period', $period)
            ->where('status', 'approved')
            ->whereHas('teacherOffering', function ($query) use ($academicTerm) {
                $query->where('academic_term_id', $academicTerm->id);
            })
            ->with(['teacherOffering:id,offering_id,academic_term_id'])
            ->get();

        $gradeMap = $grades
            ->filter(fn ($grade) => $grade->teacherOffering)
            ->keyBy(fn ($grade) => $grade->teacherOffering->offering_id);

        return $this->buildReportRows($enlistments, $gradeMap);
    }

    private function buildReportsForStudents(Collection $students, AcademicTerm $academicTerm, string $period): array
    {
        if ($students->isEmpty()) {
            return [];
        }

        $studentIds = $students->pluck('id')->values();

        $enlistmentsByStudent = Enlistment::query()
            ->whereIn('student_id', $studentIds)
            ->where('academic_term_id', $academicTerm->id)
            ->with(['subjectOffering.subject'])
            ->get()
            ->groupBy('student_id');

        $grades = Grade::query()
            ->whereIn('student_id', $studentIds)
            ->where('period', $period)
            ->where('status', 'approved')
            ->whereHas('teacherOffering', function ($query) use ($academicTerm) {
                $query->where('academic_term_id', $academicTerm->id);
            })
            ->with(['teacherOffering:id,offering_id,academic_term_id'])
            ->get();

        $gradesByStudent = $grades
            ->filter(fn ($grade) => $grade->teacherOffering)
            ->groupBy('student_id')
            ->map(function ($studentGrades) {
                return $studentGrades->keyBy(fn ($grade) => $grade->teacherOffering->offering_id);
            });

        $reports = [];
        foreach ($students as $student) {
            $enlistments = $enlistmentsByStudent->get($student->id, collect());
            $gradeMap = $gradesByStudent->get($student->id, collect());

            $report = $this->buildReportRows($enlistments, $gradeMap);

            $reports[] = [
                'student' => $student,
                'academicTerm' => $academicTerm,
                'period' => $period,
                'rows' => $report['rows'],
                'generalAverage' => $report['general_average'],
                'totalUnits' => $report['total_units'],
            ];
        }

        return $reports;
    }

    private function buildReportRows(Collection $enlistments, Collection $gradeMap): array
    {
        $rows = $enlistments
            ->map(function ($enlistment) use ($gradeMap) {
                $offering = $enlistment->subjectOffering;
                $subject = $offering?->subject;

                $grade = $offering ? $gradeMap->get($offering->id) : null;
                $gradeValue = $grade ? ($grade->period_grade ?? $grade->initial_grade) : null;

                return [
                    'subject_code' => $subject?->code ?? ($offering?->code ?? '—'),
                    'description' => $subject?->description ?? ($offering?->description ?? '—'),
                    'units' => (float) ($subject?->unit ?? 0),
                    'grade' => $gradeValue !== null ? (float) $gradeValue : null,
                ];
            })
            ->sortBy(fn ($row) => $row['subject_code'])
            ->values();

        $totalUnits = $rows->sum('units');

        $weightedGradeSum = 0.0;
        $weightedUnitsSum = 0.0;
        foreach ($rows as $row) {
            if ($row['grade'] === null) {
                continue;
            }

            $units = (float) ($row['units'] ?? 0);
            if ($units <= 0) {
                $units = 1.0;
            }

            $weightedUnitsSum += $units;
            $weightedGradeSum += ((float) $row['grade']) * $units;
        }

        $generalAverage = $weightedUnitsSum > 0
            ? round($weightedGradeSum / $weightedUnitsSum, 2)
            : null;

        return [
            'rows' => $rows,
            'general_average' => $generalAverage,
            'total_units' => $totalUnits,
        ];
    }
}
