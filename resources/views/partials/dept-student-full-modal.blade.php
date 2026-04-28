<!-- Department Student Full Detail Modal (combined: details, contact, guardian, academic history) -->
<dialog id="dept_student_full_modal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl max-h-[90vh]">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-2xl mb-2">Student Profile</h3>
        <p class="text-gray-500 mb-4">Student: <span id="dept_modal_student_number_header" class="font-medium">-</span></p>

        <!-- Tabs -->
        <div role="tablist" class="tabs tabs-bordered mb-4">
            <button type="button" role="tab" class="tab tab-active" data-tab="details" onclick="switchDeptTab('details', this)">Details</button>
            <button type="button" role="tab" class="tab" data-tab="contact" onclick="switchDeptTab('contact', this)">Contact</button>
            <button type="button" role="tab" class="tab" data-tab="guardian" onclick="switchDeptTab('guardian', this)">Guardian</button>
            <button type="button" role="tab" class="tab" data-tab="academic" onclick="switchDeptTab('academic', this)">Educational Background</button>
        </div>

        <!-- Tab: Details -->
        <div id="dept_tab_details" class="dept-tab-content">
            <!-- Student Info -->
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-primary">Student Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Student Number</span>
                        <p class="font-medium" id="dept_modal_student_number">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">LRN</span>
                        <p class="font-medium" id="dept_modal_lrn">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Status</span>
                        <p id="dept_modal_status_container"><span class="badge" id="dept_modal_status">-</span></p>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-primary">Academic Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Department</span>
                        <p class="font-medium" id="dept_modal_department">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Program</span>
                        <p class="font-medium" id="dept_modal_program">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Level</span>
                        <p class="font-medium" id="dept_modal_level">-</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="bg-green-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-primary">Personal Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Last Name</span>
                        <p class="font-medium" id="dept_modal_last_name">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">First Name</span>
                        <p class="font-medium" id="dept_modal_first_name">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Middle Name</span>
                        <p class="font-medium" id="dept_modal_middle_name">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Sex</span>
                        <p class="font-medium" id="dept_modal_sex">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Birthdate</span>
                        <p class="font-medium" id="dept_modal_birthdate">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Place of Birth</span>
                        <p class="font-medium" id="dept_modal_place_of_birth">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Citizenship</span>
                        <p class="font-medium" id="dept_modal_citizenship">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Religion</span>
                        <p class="font-medium" id="dept_modal_religion">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Civil Status</span>
                        <p class="font-medium" id="dept_modal_civil_status">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Contact -->
        <div id="dept_tab_contact" class="dept-tab-content hidden">
            <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-primary">Contact Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Email</span>
                        <p class="font-medium" id="dept_modal_email">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Mobile Number</span>
                        <p class="font-medium" id="dept_modal_mobile_number">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Telephone Number</span>
                        <p class="font-medium" id="dept_modal_telephone_number">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Zip Code</span>
                        <p class="font-medium" id="dept_modal_zip_code">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-primary">Address</h4>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Present Address</span>
                        <p class="font-medium" id="dept_modal_present_address">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Permanent Address</span>
                        <p class="font-medium" id="dept_modal_permanent_address">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Guardian -->
        <div id="dept_tab_guardian" class="dept-tab-content hidden">
            <div class="bg-purple-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-primary">Family Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Mother -->
                    <div class="border-r border-purple-200 pr-4">
                        <h5 class="font-medium text-purple-700 mb-2">Mother</h5>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-500">Name</span>
                                <p class="font-medium" id="dept_modal_mother_name">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Occupation</span>
                                <p class="font-medium" id="dept_modal_mother_occupation">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Contact</span>
                                <p class="font-medium" id="dept_modal_mother_contact_number">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Monthly Income</span>
                                <p class="font-medium" id="dept_modal_mother_monthly_income">-</p>
                            </div>
                        </div>
                    </div>
                    <!-- Father -->
                    <div class="border-r border-purple-200 pr-4">
                        <h5 class="font-medium text-purple-700 mb-2">Father</h5>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-500">Name</span>
                                <p class="font-medium" id="dept_modal_father_name">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Occupation</span>
                                <p class="font-medium" id="dept_modal_father_occupation">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Contact</span>
                                <p class="font-medium" id="dept_modal_father_contact_number">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Monthly Income</span>
                                <p class="font-medium" id="dept_modal_father_monthly_income">-</p>
                            </div>
                        </div>
                    </div>
                    <!-- Guardian -->
                    <div>
                        <h5 class="font-medium text-purple-700 mb-2">Guardian</h5>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-500">Name</span>
                                <p class="font-medium" id="dept_modal_guardian_name">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Occupation</span>
                                <p class="font-medium" id="dept_modal_guardian_occupation">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Contact</span>
                                <p class="font-medium" id="dept_modal_guardian_contact_number">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Monthly Income</span>
                                <p class="font-medium" id="dept_modal_guardian_monthly_income">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab: Academic History -->
        <div id="dept_tab_academic" class="dept-tab-content hidden">
            <div class="bg-orange-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-primary">Academic History</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Elementary -->
                    <div>
                        <h5 class="font-medium text-orange-700 mb-2">Elementary</h5>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-500">School Name</span>
                                <p class="font-medium" id="dept_modal_elementary_school_name">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Address</span>
                                <p class="font-medium" id="dept_modal_elementary_school_address">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Inclusive Years</span>
                                <p class="font-medium" id="dept_modal_elementary_inclusive_years">-</p>
                            </div>
                        </div>
                    </div>
                    <!-- Junior High School -->
                    <div>
                        <h5 class="font-medium text-orange-700 mb-2">Junior High School</h5>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-500">School Name</span>
                                <p class="font-medium" id="dept_modal_junior_school_name">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Address</span>
                                <p class="font-medium" id="dept_modal_junior_school_address">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Inclusive Years</span>
                                <p class="font-medium" id="dept_modal_junior_inclusive_years">-</p>
                            </div>
                        </div>
                    </div>
                    <!-- Senior High School -->
                    <div>
                        <h5 class="font-medium text-orange-700 mb-2">Senior High School</h5>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-500">School Name</span>
                                <p class="font-medium" id="dept_modal_senior_school_name">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Address</span>
                                <p class="font-medium" id="dept_modal_senior_school_address">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Inclusive Years</span>
                                <p class="font-medium" id="dept_modal_senior_inclusive_years">-</p>
                            </div>
                        </div>
                    </div>
                    <!-- College -->
                    <div>
                        <h5 class="font-medium text-orange-700 mb-2">College</h5>
                        <div class="space-y-2">
                            <div>
                                <span class="text-sm text-gray-500">School Name</span>
                                <p class="font-medium" id="dept_modal_college_school_name">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Address</span>
                                <p class="font-medium" id="dept_modal_college_school_address">-</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Inclusive Years</span>
                                <p class="font-medium" id="dept_modal_college_inclusive_years">-</p>
                            </div>
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
    function switchDeptTab(tabName, clickedTab) {
        // Hide all tab content
        document.querySelectorAll('.dept-tab-content').forEach(el => el.classList.add('hidden'));
        // Remove active from all tabs
        document.querySelectorAll('[data-tab]').forEach(el => el.classList.remove('tab-active'));
        // Show selected tab content
        document.getElementById('dept_tab_' + tabName).classList.remove('hidden');
        // Mark clicked tab as active
        clickedTab.classList.add('tab-active');
    }

    function openDeptStudentFullModal(student, contact, guardian, academicHistory) {
        // Header
        document.getElementById('dept_modal_student_number_header').textContent = student.student_number || '-';

        // --- Details tab ---
        document.getElementById('dept_modal_student_number').textContent = student.student_number || '-';
        document.getElementById('dept_modal_lrn').textContent = student.lrn || '-';
        
        const statusEl = document.getElementById('dept_modal_status');
        statusEl.textContent = student.status ? student.status.charAt(0).toUpperCase() + student.status.slice(1) : '-';
        statusEl.className = 'badge';
        if (student.status === 'enrolled') statusEl.classList.add('badge-success');
        else if (student.status === 'withdrawn') statusEl.classList.add('badge-warning');
        else if (student.status === 'dropped') statusEl.classList.add('badge-error');
        else if (student.status === 'graduated') statusEl.classList.add('badge-info');
        else statusEl.classList.add('badge-ghost');

        document.getElementById('dept_modal_department').textContent = student.department ? (student.department.code + ' - ' + student.department.description) : '-';
        document.getElementById('dept_modal_program').textContent = student.program ? (student.program.code + ' - ' + student.program.description) : '-';
        document.getElementById('dept_modal_level').textContent = student.level ? (student.level.code + ' - ' + student.level.description) : '-';

        document.getElementById('dept_modal_last_name').textContent = student.last_name || '-';
        document.getElementById('dept_modal_first_name').textContent = student.first_name || '-';
        document.getElementById('dept_modal_middle_name').textContent = student.middle_name || '-';
        document.getElementById('dept_modal_sex').textContent = student.sex || '-';
        document.getElementById('dept_modal_birthdate').textContent = student.birthdate || '-';
        document.getElementById('dept_modal_place_of_birth').textContent = student.place_of_birth || '-';
        document.getElementById('dept_modal_citizenship').textContent = student.citizenship || '-';
        document.getElementById('dept_modal_religion').textContent = student.religion || '-';
        document.getElementById('dept_modal_civil_status').textContent = student.civil_status || '-';

        // --- Contact tab ---
        const contactFields = ['email', 'mobile_number', 'telephone_number', 'zip_code', 'present_address', 'permanent_address'];
        contactFields.forEach(field => {
            document.getElementById('dept_modal_' + field).textContent = (contact && contact[field]) ? contact[field] : '-';
        });

        // --- Guardian tab ---
        const guardianFields = [
            'mother_name', 'mother_occupation', 'mother_contact_number', 'mother_monthly_income',
            'father_name', 'father_occupation', 'father_contact_number', 'father_monthly_income',
            'guardian_name', 'guardian_occupation', 'guardian_contact_number', 'guardian_monthly_income'
        ];
        guardianFields.forEach(field => {
            document.getElementById('dept_modal_' + field).textContent = (guardian && guardian[field]) ? guardian[field] : '-';
        });

        // --- Academic tab ---
        const levels = ['elementary', 'junior', 'senior', 'college'];
        const academicFields = ['school_name', 'school_address', 'inclusive_years'];
        levels.forEach(level => {
            academicFields.forEach(field => {
                const key = level + '_' + field;
                document.getElementById('dept_modal_' + key).textContent = (academicHistory && academicHistory[key]) ? academicHistory[key] : '-';
            });
        });

        // Reset to first tab
        switchDeptTab('details', document.querySelector('[data-tab="details"]'));

        // Open the modal
        document.getElementById('dept_student_full_modal').showModal();
    }
</script>
