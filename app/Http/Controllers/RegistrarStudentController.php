<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Level;
use App\Models\AcademicTerm;
use App\Models\Enlistment;
use App\Models\Fee;
use App\Models\StudentFee;
use App\Models\AssessmentHistory;

class RegistrarStudentController extends Controller
{
    public function showStudents()
    {
        // Don't load students on initial page load - they will be fetched via API search
        return view('registrar.student');
    }

    public function searchStudents(Request $request)
    {
        $search = $request->input('search', '');
        
        if (empty($search)) {
            return response()->json(['data' => []]);
        }
        
        $students = Student::with(['department', 'program', 'level'])
            ->where(function ($query) use ($search) {
                $query->where('student_number', 'like', '%' . $search . '%')
                    ->orWhere('lrn', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('middle_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('sex', 'like', '%' . $search . '%')
                    ->orWhere('citizenship', 'like', '%' . $search . '%')
                    ->orWhere('religion', 'like', '%' . $search . '%')
                    ->orWhere('place_of_birth', 'like', '%' . $search . '%')
                    ->orWhere('civil_status', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhereHas('department', function ($q) use ($search) {
                        $q->where('code', 'like', '%' . $search . '%')
                          ->orWhere('description', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('program', function ($q) use ($search) {
                        $q->where('code', 'like', '%' . $search . '%')
                          ->orWhere('description', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('level', function ($q) use ($search) {
                        $q->where('code', 'like', '%' . $search . '%')
                          ->orWhere('description', 'like', '%' . $search . '%');
                    });
            })
            ->orderBy('student_number')
            ->get();
        
        return response()->json(['data' => $students]);
    }

    public function showAssessment($id)
    {
        $student = Student::with(['department', 'program', 'level', 'contact'])->findOrFail($id);
        $levels = Level::where('program_id', $student->program_id)->orderBy('order')->get();

        // Get all academic terms for this student's department
        $academicTerms = AcademicTerm::where('department_id', $student->department_id)
            ->orderBy('created_at')
            ->get();

        return view('registrar.student_assessment', compact('student', 'levels', 'academicTerms'));
    }

    public function getEnlistments($studentId, $academicTermId)
    {
        $enlistments = Enlistment::with(['subjectOffering.subject'])
            ->where('student_id', $studentId)
            ->where('academic_term_id', $academicTermId)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $enlistments->map(function ($enlistment) {
                return [
                    'id' => $enlistment->id,
                    'code' => $enlistment->subjectOffering->code ?? '-',
                    'description' => $enlistment->subjectOffering->description ?? '-',
                    'unit' => $enlistment->subjectOffering->subject->unit ?? 0,
                ];
            }),
        ]);
    }

    public function updateLevel(Request $request, $id)
    {
        $validated = $request->validate([
            'level_id' => 'required|exists:levels,id',
        ]);

        $student = Student::findOrFail($id);
        $student->level_id = $validated['level_id'];
        $student->save();

        return response()->json([
            'success' => true,
            'message' => 'Year level updated successfully.',
        ]);
    }

    /**
     * Get student fees for a given academic term, grouped by fee group.
     */
    public function getStudentFees($studentId, $academicTermId)
    {
        $studentFees = StudentFee::with(['fee'])
            ->where('student_id', $studentId)
            ->where('academic_term_id', $academicTermId)
            ->get();

        $grouped = [
            'major' => [],
            'other' => [],
            'additional' => [],
        ];

        foreach ($studentFees as $sf) {
            $fee = $sf->fee;
            if ($fee) {
                $group = $fee->group ?? 'other';
                $grouped[$group][] = [
                    'student_fee_id' => $sf->id,
                    'fee_id' => $fee->id,
                    'description' => $fee->description,
                    'amount' => $fee->amount,
                    'type' => $fee->type,
                    'month_to_pay' => $fee->month_to_pay,
                ];
            }
        }

        return response()->json(['success' => true, 'data' => $grouped]);
    }

    /**
     * Get existing fees (from accounting fee table) for the student's program + academic term,
     * excluding fees already assigned to this student.
     */
    public function getExistingFees($studentId, $academicTermId, $group)
    {
        $student = Student::findOrFail($studentId);

        // IDs of fees already assigned to this student for this term
        $assignedFeeIds = StudentFee::where('student_id', $studentId)
            ->where('academic_term_id', $academicTermId)
            ->pluck('fee_id');

        $fees = Fee::where('program_id', $student->program_id)
            ->where('academic_term_id', $academicTermId)
            ->where('group', $group)
            ->whereNull('student_id')
            ->whereNotIn('id', $assignedFeeIds)
            ->get(['id', 'description', 'amount', 'type', 'month_to_pay']);

        return response()->json(['success' => true, 'data' => $fees]);
    }

    /**
     * Create a new fee (in fees table) and assign it to the student (student_fees table).
     */
    public function createStudentFee(Request $request, $studentId)
    {
        $student = Student::findOrFail($studentId);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'nullable|string|max:255',
            'months_to_pay' => 'nullable|numeric',
            'group' => 'required|in:major,other,additional',
            'academic_term_id' => 'required|exists:academic_terms,id',
        ]);

        // Create the fee record
        $fee = Fee::create([
            'description' => $validated['description'],
            'amount' => $validated['amount'],
            'type' => $validated['type'] ?? null,
            'month_to_pay' => $validated['months_to_pay'] ?? null,
            'group' => $validated['group'],
            'academic_term_id' => $validated['academic_term_id'],
            'program_id' => $student->program_id,
            'student_id' => $student->id,
        ]);

        // Link to student_fees
        StudentFee::create([
            'student_id' => $student->id,
            'fee_id' => $fee->id,
            'academic_term_id' => $validated['academic_term_id'],
        ]);

        return response()->json(['success' => true, 'message' => 'Fee created and assigned.']);
    }

    /**
     * Assign an existing fee to a student (add to student_fees table).
     */
    public function assignExistingFee(Request $request, $studentId)
    {
        $validated = $request->validate([
            'fee_id' => 'required|exists:fees,id',
            'academic_term_id' => 'required|exists:academic_terms,id',
        ]);

        // Avoid duplicates
        $exists = StudentFee::where('student_id', $studentId)
            ->where('fee_id', $validated['fee_id'])
            ->where('academic_term_id', $validated['academic_term_id'])
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Fee already assigned.'], 409);
        }

        StudentFee::create([
            'student_id' => $studentId,
            'fee_id' => $validated['fee_id'],
            'academic_term_id' => $validated['academic_term_id'],
        ]);

        return response()->json(['success' => true, 'message' => 'Fee assigned.']);
    }

    /**
     * Remove a student fee assignment.
     */
    public function removeStudentFee($studentFeeId)
    {
        $sf = StudentFee::findOrFail($studentFeeId);
        $sf->delete();

        return response()->json(['success' => true, 'message' => 'Fee removed.']);
    }

    /**
     * Get all assessment histories for a student.
     */
    public function getAssessmentHistories($studentId)
    {
        $histories = AssessmentHistory::with(['academicTerm'])
            ->where('student_id', $studentId)
            ->orderBy('date_printed', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $histories->map(function ($history) {
                return [
                    'id' => $history->id,
                    'date_printed' => $history->date_printed->format('M d, Y h:i A'),
                    'academic_term' => $history->academicTerm->description ?? '-',
                    'academic_term_id' => $history->academic_term_id,
                ];
            }),
        ]);
    }

    /**
     * Delete an assessment history record.
     */
    public function deleteAssessmentHistory($id)
    {
        $history = AssessmentHistory::findOrFail($id);
        $history->delete();

        return response()->json(['success' => true, 'message' => 'Assessment history deleted.']);
    }
}
