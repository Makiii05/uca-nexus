<!-- Applicant Details Modal -->
<dialog id="applicant_modal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-2xl mb-6">Applicant Details</h3>
        
        <!-- Application Info -->
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Application Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Application No.</span>
                    <p class="font-medium" id="modal_application_no">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Status</span>
                    <p id="modal_status_container"><span class="badge" id="modal_status">-</span></p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">LRN</span>
                    <p class="font-medium" id="modal_lrn">-</p>
                </div>
            </div>
        </div>
        
        <!-- Admission Process -->
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Admission Process</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Interview Score</span>
                    <p class="font-medium" id="modal_interview_score">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Examination Score</span>
                    <p class="font-medium" id="modal_examination_score">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Evaluation Score</span>
                    <p class="font-medium" id="modal_evaluation_score">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Interview Remarks</span>
                    <p class="font-medium" id="modal_interview_remarks">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Examination Remarks</span>
                    <p class="font-medium" id="modal_examination_remarks">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Evaluation Remarks</span>
                    <p class="font-medium" id="modal_evaluation_remarks">-</p>
                </div>
            </div>
        </div>

        <!-- Academic Preferences -->
        <div class="bg-blue-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Academic Preferences</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Level</span>
                    <p class="font-medium" id="modal_level">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Student Type</span>
                    <p class="font-medium" id="modal_student_type">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Year Level</span>
                    <p class="font-medium" id="modal_year_level">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Strand</span>
                    <p class="font-medium" id="modal_strand">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">1st Program Choice</span>
                    <p class="font-medium" id="modal_first_program_choice">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">2nd Program Choice</span>
                    <p class="font-medium" id="modal_second_program_choice">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">3rd Program Choice</span>
                    <p class="font-medium" id="modal_third_program_choice">-</p>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-green-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Personal Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Last Name</span>
                    <p class="font-medium" id="modal_last_name">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">First Name</span>
                    <p class="font-medium" id="modal_first_name">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Middle Name</span>
                    <p class="font-medium" id="modal_middle_name">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Sex</span>
                    <p class="font-medium" id="modal_sex">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Birthdate</span>
                    <p class="font-medium" id="modal_birthdate">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Place of Birth</span>
                    <p class="font-medium" id="modal_place_of_birth">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Citizenship</span>
                    <p class="font-medium" id="modal_citizenship">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Religion</span>
                    <p class="font-medium" id="modal_religion">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Civil Status</span>
                    <p class="font-medium" id="modal_civil_status">-</p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="bg-yellow-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Contact Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Present Address</span>
                    <p class="font-medium" id="modal_present_address">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Permanent Address</span>
                    <p class="font-medium" id="modal_permanent_address">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Zip Code</span>
                    <p class="font-medium" id="modal_zip_code">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Email</span>
                    <p class="font-medium" id="modal_email">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Mobile Number</span>
                    <p class="font-medium" id="modal_mobile_number">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Telephone Number</span>
                    <p class="font-medium" id="modal_telephone_number">-</p>
                </div>
            </div>
        </div>

        <!-- Family Background -->
        <div class="bg-purple-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Family Background</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Mother -->
                <div class="border-r border-purple-200 pr-4">
                    <h5 class="font-medium text-purple-700 mb-2">Mother</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Name</span>
                            <p class="font-medium" id="modal_mother_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Occupation</span>
                            <p class="font-medium" id="modal_mother_occupation">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Contact</span>
                            <p class="font-medium" id="modal_mother_contact_number">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Monthly Income</span>
                            <p class="font-medium" id="modal_mother_monthly_income">-</p>
                        </div>
                    </div>
                </div>
                <!-- Father -->
                <div class="border-r border-purple-200 pr-4">
                    <h5 class="font-medium text-purple-700 mb-2">Father</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Name</span>
                            <p class="font-medium" id="modal_father_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Occupation</span>
                            <p class="font-medium" id="modal_father_occupation">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Contact</span>
                            <p class="font-medium" id="modal_father_contact_number">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Monthly Income</span>
                            <p class="font-medium" id="modal_father_monthly_income">-</p>
                        </div>
                    </div>
                </div>
                <!-- Guardian -->
                <div>
                    <h5 class="font-medium text-purple-700 mb-2">Guardian</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Name</span>
                            <p class="font-medium" id="modal_guardian_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Occupation</span>
                            <p class="font-medium" id="modal_guardian_occupation">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Contact</span>
                            <p class="font-medium" id="modal_guardian_contact_number">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Monthly Income</span>
                            <p class="font-medium" id="modal_guardian_monthly_income">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Educational Background -->
        <div class="bg-orange-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Educational Background</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Elementary -->
                <div>
                    <h5 class="font-medium text-orange-700 mb-2">Elementary</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">School Name</span>
                            <p class="font-medium" id="modal_elementary_school_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Address</span>
                            <p class="font-medium" id="modal_elementary_school_address">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Inclusive Years</span>
                            <p class="font-medium" id="modal_elementary_inclusive_years">-</p>
                        </div>
                    </div>
                </div>
                <!-- Junior High School -->
                <div>
                    <h5 class="font-medium text-orange-700 mb-2">Junior High School</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">School Name</span>
                            <p class="font-medium" id="modal_junior_school_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Address</span>
                            <p class="font-medium" id="modal_junior_school_address">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Inclusive Years</span>
                            <p class="font-medium" id="modal_junior_inclusive_years">-</p>
                        </div>
                    </div>
                </div>
                <!-- Senior High School -->
                <div>
                    <h5 class="font-medium text-orange-700 mb-2">Senior High School</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">School Name</span>
                            <p class="font-medium" id="modal_senior_school_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Address</span>
                            <p class="font-medium" id="modal_senior_school_address">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Inclusive Years</span>
                            <p class="font-medium" id="modal_senior_inclusive_years">-</p>
                        </div>
                    </div>
                </div>
                <!-- College -->
                <div>
                    <h5 class="font-medium text-orange-700 mb-2">College</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">School Name</span>
                            <p class="font-medium" id="modal_college_school_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Address</span>
                            <p class="font-medium" id="modal_college_school_address">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Inclusive Years</span>
                            <p class="font-medium" id="modal_college_inclusive_years">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-action">
            <form method="dialog">
                <button class="btn">Close</button>
            </form>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    function openApplicantModal(applicant, admission) {
        // Application Info
        document.getElementById('modal_application_no').textContent = applicant.application_no || '-';
        document.getElementById('modal_lrn').textContent = applicant.lrn || '-';
        
        // Status with badge color
        const statusEl = document.getElementById('modal_status');
        statusEl.textContent = applicant.status || '-';
        statusEl.className = 'badge';
        if (applicant.status === 'pending') {
            statusEl.classList.add('badge-warning');
        } else if (applicant.status === 'approved') {
            statusEl.classList.add('badge-success');
        } else if (applicant.status === 'rejected') {
            statusEl.classList.add('badge-error');
        } else {
            statusEl.classList.add('badge-ghost');
        }

        // Academic Preferences
        document.getElementById('modal_level').textContent = applicant.level || '-';
        document.getElementById('modal_student_type').textContent = applicant.student_type || '-';
        document.getElementById('modal_year_level').textContent = applicant.year_level || '-';
        document.getElementById('modal_strand').textContent = applicant.strand_name || '-';
        // these program are in id format now, we need to convert it to name before displaying

        document.getElementById('modal_first_program_choice').textContent = applicant.first_program_choice_name || '-';
        document.getElementById('modal_second_program_choice').textContent = applicant.second_program_choice_name || '-';
        document.getElementById('modal_third_program_choice').textContent = applicant.third_program_choice_name || '-';
        // document.getElementById('modal_first_program_choice').textContent = applicant.first_program_choice || '-';
        // document.getElementById('modal_second_program_choice').textContent = applicant.second_program_choice || '-';
        // document.getElementById('modal_third_program_choice').textContent = applicant.third_program_choice || '-';

        // Admission Process
        document.getElementById('modal_interview_score').textContent = admission?.interview_score || '-';
        document.getElementById('modal_examination_score').textContent = admission?.exam_score || '-';
        document.getElementById('modal_evaluation_score').textContent = admission?.final_score || '-';
        document.getElementById('modal_interview_remarks').textContent = (!admission?.interview_result || admission?.interview_result === 'pending') ? '-' : admission.interview_result;
        document.getElementById('modal_examination_remarks').textContent = (!admission?.exam_result || admission?.exam_result === 'pending') ? '-' : admission.exam_result;
        document.getElementById('modal_evaluation_remarks').textContent = (!admission?.decision || admission?.decision === 'pending') ? '-' : admission.decision;

        // Personal Information
        document.getElementById('modal_last_name').textContent = applicant.last_name || '-';
        document.getElementById('modal_first_name').textContent = applicant.first_name || '-';
        document.getElementById('modal_middle_name').textContent = applicant.middle_name || '-';
        document.getElementById('modal_sex').textContent = applicant.sex || '-';
        document.getElementById('modal_birthdate').textContent = applicant.birthdate || '-';
        document.getElementById('modal_place_of_birth').textContent = applicant.place_of_birth || '-';
        document.getElementById('modal_citizenship').textContent = applicant.citizenship || '-';
        document.getElementById('modal_religion').textContent = applicant.religion || '-';
        document.getElementById('modal_civil_status').textContent = applicant.civil_status || '-';

        // Contact Information
        document.getElementById('modal_present_address').textContent = applicant.present_address || '-';
        document.getElementById('modal_permanent_address').textContent = applicant.permanent_address || '-';
        document.getElementById('modal_zip_code').textContent = applicant.zip_code || '-';
        document.getElementById('modal_email').textContent = applicant.email || '-';
        document.getElementById('modal_mobile_number').textContent = applicant.mobile_number || '-';
        document.getElementById('modal_telephone_number').textContent = applicant.telephone_number || '-';

        // Family Background - Mother
        document.getElementById('modal_mother_name').textContent = applicant.mother_name || '-';
        document.getElementById('modal_mother_occupation').textContent = applicant.mother_occupation || '-';
        document.getElementById('modal_mother_contact_number').textContent = applicant.mother_contact_number || '-';
        document.getElementById('modal_mother_monthly_income').textContent = applicant.mother_monthly_income || '-';

        // Family Background - Father
        document.getElementById('modal_father_name').textContent = applicant.father_name || '-';
        document.getElementById('modal_father_occupation').textContent = applicant.father_occupation || '-';
        document.getElementById('modal_father_contact_number').textContent = applicant.father_contact_number || '-';
        document.getElementById('modal_father_monthly_income').textContent = applicant.father_monthly_income || '-';

        // Family Background - Guardian
        document.getElementById('modal_guardian_name').textContent = applicant.guardian_name || '-';
        document.getElementById('modal_guardian_occupation').textContent = applicant.guardian_occupation || '-';
        document.getElementById('modal_guardian_contact_number').textContent = applicant.guardian_contact_number || '-';
        document.getElementById('modal_guardian_monthly_income').textContent = applicant.guardian_monthly_income || '-';

        // Educational Background - Elementary
        document.getElementById('modal_elementary_school_name').textContent = applicant.elementary_school_name || '-';
        document.getElementById('modal_elementary_school_address').textContent = applicant.elementary_school_address || '-';
        document.getElementById('modal_elementary_inclusive_years').textContent = applicant.elementary_inclusive_years || '-';

        // Educational Background - Junior High
        document.getElementById('modal_junior_school_name').textContent = applicant.junior_school_name || '-';
        document.getElementById('modal_junior_school_address').textContent = applicant.junior_school_address || '-';
        document.getElementById('modal_junior_inclusive_years').textContent = applicant.junior_inclusive_years || '-';

        // Educational Background - Senior High
        document.getElementById('modal_senior_school_name').textContent = applicant.senior_school_name || '-';
        document.getElementById('modal_senior_school_address').textContent = applicant.senior_school_address || '-';
        document.getElementById('modal_senior_inclusive_years').textContent = applicant.senior_inclusive_years || '-';

        // Educational Background - College
        document.getElementById('modal_college_school_name').textContent = applicant.college_school_name || '-';
        document.getElementById('modal_college_school_address').textContent = applicant.college_school_address || '-';
        document.getElementById('modal_college_inclusive_years').textContent = applicant.college_inclusive_years || '-';

        // Open the modal
        document.getElementById('applicant_modal').showModal();
    }
</script>
