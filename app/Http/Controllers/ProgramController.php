<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Department;

class ProgramController extends Controller
{
    //
    public function showProgram(){
        $programs = Program::orderBy("created_at", "asc")->get();
        $departments = Department::orderBy("created_at", "asc")->get();
        return view('registrar.program', [
            'programs' => $programs,
            'departments' => $departments
        ]);
    }
    
    public function createProgram(Request $request){
        $request->validate([
            'code' => 'required|string|max:255|unique:programs',
            'description' => 'required|string|max:255',
            'department' => 'required|integer|exists:departments,id',
            'status' => 'required|in:active,inactive',
        ]);

        Program::create([
            'code' => $request->code,
            'description' => $request->description,
            'department_id' => $request->department,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.program')->with('success', 'Program created successfully');
    }
    
    public function updateProgram(Request $request, $id){
        $program = Program::findOrFail($id);
        
        $request->validate([
            'code' => 'required|string|max:255|unique:programs,code,'.$id,
            'description' => 'required|string|max:255',
            'department' => 'required|integer|exists:departments,id',
            'status' => 'required|in:active,inactive',
        ]);

        $program->update([
            'code' => $request->code,
            'description' => $request->description,
            'department_id' => $request->department,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.program')->with('success', 'Program updated successfully');
    }
    
    public function deleteProgram($id){
        $program = Program::findOrFail($id);
        $program->delete();
        
        return redirect()->route('registrar.program')->with('success', 'Program deleted successfully');
    }
}
