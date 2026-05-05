<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use App\Models\Enlistment;
use App\Models\Grade;
use App\Models\Student;
use App\Models\SubjectOffering;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DepartmentGradeReportController extends Controller
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
        $user = auth()->user();
        $departmentId = $user->department_id;
        $academicTermId = $request->query('academic_term_id');
        $subjectOfferingId = $request->query('subject_offering_id');
        $period = $request->query('period');

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
            $academicTerm = AcademicTerm::query()
                ->where('department_id', $departmentId)
                ->find($academicTermId);
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
                ->where('department_id', $departmentId)
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

        return view('department.grade_report', [
            'academicTerms' => $academicTerms,
            'subjectOfferings' => $subjectOfferings,
            'periodOptions' => $periodOptions,
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

        return view('department.grade_report_view', [
            'student' => $student,
            'academicTerm' => $academicTerm,
            'period' => $validated['period'],
            'rows' => $report['rows'],
            'generalAverage' => $report['general_average'],
            'totalUnits' => $report['total_units'],
        ]);
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
