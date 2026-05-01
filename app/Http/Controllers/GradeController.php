<?php

namespace App\Http\Controllers;

use App\Models\GradeColumn;
use App\Models\Grade;
use App\Models\RawScore;
use App\Models\Teacher;
use App\Models\TeacherOffering;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    protected function getLoggedInTeacher(): ?Teacher
    {
        $teacherId = session('teacher_portal_teacher_id');

        if (!$teacherId) {
            return null;
        }

        return Teacher::find($teacherId);
    }

	public function storeGradeColumn(Request $request, $teacherOfferingId)
	{
		$teacher = $this->getLoggedInTeacher();

		if (!$teacher) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}

		$teacherOffering = TeacherOffering::query()
			->where('id', $teacherOfferingId)
			->where('teacher_id', $teacher->id)
			->firstOrFail();

		$validated = $request->validate([
			'component_id' => ['required', 'exists:components,id'],
			'period' => ['required', 'string'],
		]);

		$lastColumnNumber = GradeColumn::query()
			->where('teacher_offering_id', $teacherOffering->id)
			->where('component_id', $validated['component_id'])
			->where('period', $validated['period'])
			->max('column_number');

		$gradeColumn = GradeColumn::create([
			'teacher_offering_id' => $teacherOffering->id,
			'period' => $validated['period'],
			'component_id' => $validated['component_id'],
			'column_number' => ($lastColumnNumber ?? 0) + 1,
			'highest_score' => 0,
		]);

		$grades = Grade::query()
			->where('teacher_offering_id', $teacherOffering->id)
			->where('period', $validated['period'])
			->get();

		foreach ($grades as $grade) {
			RawScore::firstOrCreate([
				'grade_id' => $grade->id,
				'grade_column_id' => $gradeColumn->id,
			], [
				'score' => null,
			]);
		}

		return response()->json([
			'id' => $gradeColumn->id,
			'teacher_offering_id' => $gradeColumn->teacher_offering_id,
			'period' => $gradeColumn->period,
			'component_id' => $gradeColumn->component_id,
			'column_number' => $gradeColumn->column_number,
			'highest_score' => $gradeColumn->highest_score,
		]);
	}

	public function deleteGradeColumn($gradeColumnId)
	{
		$teacher = $this->getLoggedInTeacher();

		if (!$teacher) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}

		$gradeColumn = GradeColumn::query()
			->with('component')
			->whereHas('teacherOffering', function ($query) use ($teacher) {
				$query->where('teacher_id', $teacher->id);
			})
			->findOrFail($gradeColumnId);

		$gradeColumn->rawScores()->delete();
		$gradeColumn->delete();

		return response()->json([
			'message' => 'Grade column deleted successfully',
		]);
	}

	public function updateRawScore(Request $request, $gradeId, $gradeColumnId)
	{
		$validated = $request->validate([
			'score' => ['nullable', 'numeric', 'min:0'],
		]);

		$grade = Grade::query()->findOrFail($gradeId);
		$gradeColumn = GradeColumn::query()->findOrFail($gradeColumnId);

		if ($grade->teacher_offering_id !== $gradeColumn->teacher_offering_id || $grade->period !== $gradeColumn->period) {
			return response()->json(['error' => 'Mismatched grade and grade column'], 422);
		}

		$rawScore = RawScore::updateOrCreate([
			'grade_id' => $grade->id,
			'grade_column_id' => $gradeColumn->id,
		], [
			'score' => $validated['score'],
		]);

		return response()->json([
			'id' => $rawScore->id,
			'grade_id' => $rawScore->grade_id,
			'grade_column_id' => $rawScore->grade_column_id,
			'score' => $rawScore->score,
		]);
	}

	public function updateHighestScore(Request $request, $gradeColumnId)
	{
		$validated = $request->validate([
			'highest_score' => ['required', 'numeric', 'min:0'],
		]);

		$gradeColumn = GradeColumn::with('component')->findOrFail($gradeColumnId);
		$gradeColumn->update([
			'highest_score' => $validated['highest_score'],
		]);

		$componentTotal = GradeColumn::query()
			->where('teacher_offering_id', $gradeColumn->teacher_offering_id)
			->where('period', $gradeColumn->period)
			->where('component_id', $gradeColumn->component_id)
			->sum('highest_score');

		return response()->json([
			'id' => $gradeColumn->id,
			'component_id' => $gradeColumn->component_id,
			'component_code' => $gradeColumn->component?->code ?? '',
			'column_number' => $gradeColumn->column_number,
			'highest_score' => $gradeColumn->highest_score,
			'component_total' => $componentTotal,
		]);
	}

	public function updateGradeStatus(Request $request, $gradeId)
	{
		$validated = $request->validate([
			'status' => ['required', 'string', 'in:draft,submitted,approved,rejected'],
			'initial_grade' => ['nullable', 'numeric'],
			'period_grade' => ['nullable', 'numeric'],
		]);

		$grade = Grade::query()->findOrFail($gradeId);

		// Only allow teacher who owns the offering to change status
		$teacher = $this->getLoggedInTeacher();
		if (!$teacher) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}

		$teacherOffering = TeacherOffering::query()->where('id', $grade->teacher_offering_id)->where('teacher_id', $teacher->id)->first();
		if (!$teacherOffering) {
			return response()->json(['error' => 'Unauthorized'], 401);
		}

		$initialGrade = $validated['initial_grade'] ?? null;
		$periodGrade = $validated['period_grade'] ?? null;

		if ($initialGrade === null || (float) $initialGrade <= 0) {
			return response()->json([
				'id' => $grade->id,
				'status' => $grade->status,
				'skipped' => true,
			]);
		}

		$grade->fill([
			'initial_grade' => $initialGrade,
			'period_grade' => $periodGrade,
			'status' => $validated['status'],
			'submitted_at' => now(),
		]);
		$grade->save();

		return response()->json([
			'id' => $grade->id,
			'status' => $grade->status,
			'initial_grade' => $grade->initial_grade,
			'period_grade' => $grade->period_grade,
		]);
	}
}
