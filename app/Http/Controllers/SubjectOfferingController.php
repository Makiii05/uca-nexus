<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Curriculum;
use App\Models\Prospectus;
use App\Models\Level;
use App\Models\AcademicTerm;
use App\Models\Subject;
use App\Models\Program;
use App\Models\SubjectOffering;

class SubjectOfferingController extends Controller
{
    public function showSubjectOffering(Request $request)
    {
        $departmentId = $request->user()->department_id;

        $academicTermId = $request->query('academic_term_id');
        $academicTerm = $academicTermId ? AcademicTerm::find($academicTermId) : null;

        $departments = Department::where('status', 'active')->orderBy('created_at', 'asc')->get();

        $programs = Program::where('department_id', $departmentId)
            ->where('status', 'active')
            ->orderBy('code')
            ->get();

        // Load subject offerings for this academic term and department
        $subjectOfferings = collect();
        if ($academicTerm) {
            $subjectOfferings = SubjectOffering::where('academic_term_id', $academicTerm->id)
                ->where('department_id', $departmentId)
                ->with(['subject', 'level', 'enlistments'])
                ->get();
        }

        return view('department.offering', [
            'departments' => $departments,
            'programs' => $programs,
            'academicTerm' => $academicTerm,
            'subjectOfferings' => $subjectOfferings,
            'departmentId' => $departmentId,
        ]);
    }

    public function searchOffering(Request $request)
    {
        $departmentId = $request->user()->department_id;

        $validated = $request->validate([
            'department' => 'required|string|max:255',
            'curriculum' => 'required|exists:curricula,id',
            'academic_term_id' => 'nullable|exists:academic_terms,id',
        ]);

        $selectedDepartment = $validated['department'];
        $curriculum_id = $validated['curriculum'];
        $academicTermId = $validated['academic_term_id'] ?? null;
        $academicTerm = $academicTermId ? AcademicTerm::find($academicTermId) : null;

        $prospectuses = Prospectus::where('status', 'active')
            ->where('curriculum_id', $curriculum_id)
            ->whereHas('curriculum', function ($query) use ($selectedDepartment) {
                $query->where('department_id', $selectedDepartment);
            })
            ->with(['curriculum.department', 'level.program', 'subject'])
            ->orderBy('created_at', 'asc')
            ->get();

        $departments = Department::where('status', 'active')->orderBy('created_at', 'asc')->get();

        $programs = Program::where('department_id', $departmentId)
            ->where('status', 'active')
            ->orderBy('code')
            ->get();

        // Load subject offerings for this academic term and department
        $subjectOfferings = collect();
        if ($academicTerm) {
            $subjectOfferings = SubjectOffering::where('academic_term_id', $academicTerm->id)
                ->where('department_id', $departmentId)
                ->with(['subject', 'level', 'enlistments'])
                ->get();
        }

        return view('department.offering', [
            'prospectuses' => $prospectuses,
            'departments' => $departments,
            'programs' => $programs,
            'old_department' => $selectedDepartment,
            'old_curriculum' => $curriculum_id,
            'academicTerm' => $academicTerm,
            'subjectOfferings' => $subjectOfferings,
            'departmentId' => $departmentId,
        ]);
    }

    /**
     * Search subjects individually (not via prospectus).
     */
    public function searchSubjects(Request $request)
    {
        $query = $request->query('q', '');

        if (strlen($query) < 1) {
            return response()->json([]);
        }

        $subjects = Subject::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('code', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            })
            ->orderBy('code')
            ->limit(50)
            ->get(['id', 'code', 'description', 'unit']);

        return response()->json($subjects);
    }

    public function addSubjectOffering(Request $request)
    {
        $departmentId = $request->user()->department_id;

        $validated = $request->validate([
            'academic_term_id' => 'required|exists:academic_terms,id',
            'subject_id' => 'required|exists:subjects,id',
            'program_id' => 'required|exists:programs,id',
            'level_id' => 'nullable|exists:levels,id',
        ]);

        $subject = Subject::findOrFail($validated['subject_id']);
        $program = Program::findOrFail($validated['program_id']);
        $level = isset($validated['level_id']) ? Level::find($validated['level_id']) : null;

        // Count existing offerings for this academic term, department, subject, program, AND level
        $countQuery = SubjectOffering::where('academic_term_id', $validated['academic_term_id'])
            ->where('department_id', $departmentId)
            ->where('subject_id', $validated['subject_id'])
            ->where('program_id', $validated['program_id']);
        
        if ($level) {
            $countQuery->where('level_id', $validated['level_id']);
        } else {
            $countQuery->whereNull('level_id');
        }
        
        $count = $countQuery->count();

        $sectionLetter = chr(65 + $count); // 0 → A, 1 → B, 2 → C, ...
        
        // Build code: PROG-SUB-YearLevelA (e.g., BSCS-INTROCOM-1A)
        $levelOrder = $level ? $level->order : '';
        $code = $program->code . '-' . $subject->code . '-' . $levelOrder . $sectionLetter;

        $subjectOffering = SubjectOffering::create([
            'academic_term_id' => $validated['academic_term_id'],
            'department_id' => $departmentId,
            'subject_id' => $validated['subject_id'],
            'program_id' => $validated['program_id'],
            'level_id' => $validated['level_id'] ?? null,
            'code' => $code,
            'description' => $subject->description,
        ]);

        $subjectOffering->load(['subject', 'level']);

        return response()->json($subjectOffering, 201);
    }

    public function removeSubjectOffering(Request $request, $id)
    {
        $departmentId = $request->user()->department_id;

        $offering = SubjectOffering::where('id', $id)
            ->where('department_id', $departmentId)
            ->firstOrFail();

        $offering->delete();

        return response()->json(['message' => 'Subject offering removed.']);
    }

    public function getSubjectOffering(Request $request, $academicTermId, $departmentId)
    {
        $subjectOfferings = SubjectOffering::where('academic_term_id', $academicTermId)
            ->where('department_id', $departmentId)
            ->with('subject')
            ->get();

        return response()->json($subjectOfferings);
    }

    public function getCurriculaByDepartment($departmentId)
    {
        $curricula = Curriculum::where('department_id', $departmentId)
            ->where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($curricula);
    }

    public function getLevelsByProgram($programId)
    {
        $levels = Level::where('program_id', $programId)
            ->orderBy('order')
            ->get(['id', 'code', 'description', 'order']);

        return response()->json($levels);
    }
}
