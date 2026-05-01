<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use App\Models\Department;
use App\Models\Grade;
use App\Models\Teacher;
use App\Models\TeacherOffering;
use Illuminate\Http\Request;

class RegistrarGradeApprovalController extends Controller
{
    public function showGradeApproval()
    {
        $departments = Department::query()
            ->where('status', 'active')
            ->orderBy('description')
            ->get(['id', 'code', 'description']);

        return view('registrar.grade_approval', [
            'departments' => $departments,
        ]);
    }

    public function getTeachersByDepartment(Request $request)
    {
        $validated = $request->validate([
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ]);

        $teachers = Teacher::query()
            ->where('status', 'active')
            ->whereHas('offerings', function ($query) use ($validated) {
                $query->whereHas('offering', function ($offeringQuery) use ($validated) {
                    $offeringQuery->where('department_id', $validated['department_id']);
                });
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get()
            ->map(function ($teacher) {
                return [
                    'id' => $teacher->id,
                    'code' => $teacher->code,
                    'name' => trim($teacher->last_name . ', ' . $teacher->first_name . ' ' . ($teacher->middle_name ? substr($teacher->middle_name, 0, 1) . '.' : '')),
                ];
            });

        return response()->json($teachers);
    }

    public function getAcademicTermsByDepartment(Request $request)
    {
        $validated = $request->validate([
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ]);

        $academicTerms = AcademicTerm::query()
            ->where('status', 'active')
            ->where('department_id', $validated['department_id'])
            ->orderBy('created_at')
            ->get()
            ->map(function ($academicTerm) {
                return [
                    'id' => $academicTerm->id,
                    'code' => $academicTerm->code,
                    'description' => $academicTerm->description,
                    'type' => $academicTerm->type,
                ];
            });

        return response()->json($academicTerms);
    }

    public function getSubjectOfferings(Request $request)
    {
        $validated = $request->validate([
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'academic_term_id' => ['required', 'integer', 'exists:academic_terms,id'],
        ]);

        $offerings = TeacherOffering::query()
            ->where('teacher_id', $validated['teacher_id'])
            ->where('academic_term_id', $validated['academic_term_id'])
            ->whereHas('offering', function ($query) use ($validated) {
                $query->where('department_id', $validated['department_id']);
            })
            ->where('status', 'active')
            ->with([
                'teacher',
                'offering.subject',
                'academicTerm',
            ])
            ->orderBy('created_at')
            ->get()
            ->map(function ($teacherOffering) {
                return [
                    'id' => $teacherOffering->id,
                    'teacher_id' => $teacherOffering->teacher_id,
                    'academic_term_id' => $teacherOffering->academic_term_id,
                    'teacher_name' => trim($teacherOffering->teacher?->last_name . ', ' . $teacherOffering->teacher?->first_name . ' ' . ($teacherOffering->teacher?->middle_name ? substr($teacherOffering->teacher->middle_name, 0, 1) . '.' : '')),
                    'offering_code' => $teacherOffering->offering?->code ?? '',
                    'offering_description' => $teacherOffering->offering?->description ?? '',
                    'subject_code' => $teacherOffering->offering?->subject?->code ?? '',
                    'subject_description' => $teacherOffering->offering?->subject?->description ?? '',
                    'academic_term_description' => $teacherOffering->academicTerm?->description ?? '',
                ];
            });

        return response()->json($offerings);
    }

    public function getPeriodsByAcademicTerm(Request $request)
    {
        $validated = $request->validate([
            'academic_term_id' => ['required', 'integer', 'exists:academic_terms,id'],
        ]);

        $academicTerm = AcademicTerm::query()->findOrFail($validated['academic_term_id']);
        $academicTermType = strtolower(trim((string) $academicTerm->type));

        $periods = $academicTermType === 'semester'
            ? ['Prelim', 'Midterm', 'Semi-Final', 'Final']
            : ($academicTermType === 'full year'
                ? ['Quarter 1', 'Quarter 2', 'Quarter 3', 'Quarter 4']
                : ['Final']);

        return response()->json([
            'academic_term_id' => $academicTerm->id,
            'academic_term_type' => $academicTerm->type,
            'periods' => $periods,
        ]);
    }

    public function getSubmittedGrades(Request $request)
    {
        $validated = $request->validate([
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'academic_term_id' => ['required', 'integer', 'exists:academic_terms,id'],
            'teacher_offering_id' => ['required', 'integer', 'exists:teacher_offerings,id'],
            'period' => ['required', 'string'],
            'status' => ['nullable', 'string', 'in:submitted,approved,rejected'],
        ]);

        $teacherOffering = TeacherOffering::query()
            ->where('id', $validated['teacher_offering_id'])
            ->where('teacher_id', $validated['teacher_id'])
            ->where('academic_term_id', $validated['academic_term_id'])
            ->whereHas('offering', function ($query) use ($validated) {
                $query->where('department_id', $validated['department_id']);
            })
            ->with([
                'teacher',
                'offering.subject',
                'academicTerm',
            ])
            ->firstOrFail();

        $gradesQuery = Grade::query()
            ->with('student')
            ->where('teacher_offering_id', $teacherOffering->id)
            ->where('period', $validated['period']);

        // Filter by status if provided, otherwise default to 'submitted'
        if ($validated['status'] ?? false) {
            $gradesQuery->where('status', $validated['status']);
        } else {
            $gradesQuery->where('status', 'submitted');
        }

        $grades = $gradesQuery
            ->orderBy('created_at')
            ->get()
            ->map(function ($grade) {
                $student = $grade->student;

                return [
                    'id' => $grade->id,
                    'student_id' => $student?->id,
                    'student_number' => $student?->student_number,
                    'student_name' => $student
                        ? trim($student->last_name . ', ' . $student->first_name . ' ' . ($student->middle_name ? substr($student->middle_name, 0, 1) . '.' : ''))
                        : '-',
                    'initial_grade' => $grade->initial_grade,
                    'period_grade' => $grade->period_grade,
                    'status' => $grade->status,
                    'submitted_at' => $grade->submitted_at?->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'summary' => [
                'department' => [
                    'id' => $validated['department_id'],
                    'label' => Department::query()->find($validated['department_id'])?->description ?? '',
                ],
                'teacher' => [
                    'id' => $teacherOffering->teacher_id,
                    'label' => trim($teacherOffering->teacher?->last_name . ', ' . $teacherOffering->teacher?->first_name . ' ' . ($teacherOffering->teacher?->middle_name ? substr($teacherOffering->teacher->middle_name, 0, 1) . '.' : '')),
                ],
                'academic_term' => [
                    'id' => $teacherOffering->academicTerm?->id,
                    'label' => $teacherOffering->academicTerm?->description ?? '',
                    'type' => $teacherOffering->academicTerm?->type ?? '',
                ],
                'subject_offering' => [
                    'id' => $teacherOffering->id,
                    'label' => trim(($teacherOffering->offering?->code ?? '') . ' ' . ($teacherOffering->offering?->subject?->description ?? '')),
                    'code' => $teacherOffering->offering?->code ?? '',
                    'subject_code' => $teacherOffering->offering?->subject?->code ?? '',
                    'subject_description' => $teacherOffering->offering?->subject?->description ?? '',
                ],
                'period' => $validated['period'],
            ],
            'grades' => $grades,
        ]);
    }

    public function updateGradeStatus(Request $request, $gradeId)
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:approved,rejected'],
        ]);

        $grade = Grade::query()->with('student', 'teacherOffering.offering.subject', 'teacherOffering.academicTerm')->findOrFail($gradeId);

        $updateData = [
            'status' => $validated['status'],
        ];

        if ($validated['status'] === 'approved') {
            $updateData['approved_by'] = auth()->id();
            $updateData['approved_at'] = now();
            $updateData['finalized_at'] = null;
        } else {
            $updateData['approved_by'] = null;
            $updateData['approved_at'] = null;
            $updateData['finalized_at'] = null;
        }

        $grade->update($updateData);

        return response()->json([
            'id' => $grade->id,
            'status' => $grade->status,
            'approved_by' => $grade->approved_by,
            'approved_at' => $grade->approved_at,
            'student_name' => $grade->student
                ? trim($grade->student->last_name . ', ' . $grade->student->first_name . ' ' . ($grade->student->middle_name ? substr($grade->student->middle_name, 0, 1) . '.' : ''))
                : '-',
        ]);
    }
}
