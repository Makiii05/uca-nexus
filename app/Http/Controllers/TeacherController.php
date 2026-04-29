<?php

namespace App\Http\Controllers;

use App\Models\TeacherAccount;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function showTeacher()
    {
        $teachers = Teacher::orderBy('created_at', 'asc')
            ->orderBy('status', 'asc')
            ->get();

        return view('registrar.teacher', [
            'teachers' => $teachers,
        ]);
    }

    public function createTeacher(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:teachers,email',
            'status' => 'required|in:active,inactive',
        ]);

        DB::transaction(function () use ($validated) {
            $teacher = Teacher::create($validated);

            TeacherAccount::create([
                'teacher_id' => $teacher->id,
                'password' => Hash::make('123'),
                'status' => 'off',
            ]);
        });

        return redirect()->route('registrar.teacher')->with('success', 'Teacher created successfully');
    }

    public function updateTeacher(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $validated = $request->validate([
            'code' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:teachers,email,' . $teacher->id,
            'status' => 'required|in:active,inactive',
        ]);

        $teacher->update($validated);

        return redirect()->route('registrar.teacher')->with('success', 'Teacher updated successfully');
    }

    public function deleteTeacher($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('registrar.teacher')->with('success', 'Teacher deleted successfully');
    }
}
