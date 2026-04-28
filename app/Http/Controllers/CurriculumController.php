<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curriculum;
use App\Models\Department;

class CurriculumController extends Controller
{
    //
    public function showCurriculum(){
        $curricula = Curriculum::orderBy("created_at", "asc")->orderBy("status", "asc")->get();
        $departments = Department::orderBy("created_at", "asc")->get();
        return view('registrar.curriculum', [
            'curricula' => $curricula,
            'departments' => $departments,
        ]);
    }
    
    public function createCurriculum(Request $request){
        $request->validate([
            'curriculum' => 'required|string|max:255',
            'department' => 'required|integer|exists:departments,id',
            'status' => 'required|in:active,inactive',
        ]);

        Curriculum::create([
            'curriculum' => $request->curriculum,
            'department_id' => $request->department,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.curriculum')->with('success', 'Curriculum created successfully');
    }
    
    public function updateCurriculum(Request $request, $id){
        $curriculum = Curriculum::findOrFail($id);
        
        $request->validate([
            'curriculum' => 'required|string|max:255',
            'department' => 'required|integer|exists:departments,id',
            'status' => 'required|in:active,inactive',
        ]);

        $curriculum->update([
            'curriculum' => $request->curriculum,
            'department_id' => $request->department,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.curriculum')->with('success', 'Curriculum updated successfully');
    }
    
    public function deleteCurriculum($id){
        $curriculum = Curriculum::findOrFail($id);
        $curriculum->delete();
        
        return redirect()->route('registrar.curriculum')->with('success', 'Curriculum deleted successfully');
    }
}
