<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    //
    public function showSubject(){
        $subjects = Subject::orderBy("created_at", "asc")->orderBy("status", "asc")->get();
        return view('registrar.subject', [
            'subjects' => $subjects
        ]);
    }
    
    public function createSubject(Request $request){
        $request->validate([
            'code' => 'required|string|max:255|unique:subjects',
            'description' => 'required|string|max:255',
            'unit' => 'required|integer|min:0',
            'lech' => 'required|integer|min:0',
            'lecu' => 'required|integer|min:0',
            'labh' => 'required|integer|min:0',
            'labu' => 'required|integer|min:0',
            'type' => 'required|in:lec,lab,lec lab',
            'education_level' => 'nullable|in:K12,college',
            'weight_category' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Subject::create([
            'code' => $request->code,
            'description' => $request->description,
            'unit' => $request->unit,
            'lech' => $request->lech,
            'lecu' => $request->lecu,
            'labh' => $request->labh,
            'labu' => $request->labu,
            'type' => $request->type,
            'education_level' => $request->education_level,
            'weight_category' => $request->weight_category,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.subject')->with('success', 'Subject created successfully');
    }
    
    public function updateSubject(Request $request, $id){
        $subject = Subject::findOrFail($id);
        
        $request->validate([
            'code' => 'required|string|max:255|unique:subjects,code,'.$id,
            'description' => 'required|string|max:255',
            'unit' => 'required|integer|min:0',
            'lech' => 'required|integer|min:0',
            'lecu' => 'required|integer|min:0',
            'labh' => 'required|integer|min:0',
            'labu' => 'required|integer|min:0',
            'type' => 'required|in:lec,lab,lec lab',
            'education_level' => 'nullable|in:K12,college',
            'weight_category' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $subject->update([
            'code' => $request->code,
            'description' => $request->description,
            'unit' => $request->unit,
            'lech' => $request->lech,
            'lecu' => $request->lecu,
            'labh' => $request->labh,
            'labu' => $request->labu,
            'type' => $request->type,
            'education_level' => $request->education_level,
            'weight_category' => $request->weight_category,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.subject')->with('success', 'Subject updated successfully');
    }
    
    public function deleteSubject($id){
        $subject = Subject::findOrFail($id);
        $subject->delete();
        
        return redirect()->route('registrar.subject')->with('success', 'Subject deleted successfully');
    }
}
