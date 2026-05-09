<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Level;
use App\Models\AcademicTerm;
use App\Models\Enlistment;
use App\Models\SubjectOffering;
use App\Models\SubjectFee;
use App\Models\StudentFee;
use App\Models\Transaction;

class EnlistmentController extends Controller
{
    public function showEnlistment(Request $request)
    {
        $user = $request->user();
        $departmentId = $user->department_id;

        $academicTermId = $request->query('academic_term_id');
        $academicTerm = $academicTermId ? AcademicTerm::find($academicTermId) : null;

        $students = Student::with(['program', 'level'])
            ->where('department_id', $departmentId)
            ->orderBy('student_number')
            ->get();
        
        return view('department.enlistment', compact('students', 'academicTerm'));
    }

    public function viewStudentSubjects(Request $request, $id, $academic_term_id)
    {
        $student = Student::with(['program', 'level'])->findOrFail($id);
        $academicTerm = AcademicTerm::where('id', $academic_term_id)->first();
        $levels = Level::where('program_id', $student->program_id)->orderBy('order')->get();

        // Check if student has any transactions for this academic term
        $hasTransactions = Transaction::where('student_id', $id)
            ->where('academic_term_id', $academic_term_id)
            ->exists();

        return view('department.student_subjects', compact('student', 'academicTerm', 'levels', 'hasTransactions'));
    }

    public function updateStudentEnlistmentApi(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'level' => 'nullable|exists:levels,id',
            'status' => 'nullable|in:regular,irregular',
        ]);

        if (isset($validated['level'])) {
            $student->level_id = $validated['level'];
        }
        if (isset($validated['status'])) {
            $student->status = $validated['status'];
        }
        $student->save();

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully.',
        ]);
    }

    public function getSubjectOfferingsApi(Request $request, $academicTermId)
    {
        $user = auth()->user();
        $departmentId = $user->department_id;

        $query = SubjectOffering::with(['subject', 'academicTerm'])
            ->where('academic_term_id', $academicTermId)
            ->where('department_id', $departmentId);

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('subject', function ($subQ) use ($search) {
                      $subQ->where('code', 'like', "%{$search}%")
                           ->orWhere('description', 'like', "%{$search}%");
                  });
            });
        }

        $offerings = $query->orderBy('code')->get();

        return response()->json([
            'success' => true,
            'data' => $offerings->map(function ($offering) {
                return [
                    'id' => $offering->id,
                    'code' => $offering->code,
                    'description' => $offering->description,
                    'subject_code' => $offering->subject->code ?? '',
                    'subject_description' => $offering->subject->description ?? '',
                ];
            }),
        ]);
    }

    public function getSectionsApi(Request $request, $academicTermId)
    {
        $user = auth()->user();
        $departmentId = $user->department_id;

        // Get all offering codes like "BSCS-INTROCOM-1A" (PROG-SUB-YearLevelLetter)
        $codes = SubjectOffering::where('academic_term_id', $academicTermId)
            ->where('department_id', $departmentId)
            ->pluck('code');

        // Extract unique program-level-section combinations (e.g., "BSCS-1A", "BSCpE-2B")
        $sections = $codes->map(function ($code) {
            $parts = explode('-', $code);
            if (count($parts) >= 3) {
                $program = $parts[0];
                $sectionPart = end($parts); // e.g., "1A" or "2B"
                // Extract level number and letter (e.g., "1A" -> level=1, letter=A)
                if (preg_match('/^(\d+)([A-Z])$/i', $sectionPart, $matches)) {
                    return $program . '-' . strtoupper($sectionPart);
                }
                // Fallback for old format without level number
                if (strlen($sectionPart) === 1 && ctype_alpha($sectionPart)) {
                    return $program . '-' . strtoupper($sectionPart);
                }
            }
            return null;
        })->filter()->unique()->sort()->values();

        return response()->json([
            'success' => true,
            'data' => $sections,
        ]);
    }

    public function getStudentEnlistmentsApi(Request $request, $studentId, $academicTermId)
    {
        $enlistments = Enlistment::with(['subjectOffering.subject'])
            ->where('student_id', $studentId)
            ->where('academic_term_id', $academicTermId)
            ->get();

        // Check if student has transactions for this term
        $hasTransactions = Transaction::where('student_id', $studentId)
            ->where('academic_term_id', $academicTermId)
            ->exists();

        return response()->json([
            'success' => true,
            'has_transactions' => $hasTransactions,
            'data' => $enlistments->map(function ($enlistment) {
                return [
                    'id' => $enlistment->id,
                    'subject_offering_id' => $enlistment->subject_offering_id,
                    'code' => $enlistment->subjectOffering->code ?? '',
                    'description' => $enlistment->subjectOffering->description ?? '',
                    'subject_code' => $enlistment->subjectOffering->subject->code ?? '',
                    'subject_description' => $enlistment->subjectOffering->subject->description ?? '',
                    'final_grade' => $enlistment->final_grade,
                ];
            }),
        ]);
    }

    public function addEnlistmentApi(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'subject_offering_id' => 'required|exists:subject_offerings,id',
        ]);

        // Block adding if student already has a downpayment (transaction) for this term
        $hasTransactions = Transaction::where('student_id', $validated['student_id'])
            ->where('academic_term_id', $validated['academic_term_id'])
            ->exists();

        if ($hasTransactions) {
            return response()->json([
                'success' => false,
                'message' => 'Downpayment is already paid, cannot add/delete subject.',
            ], 400);
        }

        $exists = Enlistment::where('student_id', $validated['student_id'])
            ->where('academic_term_id', $validated['academic_term_id'])
            ->where('subject_offering_id', $validated['subject_offering_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Subject already enlisted.',
            ], 400);
        }

        Enlistment::create($validated);

        // Auto-add subject fees to student_fees
        $subjectOffering = SubjectOffering::find($validated['subject_offering_id']);
        if ($subjectOffering) {
            $this->addSubjectFeesToStudent(
                $validated['student_id'],
                $subjectOffering->subject_id,
                $validated['academic_term_id']
            );
        }

        // Auto-update student_type from 'new' to 'old' if student has enlistments in a different term
        $this->updateStudentTypeIfNeeded($validated['student_id'], $validated['academic_term_id']);

        return response()->json([
            'success' => true,
            'message' => 'Subject added successfully.',
        ]);
    }

    public function addEnlistmentBySectionApi(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'section_code' => 'required|string',
        ]);

        // Block adding if student already has a downpayment (transaction) for this term
        $hasTransactions = Transaction::where('student_id', $validated['student_id'])
            ->where('academic_term_id', $validated['academic_term_id'])
            ->exists();

        if ($hasTransactions) {
            return response()->json([
                'success' => false,
                'message' => 'Downpayment is already paid, cannot add/delete subject.',
            ], 400);
        }

        $user = auth()->user();
        $departmentId = $user->department_id;

        $sectionParts = explode('-', $validated['section_code']);
        $program = $sectionParts[0] ?? '';
        $sectionLetter = $sectionParts[1] ?? '';

        $offerings = SubjectOffering::where('academic_term_id', $validated['academic_term_id'])
            ->where('department_id', $departmentId)
            ->where('code', 'like', $program . '-%')
            ->where('code', 'like', '%-' . $sectionLetter)
            ->get();

        $added = 0;
        $skipped = 0;

        foreach ($offerings as $offering) {
            $exists = Enlistment::where('student_id', $validated['student_id'])
                ->where('academic_term_id', $validated['academic_term_id'])
                ->where('subject_offering_id', $offering->id)
                ->exists();

            if (!$exists) {
                Enlistment::create([
                    'student_id' => $validated['student_id'],
                    'academic_term_id' => $validated['academic_term_id'],
                    'subject_offering_id' => $offering->id,
                ]);

                // Auto-add subject fees to student_fees
                $this->addSubjectFeesToStudent(
                    $validated['student_id'],
                    $offering->subject_id,
                    $validated['academic_term_id']
                );

                $added++;
            } else {
                $skipped++;
            }
        }

        // Auto-update student_type from 'new' to 'old' if student has enlistments in a different term
        if ($added > 0) {
            $this->updateStudentTypeIfNeeded($validated['student_id'], $validated['academic_term_id']);
        }

        return response()->json([
            'success' => true,
            'message' => "Added {$added} subject(s). Skipped {$skipped} already enlisted.",
        ]);
    }

    public function removeEnlistmentApi(Request $request, $id)
    {
        $enlistment = Enlistment::with('subjectOffering')->findOrFail($id);

        // Auto-remove subject fees from student_fees
        if ($enlistment->subjectOffering) {
            $this->removeSubjectFeesFromStudent(
                $enlistment->student_id,
                $enlistment->subjectOffering->subject_id,
                $enlistment->academic_term_id
            );
        }

        $enlistment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subject removed successfully.',
        ]);
    }

    /**
     * Add subject fees to student_fees table.
     * For each fee linked to the subject via subject_fees,
     * create a student_fee entry if it doesn't already exist.
     */
    private function addSubjectFeesToStudent($studentId, $subjectId, $academicTermId)
    {
        $subjectFees = SubjectFee::where('subject_id', $subjectId)->get();

        foreach ($subjectFees as $subjectFee) {
            $exists = StudentFee::where('student_id', $studentId)
                ->where('fee_id', $subjectFee->fee_id)
                ->where('academic_term_id', $academicTermId)
                ->exists();

            if (!$exists) {
                StudentFee::create([
                    'student_id' => $studentId,
                    'fee_id' => $subjectFee->fee_id,
                    'academic_term_id' => $academicTermId,
                ]);
            }
        }
    }

    /**
     * Remove subject fees from student_fees table.
     * For each fee linked to the subject via subject_fees,
     * delete the corresponding student_fee entry.
     */
    private function removeSubjectFeesFromStudent($studentId, $subjectId, $academicTermId)
    {
        $subjectFees = SubjectFee::where('subject_id', $subjectId)->get();

        foreach ($subjectFees as $subjectFee) {
            StudentFee::where('student_id', $studentId)
                ->where('fee_id', $subjectFee->fee_id)
                ->where('academic_term_id', $academicTermId)
                ->delete();
        }
    }

    /**
     * Update student_type from 'new' to 'old' if the student has
     * enlistment entries in a different academic term.
     */
    private function updateStudentTypeIfNeeded($studentId, $currentAcademicTermId)
    {
        $student = Student::find($studentId);

        if (!$student || $student->student_type === 'old') {
            return; // Already 'old', nothing to do
        }

        // Check if student has enlistments in a DIFFERENT academic term
        $hasPriorEnlistments = Enlistment::where('student_id', $studentId)
            ->where('academic_term_id', '!=', $currentAcademicTermId)
            ->exists();

        if ($hasPriorEnlistments) {
            $student->student_type = 'old';
            $student->save();
        }
    }
}
