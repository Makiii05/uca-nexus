<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Department;
use App\Models\Program;
use App\Models\Level;
use App\Models\StudentContact;
use App\Models\StudentGuardian;
use App\Models\StudentAcademicHistory;
use App\Models\StudentProfilePicture;
use Illuminate\Support\Facades\File;

class RegistrarOfficialStudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['department', 'program', 'level', 'profilePicture', 'contact', 'guardian', 'academicHistory'])->orderBy('created_at', 'desc')->paginate(20);
        return view('registrar.official_students.index', compact('students'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search', '');
        
        if (empty($search)) {
            return response()->json(['data' => []]);
        }
        
        $students = Student::with(['department', 'program', 'level', 'contact', 'guardian', 'academicHistory', 'profilePicture'])
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

    public function edit($id)
    {
        $student = Student::with(['department', 'program', 'level', 'contact', 'guardian', 'academicHistory'])
            ->findOrFail($id);
        
        $departments = Department::all();
        $programs = Program::all();
        $levels = Level::all();
        
        return view('registrar.official_students.edit', compact('student', 'departments', 'programs', 'levels'));
    }

    public function update(Request $request, $id)
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
        
        return redirect()->route('registrar.official_students')->with('success', 'Student updated successfully.');
    }

    public function uploadProfilePicture(Request $request, $id)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $student = Student::findOrFail($id);
        $file = $request->file('profile_picture');
        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        
        $path = public_path('assets/images/profile_picture');
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $file->move($path, $filename);

        if ($student->profilePicture) {
            $oldFile = public_path('assets/images/profile_picture/' . $student->profilePicture->filename);
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }
            $student->profilePicture->update(['filename' => $filename]);
        } else {
            StudentProfilePicture::create([
                'student_id' => $student->id,
                'filename' => $filename
            ]);
        }

        return response()->json(['success' => true, 'filename' => $filename]);
    }

    public function deleteProfilePicture($id)
    {
        $student = Student::findOrFail($id);
        
        if ($student->profilePicture) {
            $oldFile = public_path('assets/images/profile_picture/' . $student->profilePicture->filename);
            if (File::exists($oldFile)) {
                File::delete($oldFile);
            }
            $student->profilePicture->delete();
        }

        return response()->json(['success' => true]);
    }
}
