<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AcademicYear;
use App\Models\AcademicTerm;
use App\Models\Department;

class AcademicTermController extends Controller
{
    public function showAcademicTerm()
    {
        $academicTerms = AcademicTerm::orderBy("created_at", "asc")->get();
        $departments = Department::orderBy("created_at", "asc")->get();
        $academicYears = AcademicYear::orderByDesc('start_year')
            ->orderByDesc('id')
            ->get();
        return view('registrar.academic_term', [
            'academicTerms' => $academicTerms,
            'departments' => $departments,
            'academicYears' => $academicYears,
        ]);
    }

    public function createAcademicTerm(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'type' => 'required|in:semester,full year',
            'department' => 'required|integer|exists:departments,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        AcademicTerm::create([
            'code' => $request->code,
            'description' => $request->description,
            'type' => $request->type,
            'department_id' => $request->department,
            'academic_year_id' => $request->academic_year_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.academic_term')->with('success', 'Academic Term created successfully');
    }

    public function updateAcademicTerm(Request $request, $id)
    {
        $academicTerm = AcademicTerm::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:255|unique:academic_terms,code,' . $id,
            'description' => 'required|string|max:255',
            'type' => 'required|in:semester,full year',
            'department' => 'required|integer|exists:departments,id',
            'academic_year_id' => 'required|integer|exists:academic_years,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:active,inactive',
        ]);

        $academicTerm->update([
            'code' => $request->code,
            'description' => $request->description,
            'type' => $request->type,
            'department_id' => $request->department,
            'academic_year_id' => $request->academic_year_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.academic_term')->with('success', 'Academic Term updated successfully');
    }

    public function deleteAcademicTerm($id)
    {
        $academicTerm = AcademicTerm::findOrFail($id);
        $academicTerm->delete();

        return redirect()->route('registrar.academic_term')->with('success', 'Academic Term deleted successfully');
    }
}
