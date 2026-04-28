<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    //
    public function showDepartment(){
        $departments = Department::orderBy("created_at", "asc")->get();
        return view('registrar.department', [
            'departments' => $departments
        ]);
    }
    
    public function createDepartment(Request $request){
        $request->validate([
            'code' => 'required|string|max:255|unique:departments',
            'description' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Department::create([
            'code' => $request->code,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.department')->with('success', 'Department created successfully');
    }
    
    public function updateDepartment(Request $request, $id){
        $department = Department::findOrFail($id);
        
        $request->validate([
            'code' => 'required|string|max:255|unique:departments,code,'.$id,
            'description' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $department->update([
            'code' => $request->code,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.department')->with('success', 'Department updated successfully');
    }
    
    public function deleteDepartment($id){
        $department = Department::findOrFail($id);
        $department->delete();
        
        return redirect()->route('registrar.department')->with('success', 'Department deleted successfully');
    }
}
