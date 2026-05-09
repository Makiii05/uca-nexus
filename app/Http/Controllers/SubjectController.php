<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;
use App\Models\SubjectFee;
use App\Models\Program;
use App\Models\Fee;
use App\Models\AcademicTerm;

class SubjectController extends Controller
{
    //
    public function showSubject(){
        $subjects = Subject::orderBy("created_at", "asc")->orderBy("status", "asc")->get();
        $programs = Program::where('status', 'active')->orderBy('created_at', 'asc')->get();
        return view('registrar.subject', [
            'subjects' => $subjects,
            'programs' => $programs,
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
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.subject')->with('success', 'Subject updated successfully');
    }
    
    public function deleteSubject($id){
        $subject = Subject::findOrFail($id);
        $subject->delete();
        
        return redirect()->route('registrar.subject')->with('success', 'Subject deleted successfully');
    }

    // Subject Fee API methods

    public function getSubjectFees($id){
        $subjectFees = SubjectFee::with(['fee', 'fee.program', 'fee.academicTerm'])
            ->where('subject_id', $id)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $subjectFees->map(function ($sf) {
                return [
                    'id' => $sf->id,
                    'fee_id' => $sf->fee_id,
                    'description' => $sf->fee->description ?? '',
                    'group' => $sf->fee->group ?? '',
                    'amount' => $sf->fee->amount ?? 0,
                ];
            }),
        ]);
    }

    public function getAcademicTermsByProgram(Request $request){
        $program = Program::find($request->program_id);
        if (!$program) {
            return response()->json([]);
        }
        $terms = AcademicTerm::where('department_id', $program->department_id)
            ->orderBy('created_at')
            ->get();
        return response()->json($terms);
    }

    public function getFeesByTerm(Request $request){
        $request->validate([
            'academic_term_id' => 'required|exists:academic_terms,id',
            'program_id' => 'required|exists:programs,id',
        ]);

        $fees = Fee::where('academic_term_id', $request->academic_term_id)
            ->where('program_id', $request->program_id)
            ->orderBy('description')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $fees,
        ]);
    }

    public function addSubjectFee(Request $request, $id){
        $request->validate([
            'fee_id' => 'required|exists:fees,id',
        ]);

        $exists = SubjectFee::where('subject_id', $id)
            ->where('fee_id', $request->fee_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This fee is already assigned to this subject.',
            ], 400);
        }

        SubjectFee::create([
            'subject_id' => $id,
            'fee_id' => $request->fee_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fee assigned to subject successfully.',
        ]);
    }

    public function removeSubjectFee($subjectFeeId){
        $subjectFee = SubjectFee::findOrFail($subjectFeeId);
        $subjectFee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fee removed from subject successfully.',
        ]);
    }
}
