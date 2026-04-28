<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SubjectOffering;
use App\Models\Enlistment;
use App\Models\Transaction;
use App\Models\PaymentAccount;
use Illuminate\Support\Facades\DB;

class ClassListController extends Controller
{
    /**
     * Show the class list with subject offerings.
     */
    public function showClassList(Request $request)
    {
        $search = $request->query('search', '');
        $sortBy = $request->query('sort_by', 'code');
        $sortDir = $request->query('sort_dir', 'asc');

        // Subquery to count only enlistments where student has a "Down payment" transaction with active academic term
        $enrolledCountSubquery = Enlistment::selectRaw('count(DISTINCT enlistments.id)')
            ->whereColumn('enlistments.subject_offering_id', 'subject_offerings.id')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('transactions')
                    ->join('payment_accounts', 'transactions.description_id', '=', 'payment_accounts.id')
                    ->join('academic_terms', 'transactions.academic_term_id', '=', 'academic_terms.id')
                    ->whereColumn('transactions.student_id', 'enlistments.student_id')
                    ->where('payment_accounts.description', 'Down payment')
                    ->where('academic_terms.status', 'active');
            });

        $query = SubjectOffering::with(['subject', 'program', 'level'])
            ->addSelect([
                'subject_offerings.*',
                'enrolled_count' => $enrolledCountSubquery
            ]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('subject', function ($subQ) use ($search) {
                    $subQ->where('description', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%");
                })
                ->orWhereHas('program', function ($progQ) use ($search) {
                    $progQ->where('code', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
                ->orWhereHas('level', function ($levelQ) use ($search) {
                    $levelQ->where('description', 'like', "%{$search}%");
                });
            });
        }

        // Handle sorting
        if ($sortBy === 'code') {
            $query->join('subjects', 'subject_offerings.subject_id', '=', 'subjects.id')
                  ->orderBy('subjects.code', $sortDir)
                  ->select(['subject_offerings.*'])
                  ->addSelect(['enrolled_count' => $enrolledCountSubquery]);
        } elseif ($sortBy === 'description') {
            $query->join('subjects', 'subject_offerings.subject_id', '=', 'subjects.id')
                  ->orderBy('subjects.description', $sortDir)
                  ->select(['subject_offerings.*'])
                  ->addSelect(['enrolled_count' => $enrolledCountSubquery]);
        } elseif ($sortBy === 'program') {
            $query->join('programs', 'subject_offerings.program_id', '=', 'programs.id')
                  ->orderBy('programs.code', $sortDir)
                  ->select(['subject_offerings.*'])
                  ->addSelect(['enrolled_count' => $enrolledCountSubquery]);
        } elseif ($sortBy === 'level') {
            $query->leftJoin('levels', 'subject_offerings.level_id', '=', 'levels.id')
                  ->orderBy('levels.order', $sortDir)
                  ->select(['subject_offerings.*'])
                  ->addSelect(['enrolled_count' => $enrolledCountSubquery]);
        } elseif ($sortBy === 'enrolled') {
            $query->orderBy('enrolled_count', $sortDir);
        } else {
            $query->orderBy($sortBy, $sortDir);
        }

        $subjectOfferings = $query->paginate(10)->appends([
            'search' => $search,
            'sort_by' => $sortBy,
            'sort_dir' => $sortDir,
        ]);

        return view('registrar.classlist', [
            'subjectOfferings' => $subjectOfferings,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
        ]);
    }

    /**
     * Show enrolled students for a specific subject offering.
     */
    public function showEnrolledStudents(Request $request, $id)
    {
        $subjectOffering = SubjectOffering::with(['subject', 'program', 'level'])
            ->findOrFail($id);

        // Get enlistments where student has a "Down payment" transaction with active academic term
        $enlistments = Enlistment::where('subject_offering_id', $id)
            ->with(['student', 'student.level', 'student.program'])
            ->whereHas('student', function ($studentQuery) {
                $studentQuery->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('transactions')
                        ->join('payment_accounts', 'transactions.description_id', '=', 'payment_accounts.id')
                        ->join('academic_terms', 'transactions.academic_term_id', '=', 'academic_terms.id')
                        ->whereColumn('transactions.student_id', 'students.id')
                        ->where('payment_accounts.description', 'Down payment')
                        ->where('academic_terms.status', 'active');
                });
            })
            ->get();

        // Separate students by sex
        $femaleStudents = $enlistments->filter(function ($enlistment) {
            return strtolower($enlistment->student->sex ?? '') === 'female';
        })->sortBy(function ($enlistment) {
            return $enlistment->student->last_name . $enlistment->student->first_name;
        });

        $maleStudents = $enlistments->filter(function ($enlistment) {
            return strtolower($enlistment->student->sex ?? '') === 'male';
        })->sortBy(function ($enlistment) {
            return $enlistment->student->last_name . $enlistment->student->first_name;
        });

        // For each student, check if they have a tuition fee transaction and get latest OR
        $femaleStudents = $this->addOrNumberToStudents($femaleStudents);
        $maleStudents = $this->addOrNumberToStudents($maleStudents);

        $yearLevel = $subjectOffering->level?->description ?? 'N/A';

        return view('registrar.classlist_enrolled', [
            'subjectOffering' => $subjectOffering,
            'femaleStudents' => $femaleStudents,
            'maleStudents' => $maleStudents,
            'yearLevel' => $yearLevel,
        ]);
    }

    /**
     * Add OR number column to students who have tuition fee transactions.
     */
    private function addOrNumberToStudents($students)
    {
        return $students->map(function ($enlistment) {
            $latestTuitionTransaction = Transaction::with('paymentType')
                ->where('student_id', $enlistment->student->id)
                ->whereHas('paymentType', function ($query) {
                    $query->where('description', 'like', '%tuition%');
                })
                ->orderBy('date', 'desc')
                ->orderBy('id', 'desc')
                ->first();

            $enlistment->or_number = $latestTuitionTransaction?->or_number;
            return $enlistment;
        });
    }
}
