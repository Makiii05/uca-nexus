<?php

namespace App\Http\Controllers;

use App\Models\Component;
use App\Models\GradingSystem;
use Illuminate\Http\Request;

class DepartmentGradingSystemController extends Controller
{
    public function index(Request $request)
    {
        $departmentId = $request->user()->department_id;

        $components = Component::where('department_id', $departmentId)
            ->orderBy('code')
            ->get();

        $gradingSystems = GradingSystem::where('department_id', $departmentId)
            ->with(['components' => function ($query) {
                $query->orderBy('code');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('department.grading_systems', [
            'components' => $components,
            'gradingSystems' => $gradingSystems,
        ]);
    }

    public function store(Request $request)
    {
        $departmentId = $request->user()->department_id;

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'component_ids' => 'required|array|min:1',
            'component_ids.*' => 'required|integer|exists:components,id',
        ]);

        $componentIds = collect($validated['component_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $components = Component::where('department_id', $departmentId)
            ->whereIn('id', $componentIds)
            ->get();

        if ($components->count() !== $componentIds->count()) {
            return back()->withErrors([
                'component_ids' => 'One or more selected components are invalid for your department.',
            ])->withInput();
        }

        $totalPercentage = $components->sum('percentage');

        $gradingSystem = GradingSystem::create([
            'description' => $validated['description'],
            'total_percentage' => $totalPercentage,
            'department_id' => $departmentId,
        ]);

        $gradingSystem->components()->sync($componentIds->all());

        return redirect()->route('department.grading_systems.index')->with('success', 'Grading system created successfully.');
    }

    public function update(Request $request, $id)
    {
        $departmentId = $request->user()->department_id;

        $gradingSystem = GradingSystem::where('department_id', $departmentId)->findOrFail($id);

        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'component_ids' => 'required|array|min:1',
            'component_ids.*' => 'required|integer|exists:components,id',
        ]);

        $componentIds = collect($validated['component_ids'])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $components = Component::where('department_id', $departmentId)
            ->whereIn('id', $componentIds)
            ->get();

        if ($components->count() !== $componentIds->count()) {
            return back()->withErrors([
                'component_ids' => 'One or more selected components are invalid for your department.',
            ])->withInput();
        }

        $totalPercentage = $components->sum('percentage');

        $gradingSystem->update([
            'description' => $validated['description'],
            'total_percentage' => $totalPercentage,
        ]);

        $gradingSystem->components()->sync($componentIds->all());

        return redirect()->route('department.grading_systems.index')->with('success', 'Grading system updated successfully.');
    }

    public function destroy(Request $request, $id)
    {
        $departmentId = $request->user()->department_id;

        $gradingSystem = GradingSystem::where('department_id', $departmentId)->findOrFail($id);
        $gradingSystem->components()->detach();
        $gradingSystem->delete();

        return redirect()->route('department.grading_systems.index')->with('success', 'Grading system deleted successfully.');
    }
}
