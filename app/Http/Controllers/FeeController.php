<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Fee;
use App\Models\AcademicTerm;

class FeeController extends Controller
{
    public function showFees() {
        $programs = Program::where('status', 'active')->orderBy("created_at", "asc")->get();

        return view('accounting.fee', [
            'programs' => $programs,
        ]);
    }

    public function getAcademicTermsByProgram(Request $request) {
        $program = Program::find($request->program_id);
        if (!$program) {
            return response()->json([]);
        }
        $terms = AcademicTerm::where('department_id', $program->department_id)
            ->orderBy('created_at')
            ->get();
        return response()->json($terms);
    }

    public function searchFee(Request $request) {
        // validate
        $request->validate([
            'program' => 'required|string|max:255',
            'academic_term_id' => 'required|exists:academic_terms,id',
        ]);
        // assign
        $program = $request->program;
        $academic_term_id = $request->academic_term_id;

        $major_fees = Fee::where('group','major')->where('academic_term_id',$academic_term_id)->where('program_id',$program)->get();
        $other_fees = Fee::where('group','other')->where('academic_term_id',$academic_term_id)->where('program_id',$program)->get();
        $additional_fees = Fee::where('group','additional')->where('academic_term_id',$academic_term_id)->where('program_id',$program)->get();
        $programs = Program::where('status', 'active')->orderBy("created_at", "asc")->get();

        return view('accounting.fee', [
            'programs' => $programs,
            'major_fees' => $major_fees,
            'other_fees' => $other_fees,
            'additional_fees' => $additional_fees,
            'old_program' => $program,
            'old_academic_term_id' => $academic_term_id,
        ]);
    }

    public function createFee(Request $request) {
        // Validate the request
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'months_to_pay' => 'nullable|numeric',
            'group' => 'required|string|max:255',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'program_id' => 'required|exists:programs,id',
        ]);

        $fees = Fee::create([
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'month_to_pay' => $validated['months_to_pay'],
            'group' => $validated['group'],
            'academic_term_id' => $validated['academic_term_id'],
            'program_id' => $validated['program_id'],
        ]);

        return redirect()->route('accounting.fee.search', [
            'program' => $validated['program_id'],
            'academic_term_id' => $validated['academic_term_id'],
        ]);
    }

    public function updateFee(Request $request, $id) {
        // Find the fee or fail
        $fee = Fee::findOrFail($id);

        // Validate the request
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|string|max:255',
            'type' => 'nullable|string|max:255',
            'months_to_pay' => 'nullable|numeric',
            'group' => 'required|string|max:255',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'program' => 'required|exists:programs,id',
        ]);

        // Update the fee
        $fee->update([
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'type' => $validated['type'],
            'month_to_pay' => $validated['months_to_pay'],
            'group' => $validated['group'],
            'academic_term_id' => $validated['academic_term_id'],
            'program_id' => $validated['program'],
        ]);

        return redirect()->route('accounting.fee.search', [
            'program' => $validated['program'],
            'academic_term_id' => $validated['academic_term_id'],
        ])->with('success', 'Fee updated successfully.');
    }

    public function deleteFee($id) {
        // Find the fee or fail
        $fee = Fee::findOrFail($id);

        // Store program and academic term before deleting
        $program_id = $fee->program_id;
        $academic_term_id = $fee->academic_term_id;

        // Delete the fee
        $fee->delete();

        return redirect()->route('accounting.fee.search', [
            'program' => $program_id,
            'academic_term_id' => $academic_term_id,
        ])->with('success', 'Fee deleted successfully.');
    }

    public function showLedger($id) {
        // Find the fee with its relationships
        $fee = Fee::with(['academicTerm', 'program'])->findOrFail($id);

        // Get all students who have this fee through StudentFee
        $studentFees = \App\Models\StudentFee::with(['student', 'student.program', 'student.level'])
            ->where('fee_id', $id)
            ->get();

        // Check if this is a unit fee
        $isUnitFee = strtolower($fee->description) === 'unit fee';
        $grandTotal = 0;

        // Calculate totals - for unit fees, we need to get each student's total units
        foreach ($studentFees as $studentFee) {
            if ($isUnitFee) {
                // Get student's enlistments for this academic term and calculate total units
                $totalUnits = \App\Models\Enlistment::where('student_id', $studentFee->student_id)
                    ->where('academic_term_id', $fee->academic_term_id)
                    ->with('subjectOffering.subject')
                    ->get()
                    ->sum(function ($enlistment) {
                        return $enlistment->subjectOffering->subject->unit ?? 0;
                    });
                
                $studentFee->total_units = $totalUnits;
                $studentFee->calculated_amount = $totalUnits * $fee->amount;
                $grandTotal += $studentFee->calculated_amount;
            } else {
                $studentFee->total_units = null;
                $studentFee->calculated_amount = $fee->amount;
                $grandTotal += $fee->amount;
            }
        }

        return view('accounting.fee_ledger', [
            'fee' => $fee,
            'studentFees' => $studentFees,
            'grandTotal' => $grandTotal,
            'isUnitFee' => $isUnitFee,
        ]);
    }
}
