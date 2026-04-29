<?php

namespace App\Http\Controllers;

use App\Models\AcademicYear;
use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function showAcademicYear()
    {
        $academicYears = AcademicYear::orderBy('created_at', 'asc')->get();

        return view('registrar.academic_year', [
            'academicYears' => $academicYears,
        ]);
    }

    public function createAcademicYear(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:academic_years',
            'description' => 'required|string|max:255',
            'start_year' => 'required|string|max:255',
            'end_year' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        AcademicYear::create([
            'code' => $request->code,
            'description' => $request->description,
            'start_year' => $request->start_year,
            'end_year' => $request->end_year,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.academic_year')->with('success', 'Academic Year created successfully');
    }

    public function updateAcademicYear(Request $request, $id)
    {
        $academicYear = AcademicYear::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:255|unique:academic_years,code,' . $id,
            'description' => 'required|string|max:255',
            'start_year' => 'required|string|max:255',
            'end_year' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $academicYear->update([
            'code' => $request->code,
            'description' => $request->description,
            'start_year' => $request->start_year,
            'end_year' => $request->end_year,
            'status' => $request->status,
        ]);

        return redirect()->route('registrar.academic_year')->with('success', 'Academic Year updated successfully');
    }

    public function deleteAcademicYear($id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        $academicYear->delete();

        return redirect()->route('registrar.academic_year')->with('success', 'Academic Year deleted successfully');
    }
}
