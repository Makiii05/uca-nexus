<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admission;
use App\Models\Applicant;
use App\Models\Level;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ApplicantController;

class AdmissionProcessController extends Controller
{
    public function showInterview(Request $request)
    {
        $query = Admission::with(['applicant', 'interviewSchedule'])
            ->whereHas('applicant', function ($query) {
                $query->where('status', 'interview');
            });
        
        // Filter by schedule_id if provided (for proctor view)
        if ($request->has('schedule_id')) {
            $query->where('interview_schedule_id', $request->schedule_id);
        }
        
        $applicants = $query->get();
        $levels = Level::orderBy('program_id')->get();
            
        return view('admission.interview', [
            'applicants' => $applicants,
            'levels' => $levels,
        ]);
    }

    public function showExam(Request $request)
    {
        $query = Admission::with(['applicant', 'examSchedule'])
            ->whereHas('applicant', function ($query) {
                $query->where('status', 'exam');
            });
        
        // Filter by schedule_id if provided (for proctor view)
        if ($request->has('schedule_id')) {
            $query->where('exam_schedule_id', $request->schedule_id);
        }
        
        $applicants = $query->get();
        $levels = Level::orderBy('program_id')->get();

        return view('admission.entrance_exam', [
            'applicants' => $applicants,
            'levels' => $levels,
        ]);    
    }
    
    public function showEvaluation(Request $request)
    {
        $query = Admission::with(['applicant', 'evaluationSchedule'])
            ->where('final_score', '!=', null)
            ->whereHas('applicant', function ($query) {
                $query->where('status', 'evaluation');
            });
        
        // Filter by schedule_id if provided (for proctor view)
        if ($request->has('schedule_id')) {
            $query->where('evaluation_schedule_id', $request->schedule_id);
        }
        
        $applicants = $query->get();
        $levels = Level::orderBy('program_id')->get();

        return view('admission.final_eval', [
            'applicants' => $applicants,
            'levels' => $levels,
        ]);    
    }

    /**
     * Handle actions from interview page (reschedule interview, mark for exam, mark for evaluation)
     */
    public function processInterviewAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:reschedule,markForExamination,markForEvaluation',
            'schedule_id' => 'required|exists:schedules,id',
            'applicant_ids' => 'required|array|min:1',
            'applicant_ids.*' => 'exists:admissions,id',
        ]);

        $action = $validated['action'];
        $admissionIds = $validated['applicant_ids'];
        $scheduleId = $validated['schedule_id'] ?? null;
        $count = count($admissionIds);

        // Ensure admission records exist for all selected applicants
        foreach ($admissionIds as $admissionId) {
            $admission = Admission::find($admissionId);
            if ($admission) {
                ApplicantController::ensureAdmissionExists($admission->applicant_id);
            }
        }

        if ($action === 'reschedule') {
            // Reschedule interview - just update the interview schedule
            Admission::whereIn('id', $admissionIds)->update([
                'interview_schedule_id' => $scheduleId,
            ]);
            return redirect()->route('admission.interview')
                ->with('success', "{$count} applicant(s) rescheduled successfully.");
                
        } elseif ($action === 'markForExamination') {
            // Check if all applicants passed the interview
            $notPassed = Admission::whereIn('id', $admissionIds)
                ->where('interview_result', '!=', 'passed')
                ->count();
            if ($notPassed > 0) {
                return redirect()->route('admission.interview')
                    ->with('error', 'Some selected applicants have not passed the interview.');
            }

            // Update applicants to exam status
            Applicant::whereIn('id', function($query) use ($admissionIds) {
                $query->select('applicant_id')
                      ->from('admissions')
                      ->whereIn('id', $admissionIds);
            })->update(['status' => 'exam']);

            // Update admission records
            Admission::whereIn('id', $admissionIds)->update([
                'exam_schedule_id' => $scheduleId,
                'exam_result' => 'pending',
            ]);
            
            return redirect()->route('admission.interview')
                ->with('success', "{$count} applicant(s) marked for examination successfully.");
                
        } elseif ($action === 'markForEvaluation') {
            // Check if all applicants passed the interview
            $notPassed = Admission::whereIn('id', $admissionIds)
                ->where('interview_result', '!=', 'passed')
                ->count();
            if ($notPassed > 0) {
                return redirect()->route('admission.interview')
                    ->with('error', 'Some selected applicants have not passed the interview.');
            }

            // Update applicants to evaluation status
            Applicant::whereIn('id', function($query) use ($admissionIds) {
                $query->select('applicant_id')
                      ->from('admissions')
                      ->whereIn('id', $admissionIds);
            })->update(['status' => 'evaluation']);

            // Update admission records with final score from interview and evaluation schedule
            foreach ($admissionIds as $admissionId) {
                $admission = Admission::find($admissionId);
                $admission->update([
                    'evaluation_schedule_id' => $scheduleId,
                    'final_score' => $admission->interview_score ?? 0,
                ]);
            }
            
            return redirect()->route('admission.interview')
                ->with('success', "{$count} applicant(s) marked for evaluation successfully.");
        }

        return redirect()->route('admission.interview')
            ->with('error', 'Invalid action.');
    }

    /**
     * Handle actions from exam page (reschedule exam, mark for evaluation)
     */
    public function processExamAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:reschedule,markForInterview,markForEvaluation',
            'schedule_id' => 'required|exists:schedules,id',
            'applicant_ids' => 'required|array|min:1',
            'applicant_ids.*' => 'exists:admissions,id',
        ]);

        $action = $validated['action'];
        $admissionIds = $validated['applicant_ids'];
        $scheduleId = $validated['schedule_id'] ?? null;
        $count = count($admissionIds);

        // Ensure admission records exist for all selected applicants
        foreach ($admissionIds as $admissionId) {
            $admission = Admission::find($admissionId);
            if ($admission) {
                ApplicantController::ensureAdmissionExists($admission->applicant_id);
            }
        }

        if ($action === 'reschedule') {
            // Reschedule exam - just update the exam schedule
            Admission::whereIn('id', $admissionIds)->update([
                'exam_schedule_id' => $scheduleId,
            ]);
            return redirect()->route('admission.exam')
                ->with('success', "{$count} applicant(s) rescheduled successfully.");
                
        } elseif ($action === 'markForInterview') {
            // Check if all applicants passed the exam
            $notPassed = Admission::whereIn('id', $admissionIds)
                ->where('exam_result', '!=', 'passed')
                ->count();
            if ($notPassed > 0) {
                return redirect()->route('admission.exam')
                    ->with('error', 'Some selected applicants have not passed the exam.');
            }

            // Update applicants to interview status
            Applicant::whereIn('id', function($query) use ($admissionIds) {
                $query->select('applicant_id')
                      ->from('admissions')
                      ->whereIn('id', $admissionIds);
            })->update(['status' => 'interview']);

            // Update admission records
            Admission::whereIn('id', $admissionIds)->update([
                'interview_schedule_id' => $scheduleId,
                'interview_result' => 'pending',
            ]);
            
            return redirect()->route('admission.exam')
                ->with('success', "{$count} applicant(s) marked for interview successfully.");
                
        } elseif ($action === 'markForEvaluation') {
            // Check if all applicants passed the exam
            $notPassed = Admission::whereIn('id', $admissionIds)
                ->where('exam_result', '!=', 'passed')
                ->count();
            if ($notPassed > 0) {
                return redirect()->route('admission.exam')
                    ->with('error', 'Some selected applicants have not passed the exam.');
            }

            // Update applicants to evaluation status
            Applicant::whereIn('id', function($query) use ($admissionIds) {
                $query->select('applicant_id')
                      ->from('admissions')
                      ->whereIn('id', $admissionIds);
            })->update(['status' => 'evaluation']);

            // Update admission records with final score and evaluation schedule
            foreach ($admissionIds as $admissionId) {
                $admission = Admission::find($admissionId);
                $admission->update([
                    'evaluation_schedule_id' => $scheduleId,
                    'final_score' => ($admission->interview_score ?? 0) + ($admission->exam_score ?? 0),
                ]);
            }
            
            return redirect()->route('admission.exam')
                ->with('success', "{$count} applicant(s) marked for evaluation successfully.");
        }

        return redirect()->route('admission.exam')
            ->with('error', 'Invalid action.');
    }

    /**
     * Handle actions from evaluation page (reschedule evaluation, admit)
     */
    public function processEvaluationAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:reschedule,admit',
            'schedule_id' => 'required_if:action,reschedule|nullable|exists:schedules,id',
            'applicant_ids' => 'required|array|min:1',
            'applicant_ids.*' => 'exists:admissions,id',
        ]);

        $action = $validated['action'];
        $admissionIds = $validated['applicant_ids'];
        $scheduleId = $validated['schedule_id'] ?? null;
        $count = count($admissionIds);

        // Ensure admission records exist for all selected applicants
        foreach ($admissionIds as $admissionId) {
            $admission = Admission::find($admissionId);
            if ($admission) {
                ApplicantController::ensureAdmissionExists($admission->applicant_id);
            }
        }

        if ($action === 'reschedule') {
            // Reschedule evaluation - just update the evaluation schedule
            Admission::whereIn('id', $admissionIds)->update([
                'evaluation_schedule_id' => $scheduleId,
            ]);
            return redirect()->route('admission.evaluation')
                ->with('success', "{$count} applicant(s) rescheduled successfully.");
                
        } elseif ($action === 'admit') {
            // Use existing admitStudents logic
            return $this->admitStudents($request);
        }

        return redirect()->route('admission.evaluation')
            ->with('error', 'Invalid action.');
    }

    public function updateInterview(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'interview_score' => 'required|string|max:255',
                'interview_remark' => 'required|string',
                'interview_result' => 'required|in:pending,passed,failed',
            ]);

            $admission = Admission::with('applicant')->findOrFail($id);
            $admission->update($validated);

            // Update applicant reject_reason if failed
            if ($validated['interview_result'] === 'failed') {
                $admission->applicant->update(['reject_reason' => 'Failed on Interview']);
            } elseif ($validated['interview_result'] === 'passed') {
                // Clear reject_reason if passed (in case it was previously failed)
                $admission->applicant->update(['reject_reason' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Interview updated successfully.',
                'data' => $admission,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update interview: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateExam(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'math_score' => 'nullable|numeric|min:0',
                'science_score' => 'nullable|numeric|min:0',
                'english_score' => 'nullable|numeric|min:0',
                'filipino_score' => 'nullable|numeric|min:0',
                'abstract_score' => 'nullable|numeric|min:0',
                'exam_score' => 'nullable|numeric|min:0',
                'exam_result' => 'required|in:pending,passed,failed',
            ]);

            $admission = Admission::with('applicant')->findOrFail($id);
            $admission->update($validated);

            // Update applicant reject_reason if failed
            if ($validated['exam_result'] === 'failed') {
                $admission->applicant->update(['reject_reason' => 'Failed on Examination']);
            } elseif ($validated['exam_result'] === 'passed') {
                // Clear reject_reason if passed (in case it was previously failed)
                $admission->applicant->update(['reject_reason' => null]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Exam scores updated successfully.',
                'data' => $admission,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update exam scores: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateEvaluation(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'decision' => 'required|in:accepted,rejected',
                'program' => 'required_if:decision,accepted|nullable|exists:programs,id',
            ]);

            $admission = Admission::with('applicant')->findOrFail($id);
            
            $updateData = [
                'decision' => $validated['decision'],
                'evaluated_by' => auth()->user()->name ?? auth()->user()->email ?? 'Unknown',
                'evaluated_at' => now(),
            ];

            // Only set program_id if accepted
            if ($validated['decision'] === 'accepted') {
                $updateData['program_id'] = $validated['program'];
                // Clear reject_reason if accepted
                $admission->applicant->update(['reject_reason' => null]);
            } else {
                // Update applicant reject_reason if rejected
                $admission->applicant->update(['reject_reason' => 'Rejected on Final Evaluation']);
            }

            $admission->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Evaluation submitted successfully.',
                'data' => $admission,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit evaluation: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function admitStudents(Request $request)
    {
        $validated = $request->validate([
            'applicant_ids' => 'required|array|min:1',
            'applicant_ids.*' => 'exists:admissions,id',
        ]);

        $admittedCount = 0;
        $deniedCount = 0;
        $errors = [];

        foreach ($validated['applicant_ids'] as $admissionId) {
            $admission = Admission::with('applicant')->find($admissionId);
            
            if (!$admission) {
                $errors[] = "Admission ID {$admissionId} not found.";
                continue;
            }

            // Check if decision is accepted
            if ($admission->decision !== 'accepted') {
                $deniedCount++;
                continue;
            }

            // Update applicant status to admitted
            $applicant = $admission->applicant;
            $applicant->update(['status' => 'admitted']);

            // Create student record
            $studentController = new StudentController();
            $result = $studentController->createStudentFromApplicant($applicant, $admission);

            if ($result['success']) {
                $admittedCount++;
            } else {
                $errors[] = $result['message'];
            }
        }

        $message = "{$admittedCount} applicant(s) admitted successfully.";
        if ($deniedCount > 0) {
            $message .= " {$deniedCount} applicant(s) were skipped (not accepted).";
        }
        if (count($errors) > 0) {
            $message .= " Errors: " . implode(', ', $errors);
        }

        return redirect()->route('admission.evaluation')
            ->with($admittedCount > 0 ? 'success' : 'error', $message);
    }

}