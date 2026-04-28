<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Program;
use App\Models\Level;
use App\Models\Applicant;
use App\Models\Admission;
use App\Models\AcademicTerm;
use App\Models\StudentContact;
use App\Models\StudentGuardian;
use App\Models\StudentAcademicHistory;
use App\Models\StudentAccount;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function showStudent()
    {
        $students = Student::with(['department', 'program', 'level'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admission.student', compact('students'));
    }

    public function searchStudents(Request $request)
    {
        $search = $request->input('search', '');
        
        if (empty($search)) {
            return response()->json(['data' => []]);
        }
        
        $students = Student::with(['department', 'program', 'level', 'contact', 'guardian', 'academicHistory'])
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
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json(['data' => $students]);
    }

    public function editStudent(Request $request, $id)
    {
        $student = Student::with(['department', 'program', 'level', 'contact', 'guardian', 'academicHistory'])
            ->findOrFail($id);
        
        $departments = Department::all();
        $programs = Program::all();
        $levels = Level::all();
        
        // Detect if coming from department context
        $prefix = $request->route()->getPrefix();
        $view = str_contains($prefix, 'department') ? 'department.student-edit' : 'admission.student-edit';
        
        return view($view, compact('student', 'departments', 'programs', 'levels'));
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        
        // Validate and update student basic info
        $validatedStudent = $request->validate([
            'student_number' => 'required|string|max:50',
            'lrn' => 'nullable|string|max:50',
            'department_id' => 'required|exists:departments,id',
            'program_id' => 'required|exists:programs,id',
            'level_id' => 'required|exists:levels,id',
            'last_name' => 'required|string|max:100',
            'first_name' => 'required|string|max:100',
            'middle_name' => 'nullable|string|max:100',
            'sex' => 'required|string|in:Male,Female',
            'citizenship' => 'nullable|string|max:50',
            'religion' => 'nullable|string|max:50',
            'birthdate' => 'nullable|date',
            'place_of_birth' => 'nullable|string|max:255',
            'civil_status' => 'nullable|string|max:50',
            'status' => 'required|string|in:enrolled,regular,irregular,withdrawn,dropped,graduated',
        ]);
        
        $student->update($validatedStudent);
        
        // Update contact info
        if ($request->has('contact')) {
            $contactData = $request->validate([
                'contact.zip_code' => 'nullable|string|max:20',
                'contact.present_address' => 'nullable|string|max:500',
                'contact.permanent_address' => 'nullable|string|max:500',
                'contact.telephone_number' => 'nullable|string|max:50',
                'contact.mobile_number' => 'nullable|string|max:50',
                'contact.email' => 'nullable|email|max:100',
            ]);
            
            StudentContact::updateOrCreate(
                ['student_id' => $student->id],
                $contactData['contact'] ?? []
            );
        }
        
        // Update guardian info
        if ($request->has('guardian')) {
            $guardianData = $request->validate([
                'guardian.mother_name' => 'nullable|string|max:255',
                'guardian.mother_occupation' => 'nullable|string|max:100',
                'guardian.mother_contact_number' => 'nullable|string|max:50',
                'guardian.mother_monthly_income' => 'nullable|string|max:50',
                'guardian.father_name' => 'nullable|string|max:255',
                'guardian.father_occupation' => 'nullable|string|max:100',
                'guardian.father_contact_number' => 'nullable|string|max:50',
                'guardian.father_monthly_income' => 'nullable|string|max:50',
                'guardian.guardian_name' => 'nullable|string|max:255',
                'guardian.guardian_occupation' => 'nullable|string|max:100',
                'guardian.guardian_contact_number' => 'nullable|string|max:50',
                'guardian.guardian_monthly_income' => 'nullable|string|max:50',
            ]);
            
            StudentGuardian::updateOrCreate(
                ['student_id' => $student->id],
                $guardianData['guardian'] ?? []
            );
        }
        
        // Update academic history
        if ($request->has('academic_history')) {
            $academicData = $request->validate([
                'academic_history.elementary_school_name' => 'nullable|string|max:255',
                'academic_history.elementary_school_address' => 'nullable|string|max:500',
                'academic_history.elementary_inclusive_years' => 'nullable|string|max:50',
                'academic_history.junior_school_name' => 'nullable|string|max:255',
                'academic_history.junior_school_address' => 'nullable|string|max:500',
                'academic_history.junior_inclusive_years' => 'nullable|string|max:50',
                'academic_history.senior_school_name' => 'nullable|string|max:255',
                'academic_history.senior_school_address' => 'nullable|string|max:500',
                'academic_history.senior_inclusive_years' => 'nullable|string|max:50',
                'academic_history.college_school_name' => 'nullable|string|max:255',
                'academic_history.college_school_address' => 'nullable|string|max:500',
                'academic_history.college_inclusive_years' => 'nullable|string|max:50',
            ]);
            
            StudentAcademicHistory::updateOrCreate(
                ['student_id' => $student->id],
                $academicData['academic_history'] ?? []
            );
        }
        
        // Detect if coming from department context
        $prefix = $request->route()->getPrefix();
        $redirectRoute = str_contains($prefix, 'department') ? 'department.student' : 'admission.student';
        
        return redirect()->route($redirectRoute)->with('success', 'Student updated successfully.');
    }

    /**
     * Create a student record from applicant and admission data
     */
    public function createStudentFromApplicant(Applicant $applicant, Admission $admission)
    {
        try {
            // Get the program from admission (assigned during evaluation)
            $program = Program::find($admission->program_id);
            
            if (!$program) {
                return [
                    'success' => false,
                    'message' => "Program not found for applicant {$applicant->application_no}."
                ];
            }

            // Get department from program
            $departmentId = $program->department_id;

            // Get the first level for this program (order = 1 or lowest order)
            $level = Level::where('program_id', $program->id)
                ->orderBy('order', 'asc')
                ->first();

            if (!$level) {
                return [
                    'success' => false,
                    'message' => "No level found for program {$program->code}."
                ];
            }

                // Generate student number (format: PROGRAM-YY-XXXXX)
            $year = date('y'); // 2-digit year (e.g., 26 for 2026)
            $programCode = $program->code;
            $prefix = $programCode . '-' . $year . '-';
            
            $lastStudent = Student::where('student_number', 'like', $prefix . '%')
                ->orderBy('student_number', 'desc')
                ->first();
            
            if ($lastStudent) {
                // Extract the increment number from the last student number
                $lastNumber = (int) substr($lastStudent->student_number, strlen($prefix));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            $studentNumber = $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

            // Create the student record
            $student = Student::create([
                'student_number' => $studentNumber,
                'lrn' => $applicant->lrn,
                'department_id' => $departmentId,
                'program_id' => $program->id,
                'level_id' => $level->id,
                'last_name' => $applicant->last_name,
                'first_name' => $applicant->first_name,
                'middle_name' => $applicant->middle_name,
                'sex' => $applicant->sex,
                'citizenship' => $applicant->citizenship,
                'religion' => $applicant->religion,
                'birthdate' => $applicant->birthdate,
                'place_of_birth' => $applicant->place_of_birth,
                'civil_status' => $applicant->civil_status,
                'application_id' => $applicant->id,
                'status' => 'enrolled',
            ]);

            // Create student contact
            StudentContact::create([
                'student_id' => $student->id,
                'zip_code' => $applicant->zip_code,
                'present_address' => $applicant->present_address,
                'permanent_address' => $applicant->permanent_address,
                'telephone_number' => $applicant->telephone_number,
                'mobile_number' => $applicant->mobile_number,
                'email' => $applicant->email,
            ]);

            // Create student guardian
            StudentGuardian::create([
                'student_id' => $student->id,
                'mother_name' => $applicant->mother_name,
                'mother_occupation' => $applicant->mother_occupation,
                'mother_contact_number' => $applicant->mother_contact_number,
                'mother_monthly_income' => $applicant->mother_monthly_income,
                'father_name' => $applicant->father_name,
                'father_occupation' => $applicant->father_occupation,
                'father_contact_number' => $applicant->father_contact_number,
                'father_monthly_income' => $applicant->father_monthly_income,
                'guardian_name' => $applicant->guardian_name,
                'guardian_occupation' => $applicant->guardian_occupation,
                'guardian_contact_number' => $applicant->guardian_contact_number,
                'guardian_monthly_income' => $applicant->guardian_monthly_income,
            ]);

            // Create student academic history
            StudentAcademicHistory::create([
                'student_id' => $student->id,
                'elementary_school_name' => $applicant->elementary_school_name,
                'elementary_school_address' => $applicant->elementary_school_address,
                'elementary_inclusive_years' => $applicant->elementary_inclusive_years,
                'junior_school_name' => $applicant->junior_school_name,
                'junior_school_address' => $applicant->junior_school_address,
                'junior_inclusive_years' => $applicant->junior_inclusive_years,
                'senior_school_name' => $applicant->senior_school_name,
                'senior_school_address' => $applicant->senior_school_address,
                'senior_inclusive_years' => $applicant->senior_inclusive_years,
                'college_school_name' => $applicant->college_school_name,
                'college_school_address' => $applicant->college_school_address,
                'college_inclusive_years' => $applicant->college_inclusive_years,
            ]);

            // Create student account
            StudentAccount::create([
                'student_id' => $student->id,
                'password' => Hash::make('123'),
            ]);

            return [
                'success' => true,
                'message' => "Student {$studentNumber} created successfully.",
                'student' => $student
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => "Failed to create student for {$applicant->application_no}: " . $e->getMessage()
            ];
        }
    }


    // department students view
    public function showDepartmentStudents(Request $request)
    {
        // Don't load students on initial page load - they will be fetched via API search
        return view('department.students');
    }

    public function searchDepartmentStudents(Request $request)
    {
        $user = $request->user();
        $departmentId = $user->department_id;
        $search = $request->input('search', '');
        
        if (empty($search)) {
            return response()->json(['data' => []]);
        }
        
        $students = Student::with(['department', 'program', 'level', 'contact', 'guardian', 'academicHistory'])
            ->where('department_id', $departmentId)
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
}
