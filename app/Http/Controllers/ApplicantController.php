<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Level;
use App\Models\Program;
use App\Models\Applicant;
use App\Models\Schedule;
use App\Models\Admission;
use App\Models\AcademicYear;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use Carbon\Carbon;

class ApplicantController extends Controller
{
    //
    public function showApplication(){
        $levels = Level::all()->unique('description');
        $strands =  Program::whereHas('department', function($query){
            $query->where('code', 'SHS');
        })->get();
        $college_programs = Program::whereHas('department', function($query){
            $query->where('description', 'like', '%College%');
        })->get();
        $currentAcademicYear = AcademicYear::getActiveYearLabel()
            ?? AcademicYear::orderByDesc('start_year')->orderByDesc('id')->first()?->label;

        return view('application', [
            'levels' => $levels,
            'strands' => $strands,
            'college_programs' => $college_programs,
            'currentAcademicYear' => $currentAcademicYear,
        ]);
    }
    
    public function createApplication(Request $request){
        // Validate the request
        $validated = $request->validate([
            "application_no" => "required|unique:applicants,application_no",
            "level" => "required",
            "student_type" => "required",
            "year_level" => "nullable",
            "strand" => "nullable",
            "first_program_choice" => "nullable",
            "second_program_choice" => "nullable",
            "third_program_choice" => "nullable",
            "last_name" => "required",
            "first_name" => "required",
            "middle_name" => "required",
            "sex" => "required",
            "citizenship" => "required",
            "religion" => "required",
            "birthdate" => "required|date",
            "place_of_birth" => "required",
            "civil_status" => "required",
            "present_address" => "required",
            "zip_code" => "required|integer",
            "permanent_address" => "required",
            "telephone_number" => "nullable",
            "mobile_number" => "nullable",
            "email" => "required|email",
            "email_confirmation" => "required|email|same:email",
            "mother_name" => "required",
            "mother_occupation" => "required",
            "mother_contact_number" => "required",
            "mother_monthly_income" => "required|integer",
            "father_name" => "required",
            "father_occupation" => "required",
            "father_contact_number" => "required",
            "father_monthly_income" => "required|integer",
            "guardian_name" => "required",
            "guardian_occupation" => "required",
            "guardian_contact_number" => "required",
            "guardian_monthly_income" => "required|integer",
            "elementary_school_name" => "required",
            "elementary_school_address" => "required",
            "elementary_inclusive_years" => "required",
            "junior_school_name" => "required",
            "junior_school_address" => "required",
            "junior_inclusive_years" => "required",
            "senior_school_name" => "required",
            "senior_school_address" => "required",
            "senior_inclusive_years" => "required",
            "college_school_name" => "required",
            "college_school_address" => "required",
            "college_inclusive_years" => "required",
            "lrn" => "required|integer",
        ]);

        $academicYearLabel = AcademicYear::getActiveYearLabel()
            ?? AcademicYear::orderByDesc('start_year')->orderByDesc('id')->first()?->label;

        if (!$academicYearLabel) {
            return back()
                ->withErrors(['academic_year' => 'No active academic year is set. Please contact the registrar.'])
                ->withInput();
        }

        $payload = $request->except(['email_confirmation']);
        $payload['academic_year'] = $academicYearLabel;
        
        // Check if applicant with this email already exists
        $existingApplicant = Applicant::where('email', $validated['email'])->first();
        
        if($existingApplicant){
            // Update existing applicant (keep original application_no)
            $existingApplicant->update($payload);
            $applicant = $existingApplicant;
            $isNew = false;
        } else {
            // Create new applicant
            $applicant = Applicant::create($payload);
            $isNew = true;
        }
        
        // Send confirmation email
        EmailController::sendApplicationConfirmation($applicant, $isNew);

        // Redirect with success notification
        return redirect()->route('applicant.form')
            ->with('success', true)
            ->with('application_no', $applicant->application_no)
            ->with('applicant_email', $applicant->email)
            ->with('is_new', $isNew);
    }

    public function showApplicant(Request $request){
        $academicYears = DashboardController::getAcademicYearOptions();
        $defaultYear = AcademicYear::getActiveYearLabel() ?? $academicYears->first()?->label ?? '';
        $selectedYear = $request->query('academic_year', $defaultYear);
        $search = $request->query('search', '');
        $sortColumn = $request->query('sort', 'created_at');
        $sortDirection = $request->query('direction', 'desc');

        // Validate sort column to prevent SQL injection
        $allowedSortColumns = ['id', 'application_no', 'first_name', 'last_name', 'status', 'created_at'];
        if (!in_array($sortColumn, $allowedSortColumns)) {
            $sortColumn = 'created_at';
        }
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';

        $query = Applicant::with('admission')
            ->where('academic_year', $selectedYear);

        // Apply search filter across all fields
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('application_no', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%")
                  ->orWhere('student_type', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('sex', 'like', "%{$search}%")
                  ->orWhere('citizenship', 'like', "%{$search}%")
                  ->orWhere('religion', 'like', "%{$search}%")
                  ->orWhere('civil_status', 'like', "%{$search}%")
                  ->orWhere('present_address', 'like', "%{$search}%")
                  ->orWhere('permanent_address', 'like', "%{$search}%")
                  ->orWhere('mobile_number', 'like', "%{$search}%")
                  ->orWhere('telephone_number', 'like', "%{$search}%")
                  ->orWhere('mother_name', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('guardian_name', 'like', "%{$search}%")
                  ->orWhere('elementary_school_name', 'like', "%{$search}%")
                  ->orWhere('junior_school_name', 'like', "%{$search}%")
                  ->orWhere('senior_school_name', 'like', "%{$search}%")
                  ->orWhere('college_school_name', 'like', "%{$search}%");
            });
        }

        // Always show pending applicants first, then sort by applicant ID.
        $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderBy('id', 'asc');

        // Paginate results
        $applicants = $query->paginate(20)->withQueryString();

        return view('admission.applicant', [
            'applicants' => $applicants,
            'academicYears' => $academicYears,
            'selectedYear' => $selectedYear,
            'search' => $search,
            'sortColumn' => $sortColumn,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * API endpoint for searching applicants
     */
    public function searchApplicants(Request $request)
    {
        $search = $request->query('search', '');
        $academicYears = DashboardController::getAcademicYearOptions();
        $defaultYear = AcademicYear::getActiveYearLabel() ?? $academicYears->first()?->label ?? '';
        $academicYear = $request->query('academic_year', $defaultYear);
        $sortColumn = $request->query('sort', 'created_at');
        $sortDirection = $request->query('direction', 'desc');
        $page = $request->query('page', 1);

        // Validate sort column
        $allowedSortColumns = ['id', 'application_no', 'first_name', 'last_name', 'status', 'created_at'];
        if (!in_array($sortColumn, $allowedSortColumns)) {
            $sortColumn = 'created_at';
        }
        $sortDirection = strtolower($sortDirection) === 'asc' ? 'asc' : 'desc';

        $query = Applicant::with('admission')
            ->where('academic_year', $academicYear);

        // Apply search filter across all fields
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('middle_name', 'like', "%{$search}%")
                  ->orWhere('application_no', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('level', 'like', "%{$search}%")
                  ->orWhere('student_type', 'like', "%{$search}%")
                  ->orWhere('lrn', 'like', "%{$search}%")
                  ->orWhere('sex', 'like', "%{$search}%")
                  ->orWhere('citizenship', 'like', "%{$search}%")
                  ->orWhere('religion', 'like', "%{$search}%")
                  ->orWhere('civil_status', 'like', "%{$search}%")
                  ->orWhere('present_address', 'like', "%{$search}%")
                  ->orWhere('permanent_address', 'like', "%{$search}%")
                  ->orWhere('mobile_number', 'like', "%{$search}%")
                  ->orWhere('telephone_number', 'like', "%{$search}%")
                  ->orWhere('mother_name', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('guardian_name', 'like', "%{$search}%")
                  ->orWhere('elementary_school_name', 'like', "%{$search}%")
                  ->orWhere('junior_school_name', 'like', "%{$search}%")
                  ->orWhere('senior_school_name', 'like', "%{$search}%")
                  ->orWhere('college_school_name', 'like', "%{$search}%");
            });
        }

        // Apply sorting
        $query->orderBy($sortColumn, $sortDirection);

        // Paginate results
        $applicants = $query->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $applicants->items(),
            'pagination' => [
                'current_page' => $applicants->currentPage(),
                'last_page' => $applicants->lastPage(),
                'per_page' => $applicants->perPage(),
                'total' => $applicants->total(),
                'from' => $applicants->firstItem(),
                'to' => $applicants->lastItem(),
            ],
            'search' => $search,
            'sort' => $sortColumn,
            'direction' => $sortDirection,
        ]);
    }

    public function editApplicant($id){
        $applicant = Applicant::with('admission')->findOrFail($id);
        $levels = Level::all();
        $strands = Program::whereHas('department', function($query){
            $query->where('code', 'SHS');
        })->get();
        $college_programs = Program::whereHas('department', function($query){
            $query->where('description', 'like', '%College%');
        })->get();

        return view('admission.applicant-edit', [
            'applicant' => $applicant,
            'levels' => $levels,
            'strands' => $strands,
            'college_programs' => $college_programs,
        ]);
    }

    public function updateApplicant(Request $request, $id){
        $applicant = Applicant::findOrFail($id);

        $validated = $request->validate([
            "level" => "nullable",
            "student_type" => "nullable",
            "year_level" => "nullable",
            "strand" => "nullable",
            "first_program_choice" => "nullable",
            "second_program_choice" => "nullable",
            "third_program_choice" => "nullable",
            "last_name" => "required",
            "first_name" => "required",
            "middle_name" => "nullable",
            "sex" => "required",
            "citizenship" => "nullable",
            "religion" => "nullable",
            "birthdate" => "nullable|date",
            "place_of_birth" => "nullable",
            "civil_status" => "nullable",
            "present_address" => "nullable",
            "zip_code" => "nullable|integer",
            "permanent_address" => "nullable",
            "telephone_number" => "nullable",
            "mobile_number" => "nullable",
            "email" => "required|email",
            "mother_name" => "nullable",
            "mother_occupation" => "nullable",
            "mother_contact_number" => "nullable",
            "mother_monthly_income" => "nullable|integer",
            "father_name" => "nullable",
            "father_occupation" => "nullable",
            "father_contact_number" => "nullable",
            "father_monthly_income" => "nullable|integer",
            "guardian_name" => "nullable",
            "guardian_occupation" => "nullable",
            "guardian_contact_number" => "nullable",
            "guardian_monthly_income" => "nullable|integer",
            "elementary_school_name" => "nullable",
            "elementary_school_address" => "nullable",
            "elementary_inclusive_years" => "nullable",
            "junior_school_name" => "nullable",
            "junior_school_address" => "nullable",
            "junior_inclusive_years" => "nullable",
            "senior_school_name" => "nullable",
            "senior_school_address" => "nullable",
            "senior_inclusive_years" => "nullable",
            "college_school_name" => "nullable",
            "college_school_address" => "nullable",
            "college_inclusive_years" => "nullable",
            "lrn" => "nullable|integer",
        ]);

        $applicant->update($validated);

        return redirect()->route('admission.applicant')
            ->with('success', "Applicant '{$applicant->first_name} {$applicant->last_name}' updated successfully.");
    }

    /**
     * Ensure an admission record exists for the given applicant.
     * Creates one if it doesn't exist. Returns the Admission instance.
     */
    public static function ensureAdmissionExists($applicantId, $scheduleId = null)
    {
        return Admission::firstOrCreate(
            ['applicant_id' => $applicantId],
            [
                'interview_result' => 'pending',
                'exam_result' => 'pending',
                'decision' => 'pending',
                'interview_schedule_id' => $scheduleId,
                'exam_schedule_id' => $scheduleId,
                'evaluation_schedule_id' => $scheduleId,
            ]
        );
    }

    public function createApplicantProcess(Request $request){
        $validated = $request->validate([
            'action' => 'required|in:markForInterview,markForExamination,markForEvaluation',
            'schedule_id' => 'required|exists:schedules,id',
            'applicant_ids' => 'required|array|min:1',
            'applicant_ids.*' => 'exists:applicants,id',
        ]);

        $action = $validated['action'];
        $applicantIds = $validated['applicant_ids'];
        $scheduleId = $validated['schedule_id'] ?? null;

        // Get only applicants with 'pending' status
        $pendingApplicants = Applicant::whereIn('id', $applicantIds)
            ->where('status', 'pending')
            ->pluck('id')
            ->toArray();

        if(count($pendingApplicants) === 0){
            return redirect()->route('admission.applicant')
                ->with('error', 'No pending applicants selected. Only applicants with pending status can be processed.');
        }

        $count = count($pendingApplicants);
        $skipped = count($applicantIds) - $count;

        // Create admission records for all pending applicants first
        foreach($pendingApplicants as $applicantId){
            self::ensureAdmissionExists($applicantId, $scheduleId);
        }
        
        if ($action === 'markForInterview') {
            // Update only pending applicants to 'interview' status
            Applicant::whereIn('id', $pendingApplicants)
                ->update(['status' => 'interview']);

            // Update admission records with interview schedule
            Admission::whereIn('applicant_id', $pendingApplicants)->update([
                'interview_schedule_id' => $scheduleId,
                'interview_result' => 'pending',
            ]);
            $message = "{$count} applicant(s) marked for interview successfully.";
            
        } elseif ($action === 'markForExamination') {
            // Update only pending applicants to 'exam' status
            Applicant::whereIn('id', $pendingApplicants)
                ->update(['status' => 'exam']);

            // Update admission records with exam schedule
            Admission::whereIn('applicant_id', $pendingApplicants)->update([
                'exam_schedule_id' => $scheduleId,
                'exam_result' => 'pending',
            ]);
            $message = "{$count} applicant(s) marked for examination successfully.";
            
        } elseif ($action === 'markForEvaluation') {
            // Update only pending applicants to 'evaluation' status
            Applicant::whereIn('id', $pendingApplicants)
                ->update(['status' => 'evaluation']);

            // Update admission records with final_score and evaluation schedule
            Admission::whereIn('applicant_id', $pendingApplicants)->update([
                'final_score' => 0,
                'evaluation_schedule_id' => $scheduleId,
            ]);
            $message = "{$count} applicant(s) marked for evaluation successfully.";
        }

        if($skipped > 0){
            $message .= " {$skipped} applicant(s) were skipped (not in pending status).";
        }

        return redirect()->route('admission.applicant')
            ->with('success', $message);
    }

    public function deleteApplicants(Request $request){
        $validated = $request->validate([
            'applicant_ids' => 'required|array|min:1',
            'applicant_ids.*' => 'exists:applicants,id',
        ]);

        // Get only applicants who are NOT admitted (status is not 'admitted')
        $deletableApplicants = Applicant::whereIn('id', $validated['applicant_ids'])
            ->where('status', '!=', 'admitted')
            ->pluck('id')
            ->toArray();

        if(count($deletableApplicants) === 0){
            return redirect()->route('admission.applicant')
                ->with('error', 'No applicants deleted. Only applicants who are not yet admitted can be deleted.');
        }

        // Delete related admission records first
        Admission::whereIn('applicant_id', $deletableApplicants)->delete();

        // Delete the applicants
        Applicant::whereIn('id', $deletableApplicants)->delete();

        $count = count($deletableApplicants);
        $skipped = count($validated['applicant_ids']) - $count;
        
        $message = "{$count} applicant(s) deleted successfully.";
        if($skipped > 0){
            $message .= " {$skipped} applicant(s) were skipped (already admitted).";
        }

        return redirect()->route('admission.applicant')
            ->with('success', $message);
    }
}
