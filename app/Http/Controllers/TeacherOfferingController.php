<?php

namespace App\Http\Controllers;

use App\Models\AcademicTerm;
use App\Models\SubjectOffering;
use App\Models\Teacher;
use App\Models\TeacherOffering;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeacherOfferingController extends Controller
{
    public function showTeacherLoading(Request $request)
    {
        $departmentId = $request->user()->department_id;

        $teachers = Teacher::whereHas('offerings', function ($query) use ($departmentId) {
            $query->whereHas('offering', function ($offeringQuery) use ($departmentId) {
                $offeringQuery->where('department_id', $departmentId);
            });
        })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $teacherSelectOptions = Teacher::where('status', 'active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $academicTerms = AcademicTerm::where('status', 'active')
            ->where('department_id', $departmentId)
            ->orderBy('created_at')
            ->get();

        return view('department.teacher_loading', [
            'departmentId' => $departmentId,
            'teachers' => $teachers,
            'teacherSelectOptions' => $teacherSelectOptions,
            'academicTerms' => $academicTerms,
        ]);
    }

    public function showTeacherSubjects(Request $request, $teacherId)
    {
        $departmentId = $request->user()->department_id;

        $teacher = Teacher::findOrFail($teacherId);

        $teacherBelongsToDepartment = TeacherOffering::where('teacher_id', $teacher->id)
            ->whereHas('offering', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->exists();

        if (!$teacherBelongsToDepartment) {
            abort(404);
        }

        $academicTerms = AcademicTerm::where('status', 'active')
            ->where('department_id', $departmentId)
            ->orderBy('created_at')
            ->get();

        $academicTermId = $request->query('academic_term_id');
        $academicTerm = null;
        if ($academicTermId) {
            $academicTerm = AcademicTerm::where('id', $academicTermId)
                ->where('department_id', $departmentId)
                ->first();
        }

        $teacherOfferings = collect();
        if ($academicTerm) {
            $teacherOfferings = TeacherOffering::where('teacher_id', $teacher->id)
                ->where('academic_term_id', $academicTerm->id)
                ->whereHas('offering', function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })
                ->with([
                    'offering.subject',
                    'offering.program',
                    'offering.level',
                ])
                ->orderBy('created_at')
                ->get();
        }

        return view('department.teacher_subjects', [
            'departmentId' => $departmentId,
            'teacher' => $teacher,
            'academicTerms' => $academicTerms,
            'academicTerm' => $academicTerm,
            'teacherOfferings' => $teacherOfferings,
        ]);
    }

    public function createTeacherOffering(Request $request)
    {
        $departmentId = $request->user()->department_id;

        $validated = $request->validate([
            'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
            'academic_term_id' => [
                'required',
                'integer',
                Rule::exists('academic_terms', 'id')->where(function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                }),
            ],
            'offering_id' => [
                'required',
                'integer',
                Rule::exists('subject_offerings', 'id')->where(function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                }),
            ],
            'status' => ['required', 'in:active,inactive'],
        ]);

        $offeringValidForTerm = SubjectOffering::where('id', $validated['offering_id'])
            ->where('department_id', $departmentId)
            ->where('academic_term_id', $validated['academic_term_id'])
            ->exists();

        if (!$offeringValidForTerm) {
            return back()
                ->withErrors(['offering_id' => 'Selected subject offering is not available for the chosen academic term.'])
                ->withInput();
        }

        $duplicate = TeacherOffering::where('teacher_id', $validated['teacher_id'])
            ->where('offering_id', $validated['offering_id'])
            ->where('academic_term_id', $validated['academic_term_id'])
            ->exists();

        if ($duplicate) {
            return back()
                ->withErrors(['offering_id' => 'This subject is already assigned to the selected teacher for this academic term.'])
                ->withInput();
        }

        TeacherOffering::create([
            'teacher_id' => $validated['teacher_id'],
            'offering_id' => $validated['offering_id'],
            'academic_term_id' => $validated['academic_term_id'],
            'status' => $validated['status'],
        ]);

        return back()->with('success', 'Subject assigned to teacher successfully');
    }
}
