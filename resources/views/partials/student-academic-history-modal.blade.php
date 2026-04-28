<!-- Student Academic History Modal -->
<dialog id="student_academic_history_modal" class="modal">
    <div class="modal-box w-11/12 max-w-4xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-2xl mb-2">Educational Background</h3>
        <p class="text-gray-500 mb-6">Student: <span id="academic_modal_student_number" class="font-medium">-</span></p>
        
        <!-- Educational Background -->
        <div class="bg-orange-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Academic History</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Elementary -->
                <div>
                    <h5 class="font-medium text-orange-700 mb-2">Elementary</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">School Name</span>
                            <p class="font-medium" id="academic_modal_elementary_school_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Address</span>
                            <p class="font-medium" id="academic_modal_elementary_school_address">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Inclusive Years</span>
                            <p class="font-medium" id="academic_modal_elementary_inclusive_years">-</p>
                        </div>
                    </div>
                </div>
                <!-- Junior High School -->
                <div>
                    <h5 class="font-medium text-orange-700 mb-2">Junior High School</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">School Name</span>
                            <p class="font-medium" id="academic_modal_junior_school_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Address</span>
                            <p class="font-medium" id="academic_modal_junior_school_address">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Inclusive Years</span>
                            <p class="font-medium" id="academic_modal_junior_inclusive_years">-</p>
                        </div>
                    </div>
                </div>
                <!-- Senior High School -->
                <div>
                    <h5 class="font-medium text-orange-700 mb-2">Senior High School</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">School Name</span>
                            <p class="font-medium" id="academic_modal_senior_school_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Address</span>
                            <p class="font-medium" id="academic_modal_senior_school_address">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Inclusive Years</span>
                            <p class="font-medium" id="academic_modal_senior_inclusive_years">-</p>
                        </div>
                    </div>
                </div>
                <!-- College -->
                <div>
                    <h5 class="font-medium text-orange-700 mb-2">College</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">School Name</span>
                            <p class="font-medium" id="academic_modal_college_school_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Address</span>
                            <p class="font-medium" id="academic_modal_college_school_address">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Inclusive Years</span>
                            <p class="font-medium" id="academic_modal_college_inclusive_years">-</p>
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
    function openStudentAcademicHistoryModal(academicHistory, studentNumber) {
        document.getElementById('academic_modal_student_number').textContent = studentNumber || '-';
        
        if (academicHistory) {
            // Elementary
            document.getElementById('academic_modal_elementary_school_name').textContent = academicHistory.elementary_school_name || '-';
            document.getElementById('academic_modal_elementary_school_address').textContent = academicHistory.elementary_school_address || '-';
            document.getElementById('academic_modal_elementary_inclusive_years').textContent = academicHistory.elementary_inclusive_years || '-';
            
            // Junior High
            document.getElementById('academic_modal_junior_school_name').textContent = academicHistory.junior_school_name || '-';
            document.getElementById('academic_modal_junior_school_address').textContent = academicHistory.junior_school_address || '-';
            document.getElementById('academic_modal_junior_inclusive_years').textContent = academicHistory.junior_inclusive_years || '-';
            
            // Senior High
            document.getElementById('academic_modal_senior_school_name').textContent = academicHistory.senior_school_name || '-';
            document.getElementById('academic_modal_senior_school_address').textContent = academicHistory.senior_school_address || '-';
            document.getElementById('academic_modal_senior_inclusive_years').textContent = academicHistory.senior_inclusive_years || '-';
            
            // College
            document.getElementById('academic_modal_college_school_name').textContent = academicHistory.college_school_name || '-';
            document.getElementById('academic_modal_college_school_address').textContent = academicHistory.college_school_address || '-';
            document.getElementById('academic_modal_college_inclusive_years').textContent = academicHistory.college_inclusive_years || '-';
        } else {
            // Clear all fields
            const levels = ['elementary', 'junior', 'senior', 'college'];
            const fields = ['school_name', 'school_address', 'inclusive_years'];
            levels.forEach(level => {
                fields.forEach(field => {
                    document.getElementById('academic_modal_' + level + '_' + field).textContent = '-';
                });
            });
        }

        document.getElementById('student_academic_history_modal').showModal();
    }
</script>
