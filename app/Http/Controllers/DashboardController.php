<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Applicant;
use App\Models\Admission;

class DashboardController extends Controller
{
    /**
     * Hardcoded academic year options.
     */
    public static function getAcademicYearOptions(): array
    {
        return [
            '2024 - 2025',
            '2025 - 2026',
            '2026 - 2027',
            '2027 - 2028',
            '2028 - 2029',
            '2029 - 2030',
        ];
    }

    public function admissionDashboard(Request $request)
    {
        $academicYears = self::getAcademicYearOptions();
        $selectedYear = $request->query('academic_year', '2025 - 2026');

        // Base query: applicants filtered by academic year with their admissions
        $applicantsForYear = Applicant::where('academic_year', $selectedYear);

        $totalApplicants = (clone $applicantsForYear)->count();
        $officialStudents = Student::whereHas('applicant', function ($q) use ($selectedYear) {
            $q->where('academic_year', $selectedYear);
        })->count();

        // // Interviewee: anyone who went through interview (has result passed or failed)
        // $interviewCount = Admission::whereHas('applicant', function ($q) use ($selectedYear) {
        //     $q->where('academic_year', $selectedYear);
        // })->whereIn('interview_result', ['passed', 'failed'])->count();

        // // Examinee: anyone who went through exam (has result passed or failed)
        // $examCount = Admission::whereHas('applicant', function ($q) use ($selectedYear) {
        //     $q->where('academic_year', $selectedYear);
        // })->whereIn('exam_result', ['passed', 'failed'])->count();

        // // Evaluatee: anyone who went through evaluation (decision accepted or rejected)
        // $evaluationCount = Admission::whereHas('applicant', function ($q) use ($selectedYear) {
        //     $q->where('academic_year', $selectedYear);
        // })->whereIn('decision', ['accepted', 'rejected'])->count();

        // // Admitted: decision = accepted
        // $admittedCount = Admission::whereHas('applicant', function ($q) use ($selectedYear) {
        //     $q->where('academic_year', $selectedYear);
        // })->where('decision', 'accepted')->count();

        // interviewee: bse on apllicant status
        $interviewCount = Applicant::where('academic_year', $selectedYear)
            ->whereIn('status', ['interview'])
            ->count();

        // examinee: based on applicant status
        $examCount = Applicant::where('academic_year', $selectedYear)
            ->whereIn('status', ['exam'])
            ->count();

        // evaluationCount: based on applicant status
        $evaluationCount = Applicant::where('academic_year', $selectedYear)
            ->whereIn('status', ['evaluation'])
            ->count();

        // admitted: based on applicant status
        $admittedCount = Applicant::where('academic_year', $selectedYear)
            ->whereIn('status', ['admitted'])
            ->count();

        $variance = $totalApplicants - $admittedCount;

        // Feeder schools filtered by academic year
        $feederSchools = Applicant::select(
                DB::raw("
                    CASE
                        WHEN college_school_name IS NOT NULL AND college_school_name != '' AND college_school_name != 'N/A' THEN college_school_name
                        WHEN senior_school_name IS NOT NULL AND senior_school_name != '' AND senior_school_name != 'N/A' THEN senior_school_name
                        WHEN junior_school_name IS NOT NULL AND junior_school_name != '' AND junior_school_name != 'N/A' THEN junior_school_name
                        WHEN elementary_school_name IS NOT NULL AND elementary_school_name != '' AND elementary_school_name != 'N/A' THEN elementary_school_name
                        ELSE 'Unknown'
                    END as last_school_attended
                "),
                DB::raw("COUNT(*) as total_applicants"),
                DB::raw("SUM(CASE WHEN status IN ('pending', 'interview', 'exam', 'evaluation') THEN 1 ELSE 0 END) as ongoing"),
                DB::raw("SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as failed"),
                DB::raw("SUM(CASE WHEN status = 'admitted' THEN 1 ELSE 0 END) as passed")
            )
            ->where('academic_year', $selectedYear)
            ->groupBy('last_school_attended')
            ->orderByDesc('total_applicants')
            ->get();

        return view('admission.dashboard', compact(
            'academicYears',
            'selectedYear',
            'officialStudents',
            'totalApplicants',
            'interviewCount',
            'examCount',
            'evaluationCount',
            'admittedCount',
            'variance',
            'feederSchools'
        ));
    }
}
