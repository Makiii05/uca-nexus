<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prospectus;
use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\Level;
use App\Models\Department;
use App\Models\AcademicTerm;

class ProspectusController extends Controller
{
    private function validateSearchRequest(Request $request): array
    {
        return $request->validate([
            'department' => 'required|string|max:255',
            'curriculum' => 'required|exists:curricula,id',
        ]);
    }

    private function validateProspectusRequest(Request $request): array
    {
        return $request->validate([
            'curriculum' => 'required|exists:curricula,id',
            'level' => 'required|exists:levels,id',
            'term' => 'required|exists:academic_terms,id',
            'subject' => 'required|exists:subjects,id',
            'status' => 'required|in:active,inactive',
        ]);
    }

    public function showProspectus(){
        $curricula = Curriculum::where('status', 'active')->orderBy("created_at", "asc")->get();
        $subjects = Subject::where('status', 'active')->orderBy("created_at", "asc")->get();
        $departments = Department::where('status', 'active')->orderBy("created_at", "asc")->get();
        
        return view('registrar.prospectus', [
            'curricula' => $curricula,
            'subjects' => $subjects,
            'departments' => $departments,
        ]);
    }

    public function searchProspectus(Request $request){
        $validated = $this->validateSearchRequest($request);

        $department = $validated['department'];
        $curriculum_id = $validated['curriculum'];

        $prospectuses = Prospectus::where('status', 'active')
            ->where('curriculum_id', $curriculum_id)
            ->whereHas('curriculum', function ($query) use ($department) {
                $query->where('department_id', $department);
            })
            ->with(['curriculum.department', 'level.program', 'term', 'subject'])
            ->get();
        $curricula = Curriculum::where('status', 'active')->orderBy("created_at", "asc")->get();
        $subjects = Subject::where('status', 'active')->orderBy("created_at", "asc")->get();
        $departments = Department::where('status', 'active')->orderBy("created_at", "asc")->get();
        
        return view('registrar.prospectus', [
            'prospectuses' => $prospectuses,
            'curricula' => $curricula,
            'subjects' => $subjects,
            'departments' => $departments,
            'old_department' => $department,
            'old_curriculum' => $curriculum_id,
        ]);
    }
    
    public function createProspectus(Request $request){
        $validated = $this->validateProspectusRequest($request);

        $prospectus = Prospectus::create([
            'curriculum_id' => $validated['curriculum'],
            'level_id' => $validated['level'],
            'term_id' => $validated['term'],
            'subject_id' => $validated['subject'],
            'status' => $validated['status'],
        ]);

        $prospectus->load(['curriculum.department', 'level.program', 'term', 'subject']);

        return response()->json([
            'success' => true,
            'message' => 'Prospectus created successfully',
            'prospectus' => $prospectus
        ]);
    }

    public function updateProspectus(Request $request, $id){
        $prospectus = Prospectus::findOrFail($id);
        
        $validated = $this->validateProspectusRequest($request);

        $prospectus->update([
            'curriculum_id' => $validated['curriculum'],
            'level_id' => $validated['level'],
            'term_id' => $validated['term'],
            'subject_id' => $validated['subject'],
            'status' => $validated['status'],
        ]);

        $prospectus->load(['curriculum.department', 'level.program', 'term', 'subject']);

        return response()->json([
            'success' => true,
            'message' => 'Prospectus updated successfully',
            'prospectus' => $prospectus
        ]);
    }

    public function deleteProspectus($id){
        $prospectus = Prospectus::findOrFail($id);
        $prospectus->delete();

        return response()->json([
            'success' => true,
            'message' => 'Prospectus deleted successfully'
        ]);
    }

    public function getLevelsByDepartment($departmentId)
    {
        $levels = Level::with('program')
            ->whereHas('program', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($levels);
    }

    public function getCurriculaByDepartment($departmentId)
    {
        $curricula = Curriculum::where('department_id', $departmentId)
            ->where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($curricula);
    }

    public function getTermsByDepartment($departmentId)
    {
        $terms = AcademicTerm::where('department_id', $departmentId)
            ->where('status', 'active')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($terms);
    }

    public function getProspectusesApi(Request $request)
    {
        $validated = $this->validateSearchRequest($request);

        $department = $validated['department'];
        $curriculum_id = $validated['curriculum'];

        $prospectuses = Prospectus::where('status', 'active')
            ->where('curriculum_id', $curriculum_id)
            ->whereHas('curriculum', function ($query) use ($department) {
                $query->where('department_id', $department);
            })
            ->with(['curriculum.department', 'level.program', 'term', 'subject'])
            ->get();

        $grouped = $prospectuses
            // should be order by code of the program. But since the relationship is through level, we will order by program code in the collection after loading the relationship.
            ->sortBy(function ($prospectus) {
                return $prospectus->level->program->code;
            })
            ->groupBy('level_id')
            ->map(function ($levelGroup) {
                $terms = $levelGroup
                    ->sortBy('term.description')
                    ->groupBy('term_id')
                    ->map(function ($termGroup) {
                        return [
                            'term' => $termGroup->first()->term,
                            'prospectuses' => $termGroup->sortBy('subject.description')->values(),
                        ];
                    })
                    ->values();

                return [
                    'level' => $levelGroup->first()->level,
                    'terms' => $terms,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'data' => $grouped,
            'count' => $prospectuses->count()
        ]);
    }
}