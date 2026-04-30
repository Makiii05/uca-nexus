<?php

namespace App\Http\Controllers;

use App\Models\Component;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DepartmentComponentController extends Controller
{
    public function index(Request $request)
    {
        $departmentId = $request->user()->department_id;

        $components = Component::where('department_id', $departmentId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('department.grading_components', [
            'components' => $components,
        ]);
    }

    public function store(Request $request)
    {
        $departmentId = $request->user()->department_id;

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('components', 'code')->where(function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                }),
            ],
            'description' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        Component::create([
            'code' => strtoupper(trim($validated['code'])),
            'description' => $validated['description'],
            'percentage' => $validated['percentage'],
            'department_id' => $departmentId,
        ]);

        return redirect()->route('department.grading_components.index')->with('success', 'Grading component created successfully.');
    }

    public function update(Request $request, $id)
    {
        $departmentId = $request->user()->department_id;

        $component = Component::where('department_id', $departmentId)->findOrFail($id);

        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('components', 'code')->where(function ($query) use ($departmentId) {
                    $query->where('department_id', $departmentId);
                })->ignore($component->id),
            ],
            'description' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
        ]);

        $component->update([
            'code' => strtoupper(trim($validated['code'])),
            'description' => $validated['description'],
            'percentage' => $validated['percentage'],
        ]);

        return redirect()->route('department.grading_components.index')->with('success', 'Grading component updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $departmentId = $request->user()->department_id;

        $component = Component::where('department_id', $departmentId)->findOrFail($id);
        $component->delete();

        return redirect()->route('department.grading_components.index')->with('success', 'Grading component deleted successfully.');
    }
}
