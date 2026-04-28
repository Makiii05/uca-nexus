<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Department;
use App\Models\Program;
use App\Models\Student;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RegistrarAuthController extends Controller
{
    public function showLogin(){
        return view('registrar.login');
    }

    public function login(Request $request){
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if(Auth::attempt($validated)){
            $request->session()->regenerate();
            $user = auth()->user();
            if ($user && isset($user->type) && $user->type === 'registrar') {
                return redirect()->route('registrar.dashboard');
            } else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                throw ValidationException::withMessages([
                    'email' => 'You are not authorized as a registrar.',
                ]);
            }
        }
        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);

    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('registrar.login');
    }

    public function showDashboard(){
        $departmentCount = Department::count();
        $programCount = Program::count();
        $studentCount = Student::count();
        $enrollmentStatus = Status::getEnrollmentStatus();

        // Get enrollment statistics by program
        $programs = Program::with('department')->get();
        $enrollmentStats = $programs->map(function ($program) {
            $newMale = Student::where('program_id', $program->id)
                ->where('student_type', 'new')
                ->where('sex', 'Male')
                ->count();
            $newFemale = Student::where('program_id', $program->id)
                ->where('student_type', 'new')
                ->where('sex', 'Female')
                ->count();
            $oldMale = Student::where('program_id', $program->id)
                ->where('student_type', 'old')
                ->where('sex', 'Male')
                ->count();
            $oldFemale = Student::where('program_id', $program->id)
                ->where('student_type', 'old')
                ->where('sex', 'Female')
                ->count();

            return [
                'program_code' => $program->code,
                'program_description' => $program->description,
                'new_male' => $newMale,
                'new_female' => $newFemale,
                'new_total' => $newMale + $newFemale,
                'old_male' => $oldMale,
                'old_female' => $oldFemale,
                'old_total' => $oldMale + $oldFemale,
                'total_male' => $newMale + $oldMale,
                'total_female' => $newFemale + $oldFemale,
                'grand_total' => $newMale + $newFemale + $oldMale + $oldFemale,
            ];
        });

        return view('registrar.dashboard', [
            'departmentCount' => $departmentCount,
            'programCount' => $programCount,
            'studentCount' => $studentCount,
            'enrollmentStatus' => $enrollmentStatus,
            'enrollmentStats' => $enrollmentStats,
        ]);
    }

}
