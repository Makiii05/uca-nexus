<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Models\Program;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function showLevel()
    {
        $levels = Level::with('program')->orderBy('id', 'asc')->get();
        $programs = Program::where('status', 'active')->orderBy('id', 'asc')->get();
        
        return view('registrar.level', [
            'levels' => $levels,
            'programs' => $programs
        ]);
    }

    public function createLevel(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:levels',
            'description' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
            'order' => 'required|integer|min:0',
        ]);

        Level::create([
            'code' => $request->code,
            'description' => $request->description,
            'program_id' => $request->program_id,
            'order' => $request->order,
        ]);

        return redirect()->route('registrar.level')->with('success', 'Level created successfully');
    }

    public function updateLevel(Request $request, $id)
    {
        $level = Level::findOrFail($id);

        $request->validate([
            'code' => 'required|string|max:255|unique:levels,code,' . $id,
            'description' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
            'order' => 'required|integer|min:0',
        ]);

        $level->update([
            'code' => $request->code,
            'description' => $request->description,
            'program_id' => $request->program_id,
            'order' => $request->order,
        ]);

        return redirect()->route('registrar.level')->with('success', 'Level updated successfully');
    }

    public function deleteLevel($id)
    {
        $level = Level::findOrFail($id);
        $level->delete();

        return redirect()->route('registrar.level')->with('success', 'Level deleted successfully');
    }
}
