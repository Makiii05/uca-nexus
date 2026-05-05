<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Level;
use App\Models\AcademicTerm;
use App\Models\Enlistment;
use App\Models\Fee;
use App\Models\StudentAccount;
use App\Models\StudentFee;
use App\Models\AssessmentHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function importStudents(Request $request)
    {
        $request->validate([
            'students_csv' => 'required|file|mimes:csv,txt',
        ]);

        $rows = $this->readCsvFile($request->file('students_csv')->getRealPath());
        if (empty($rows)) {
            return back()->with('error', 'Students CSV is empty.');
        }

        $studentRows = [];
        $contactRows = [];
        $guardianRows = [];
        $academicRows = [];

        foreach ($rows as $row) {
            $recordType = strtolower(trim((string) ($row['record_type'] ?? 'student')));
            if ($recordType === 'contact') {
                $contactRows[] = $row;
            } elseif ($recordType === 'guardian') {
                $guardianRows[] = $row;
            } elseif ($recordType === 'academic_history') {
                $academicRows[] = $row;
            } else {
                $studentRows[] = $row;
            }
        }

        if (empty($studentRows)) {
            return back()->with('error', 'No student rows found in the CSV.');
        }

        $inserted = 0;
        $skipped = 0;
        $duplicates = 0;
        $missingRefs = 0;
        $invalidData = 0;
        $insertFailed = 0;

        $contactsUpserted = 0;
        $contactsSkipped = 0;
        $contactsFailed = 0;
        $guardiansUpserted = 0;
        $guardiansSkipped = 0;
        $guardiansFailed = 0;
        $academicsUpserted = 0;
        $academicsSkipped = 0;
        $academicsFailed = 0;

        DB::beginTransaction();
        try {
            foreach ($studentRows as $row) {
                $studentNumber = trim((string) ($row['student_number'] ?? ''));
                if ($studentNumber === '') {
                    $skipped++;
                    $invalidData++;
                    continue;
                }

                if (Student::where('student_number', $studentNumber)->exists()) {
                    $skipped++;
                    $duplicates++;
                    continue;
                }

                $departmentId = $row['department_id'] ?? null;
                $programId = $row['program_id'] ?? null;
                $levelId = $row['level_id'] ?? null;
                $applicationId = $row['application_id'] ?? null;
                $lrn = $row['lrn'] ?? null;

                if (!$departmentId || !$programId || !$levelId || !$applicationId || $lrn === null || $lrn === '') {
                    $skipped++;
                    $invalidData++;
                    continue;
                }

                if (!DB::table('departments')->where('id', $departmentId)->exists()
                    || !DB::table('programs')->where('id', $programId)->exists()
                    || !DB::table('levels')->where('id', $levelId)->exists()
                    || !DB::table('applicants')->where('id', $applicationId)->exists()) {
                    $skipped++;
                    $missingRefs++;
                    continue;
                }

                $birthdate = $this->parseDate($row['birthdate'] ?? null);
                if (!$birthdate) {
                    $skipped++;
                    $invalidData++;
                    continue;
                }

                $createdAt = $this->parseDateTime($row['student_created_at'] ?? $row['created_at'] ?? null)
                    ?? now()->format('Y-m-d H:i:s');
                $updatedAt = $this->parseDateTime($row['student_updated_at'] ?? $row['updated_at'] ?? null)
                    ?? now()->format('Y-m-d H:i:s');

                $insertData = [
                    'student_number' => $studentNumber,
                    'lrn' => $lrn,
                    'department_id' => $departmentId,
                    'program_id' => $programId,
                    'level_id' => $levelId,
                    'last_name' => $row['last_name'] ?? '',
                    'first_name' => $row['first_name'] ?? '',
                    'middle_name' => $row['middle_name'] ?? null,
                    'sex' => $row['sex'] ?? 'Male',
                    'citizenship' => $row['citizenship'] ?? '',
                    'religion' => $row['religion'] ?? '',
                    'birthdate' => $birthdate,
                    'place_of_birth' => $row['place_of_birth'] ?? '',
                    'civil_status' => $row['civil_status'] ?? 'Single',
                    'student_type' => $row['student_type'] ?? 'new',
                    'application_id' => $applicationId,
                    'status' => $row['status'] ?? 'enrolled',
                    'is_exported' => $this->parseBoolean($row['is_exported'] ?? true),
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ];

                if ($insertData['last_name'] === '' || $insertData['first_name'] === '') {
                    $skipped++;
                    $invalidData++;
                    continue;
                }

                try {
                    DB::table('students')->insert($insertData);
                    $inserted++;
                } catch (\Throwable $e) {
                    $skipped++;
                    $insertFailed++;
                }
            }

            $studentNumbers = array_values(array_filter(array_map(function ($row) {
                return trim((string) ($row['student_number'] ?? ''));
            }, $rows)));

            $studentIdByNumber = Student::whereIn('student_number', $studentNumbers)
                ->pluck('id', 'student_number');

            foreach ($contactRows as $row) {
                $studentNumber = trim((string) ($row['student_number'] ?? ''));
                $studentId = $studentIdByNumber[$studentNumber] ?? null;
                if (!$studentId) {
                    $contactsSkipped++;
                    continue;
                }

                $zipCode = $row['contact_zip_code'] ?? null;
                $presentAddress = $row['contact_present_address'] ?? null;
                $permanentAddress = $row['contact_permanent_address'] ?? null;
                $email = $row['contact_email'] ?? null;

                if ($zipCode === null || $presentAddress === null || $permanentAddress === null || $email === null || $email === '') {
                    $contactsSkipped++;
                    continue;
                }

                $contactData = [
                    'student_id' => $studentId,
                    'zip_code' => $zipCode,
                    'present_address' => $presentAddress,
                    'permanent_address' => $permanentAddress,
                    'telephone_number' => $row['contact_telephone_number'] ?? null,
                    'mobile_number' => $row['contact_mobile_number'] ?? null,
                    'email' => $email,
                    'created_at' => $this->parseDateTime($row['contact_created_at'] ?? null) ?? now()->format('Y-m-d H:i:s'),
                    'updated_at' => $this->parseDateTime($row['contact_updated_at'] ?? null) ?? now()->format('Y-m-d H:i:s'),
                ];

                try {
                    DB::table('student_contacts')->updateOrInsert(
                        ['student_id' => $studentId],
                        $contactData
                    );
                    $contactsUpserted++;
                } catch (\Throwable $e) {
                    $contactsFailed++;
                }
            }

            foreach ($guardianRows as $row) {
                $studentNumber = trim((string) ($row['student_number'] ?? ''));
                $studentId = $studentIdByNumber[$studentNumber] ?? null;
                if (!$studentId) {
                    $guardiansSkipped++;
                    continue;
                }

                $motherName = $row['guardian_mother_name'] ?? null;
                $fatherName = $row['guardian_father_name'] ?? null;
                $guardianName = $row['guardian_guardian_name'] ?? null;

                if ($motherName === null || $fatherName === null || $guardianName === null) {
                    $guardiansSkipped++;
                    continue;
                }

                $guardianData = [
                    'student_id' => $studentId,
                    'mother_name' => $motherName,
                    'mother_occupation' => $row['guardian_mother_occupation'] ?? '',
                    'mother_contact_number' => $row['guardian_mother_contact_number'] ?? '',
                    'mother_monthly_income' => $row['guardian_mother_monthly_income'] ?? 0,
                    'father_name' => $fatherName,
                    'father_occupation' => $row['guardian_father_occupation'] ?? '',
                    'father_contact_number' => $row['guardian_father_contact_number'] ?? '',
                    'father_monthly_income' => $row['guardian_father_monthly_income'] ?? 0,
                    'guardian_name' => $guardianName,
                    'guardian_occupation' => $row['guardian_guardian_occupation'] ?? '',
                    'guardian_contact_number' => $row['guardian_guardian_contact_number'] ?? '',
                    'guardian_monthly_income' => $row['guardian_guardian_monthly_income'] ?? 0,
                    'created_at' => $this->parseDateTime($row['guardian_created_at'] ?? null) ?? now()->format('Y-m-d H:i:s'),
                    'updated_at' => $this->parseDateTime($row['guardian_updated_at'] ?? null) ?? now()->format('Y-m-d H:i:s'),
                ];

                try {
                    DB::table('student_guardians')->updateOrInsert(
                        ['student_id' => $studentId],
                        $guardianData
                    );
                    $guardiansUpserted++;
                } catch (\Throwable $e) {
                    $guardiansFailed++;
                }
            }

            foreach ($academicRows as $row) {
                $studentNumber = trim((string) ($row['student_number'] ?? ''));
                $studentId = $studentIdByNumber[$studentNumber] ?? null;
                if (!$studentId) {
                    $academicsSkipped++;
                    continue;
                }

                $elementaryName = $row['academic_elementary_school_name'] ?? null;
                $juniorName = $row['academic_junior_school_name'] ?? null;
                $seniorName = $row['academic_senior_school_name'] ?? null;

                if ($elementaryName === null || $juniorName === null || $seniorName === null) {
                    $academicsSkipped++;
                    continue;
                }

                $academicData = [
                    'student_id' => $studentId,
                    'elementary_school_name' => $elementaryName,
                    'elementary_school_address' => $row['academic_elementary_school_address'] ?? '',
                    'elementary_inclusive_years' => $row['academic_elementary_inclusive_years'] ?? '',
                    'junior_school_name' => $juniorName,
                    'junior_school_address' => $row['academic_junior_school_address'] ?? '',
                    'junior_inclusive_years' => $row['academic_junior_inclusive_years'] ?? '',
                    'senior_school_name' => $seniorName,
                    'senior_school_address' => $row['academic_senior_school_address'] ?? '',
                    'senior_inclusive_years' => $row['academic_senior_inclusive_years'] ?? '',
                    'college_school_name' => $row['academic_college_school_name'] ?? null,
                    'college_school_address' => $row['academic_college_school_address'] ?? null,
                    'college_inclusive_years' => $row['academic_college_inclusive_years'] ?? null,
                    'created_at' => $this->parseDateTime($row['academic_created_at'] ?? null) ?? now()->format('Y-m-d H:i:s'),
                    'updated_at' => $this->parseDateTime($row['academic_updated_at'] ?? null) ?? now()->format('Y-m-d H:i:s'),
                ];

                try {
                    DB::table('student_academic_histories')->updateOrInsert(
                        ['student_id' => $studentId],
                        $academicData
                    );
                    $academicsUpserted++;
                } catch (\Throwable $e) {
                    $academicsFailed++;
                }
            }

            $accountsCreated = 0;
            foreach ($studentIdByNumber as $studentId) {
                if (!StudentAccount::where('student_id', $studentId)->exists()) {
                    StudentAccount::create([
                        'student_id' => $studentId,
                        'account_status' => 'off',
                        'password' => Hash::make('123'),
                    ]);
                    $accountsCreated++;
                }
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Import failed. Please check the CSV format and try again.');
        }

        return back()->with(
            'success',
            "Imported {$inserted} students (skipped {$skipped}). Existing: {$duplicates}, missing refs: {$missingRefs}, invalid: {$invalidData}, failed: {$insertFailed}. Contacts: {$contactsUpserted} upserted, {$contactsSkipped} skipped, {$contactsFailed} failed. Guardians: {$guardiansUpserted} upserted, {$guardiansSkipped} skipped, {$guardiansFailed} failed. Academic histories: {$academicsUpserted} upserted, {$academicsSkipped} skipped, {$academicsFailed} failed. Created {$accountsCreated} student accounts."
        );
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

    private function readCsvFile(string $path): array
    {
        if (!is_readable($path)) {
            return [];
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            return [];
        }

        $rows = [];
        $headers = null;

        while (($data = fgetcsv($handle)) !== false) {
            if ($headers === null) {
                $headers = array_map('trim', $data);
                if (!empty($headers[0])) {
                    $headers[0] = preg_replace('/^\xEF\xBB\xBF/', '', $headers[0]);
                }
                continue;
            }

            if (count(array_filter($data, function ($value) {
                return $value !== null && $value !== '';
            })) === 0) {
                continue;
            }

            $row = [];
            foreach ($headers as $index => $header) {
                if ($header === '') {
                    continue;
                }
                $row[$header] = $data[$index] ?? null;
            }
            $rows[] = $row;
        }

        fclose($handle);

        return $rows;
    }

    private function parseBoolean($value): bool
    {
        $normalized = strtolower(trim((string) $value));
        return in_array($normalized, ['1', 'true', 'yes', 'y'], true);
    }

    private function parseDate($value): ?string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        $formats = ['Y-m-d', 'm/d/Y', 'n/j/Y'];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $raw)->format('Y-m-d');
            } catch (\Throwable $e) {
                // try next format
            }
        }

        try {
            return Carbon::parse($raw)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function parseDateTime($value): ?string
    {
        $raw = trim((string) $value);
        if ($raw === '') {
            return null;
        }

        $formats = [
            'Y-m-d H:i:s',
            'Y-m-d H:i',
            'm/d/Y H:i:s',
            'm/d/Y H:i',
            'n/j/Y H:i:s',
            'n/j/Y H:i',
        ];
        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $raw)->format('Y-m-d H:i:s');
            } catch (\Throwable $e) {
                // try next format
            }
        }

        try {
            return Carbon::parse($raw)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return null;
        }
    }
}
