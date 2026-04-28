<!-- Student Details Modal -->
<dialog id="student_detail_modal" class="modal">
    <div class="modal-box w-11/12 max-w-4xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-2xl mb-6">Student Details</h3>
        
        <!-- Student Info -->
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Student Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Student Number</span>
                    <p class="font-medium" id="student_modal_student_number">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">LRN</span>
                    <p class="font-medium" id="student_modal_lrn">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Status</span>
                    <p id="student_modal_status_container"><span class="badge" id="student_modal_status">-</span></p>
                </div>
            </div>
        </div>

        <!-- Academic Information -->
        <div class="bg-blue-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Academic Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Department</span>
                    <p class="font-medium" id="student_modal_department">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Program</span>
                    <p class="font-medium" id="student_modal_program">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Level</span>
                    <p class="font-medium" id="student_modal_level">-</p>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-green-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Personal Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Last Name</span>
                    <p class="font-medium" id="student_modal_last_name">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">First Name</span>
                    <p class="font-medium" id="student_modal_first_name">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Middle Name</span>
                    <p class="font-medium" id="student_modal_middle_name">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Sex</span>
                    <p class="font-medium" id="student_modal_sex">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Birthdate</span>
                    <p class="font-medium" id="student_modal_birthdate">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Place of Birth</span>
                    <p class="font-medium" id="student_modal_place_of_birth">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Citizenship</span>
                    <p class="font-medium" id="student_modal_citizenship">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Religion</span>
                    <p class="font-medium" id="student_modal_religion">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Civil Status</span>
                    <p class="font-medium" id="student_modal_civil_status">-</p>
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
    function openStudentDetailModal(student) {
        // Student Info
        document.getElementById('student_modal_student_number').textContent = student.student_number || '-';
        document.getElementById('student_modal_lrn').textContent = student.lrn || '-';
        
        // Status with badge color
        const statusEl = document.getElementById('student_modal_status');
        statusEl.textContent = student.status ? student.status.charAt(0).toUpperCase() + student.status.slice(1) : '-';
        statusEl.className = 'badge';
        if (student.status === 'enrolled') {
            statusEl.classList.add('badge-success');
        } else if (student.status === 'withdrawn') {
            statusEl.classList.add('badge-warning');
        } else if (student.status === 'dropped') {
            statusEl.classList.add('badge-error');
        } else if (student.status === 'graduated') {
            statusEl.classList.add('badge-info');
        } else {
            statusEl.classList.add('badge-ghost');
        }

        // Academic Information
        document.getElementById('student_modal_department').textContent = student.department ? (student.department.code + ' - ' + student.department.description) : '-';
        document.getElementById('student_modal_program').textContent = student.program ? (student.program.code + ' - ' + student.program.description) : '-';
        document.getElementById('student_modal_level').textContent = student.level ? (student.level.code + ' - ' + student.level.description) : '-';

        // Personal Information
        document.getElementById('student_modal_last_name').textContent = student.last_name || '-';
        document.getElementById('student_modal_first_name').textContent = student.first_name || '-';
        document.getElementById('student_modal_middle_name').textContent = student.middle_name || '-';
        document.getElementById('student_modal_sex').textContent = student.sex || '-';
        document.getElementById('student_modal_birthdate').textContent = student.birthdate || '-';
        document.getElementById('student_modal_place_of_birth').textContent = student.place_of_birth || '-';
        document.getElementById('student_modal_citizenship').textContent = student.citizenship || '-';
        document.getElementById('student_modal_religion').textContent = student.religion || '-';
        document.getElementById('student_modal_civil_status').textContent = student.civil_status || '-';

        // Open the modal
        document.getElementById('student_detail_modal').showModal();
    }
</script>