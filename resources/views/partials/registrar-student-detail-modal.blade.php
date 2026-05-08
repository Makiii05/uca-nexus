<!-- Student Details Modal -->
<dialog id="student_detail_modal" class="modal">
    <div class="modal-box w-11/12 max-w-5xl relative">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <div class="flex flex-col md:flex-row gap-6 mb-6">
            <!-- Left side (Info) -->
            <div class="flex-1">
                <h3 class="font-bold text-3xl mb-4">Student Details</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Student Number</span>
                        <p class="font-bold text-lg" id="student_modal_student_number">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Status</span>
                        <p id="student_modal_status_container"><span class="badge" id="student_modal_status">-</span></p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">LRN</span>
                        <p class="font-medium" id="student_modal_lrn">-</p>
                    </div>
                </div>
            </div>

            <!-- Right side (Profile Picture) -->
            <div class="w-full md:w-48 flex flex-col items-center justify-center border p-4 m-4 rounded-lg bg-base-100 shadow-sm relative">
                <div id="pfp_container" class="w-32 h-32 rounded-full bg-gray-200 overflow-hidden flex items-center justify-center mb-3">
                    <img id="pfp_image" src="" class="w-full h-full object-cover hidden" alt="Profile Picture" />
                    <svg id="pfp_placeholder" xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                
                <div id="pfp_actions" class="flex flex-col gap-2 w-full text-center">
                    <label for="pfp_upload" id="pfp_add_btn" class="btn btn-sm btn-primary w-full cursor-pointer hidden">Add Profile Picture</label>
                    <div id="pfp_edit_btns" class="flex gap-2 w-full hidden">
                        <label for="pfp_upload" class="btn btn-sm btn-outline btn-primary flex-1 cursor-pointer">Change</label>
                        <button type="button" class="btn btn-sm btn-outline btn-error flex-1" onclick="deleteProfilePicture()">Delete</button>
                    </div>
                </div>
                
                <input type="file" id="pfp_upload" class="hidden" accept="image/jpeg,image/png,image/gif" onchange="uploadProfilePicture(this)" />
                <input type="hidden" id="pfp_student_id" />
            </div>
        </div>

        <div class="h-[60vh] overflow-y-auto pr-2">
            <!-- Academic Information -->
            <div class="bg-blue-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-primary border-b border-blue-200 pb-2">Academic Information</h4>
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
                <h4 class="font-semibold text-lg mb-3 text-green-700 border-b border-green-200 pb-2">Personal Information</h4>
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

            <!-- Contact Information -->
            <div class="bg-yellow-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-yellow-700 border-b border-yellow-200 pb-2">Contact Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <span class="text-sm text-gray-500">Mobile Number</span>
                        <p class="font-medium" id="student_modal_mobile">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Telephone Number</span>
                        <p class="font-medium" id="student_modal_telephone">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Email</span>
                        <p class="font-medium break-all" id="student_modal_email">-</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-500">Zip Code</span>
                        <p class="font-medium" id="student_modal_zip">-</p>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-sm text-gray-500">Present Address</span>
                        <p class="font-medium" id="student_modal_present_address">-</p>
                    </div>
                    <div class="md:col-span-2">
                        <span class="text-sm text-gray-500">Permanent Address</span>
                        <p class="font-medium" id="student_modal_permanent_address">-</p>
                    </div>
                </div>
            </div>

            <!-- Family Background -->
            <div class="bg-purple-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-purple-700 border-b border-purple-200 pb-2">Family Background</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="border-r border-purple-200 pr-4">
                        <h5 class="font-medium text-purple-600 mb-2">Mother</h5>
                        <p class="text-sm"><span class="text-gray-500">Name:</span> <span id="student_modal_mother_name">-</span></p>
                        <p class="text-sm"><span class="text-gray-500">Occupation:</span> <span id="student_modal_mother_occ">-</span></p>
                        <p class="text-sm"><span class="text-gray-500">Contact:</span> <span id="student_modal_mother_contact">-</span></p>
                        <p class="text-sm"><span class="text-gray-500">Income:</span> <span id="student_modal_mother_income">-</span></p>
                    </div>
                    <div class="border-r border-purple-200 pr-4">
                        <h5 class="font-medium text-purple-600 mb-2">Father</h5>
                        <p class="text-sm"><span class="text-gray-500">Name:</span> <span id="student_modal_father_name">-</span></p>
                        <p class="text-sm"><span class="text-gray-500">Occupation:</span> <span id="student_modal_father_occ">-</span></p>
                        <p class="text-sm"><span class="text-gray-500">Contact:</span> <span id="student_modal_father_contact">-</span></p>
                        <p class="text-sm"><span class="text-gray-500">Income:</span> <span id="student_modal_father_income">-</span></p>
                    </div>
                    <div>
                        <h5 class="font-medium text-purple-600 mb-2">Guardian</h5>
                        <p class="text-sm"><span class="text-gray-500">Name:</span> <span id="student_modal_guardian_name">-</span></p>
                        <p class="text-sm"><span class="text-gray-500">Occupation:</span> <span id="student_modal_guardian_occ">-</span></p>
                        <p class="text-sm"><span class="text-gray-500">Contact:</span> <span id="student_modal_guardian_contact">-</span></p>
                        <p class="text-sm"><span class="text-gray-500">Income:</span> <span id="student_modal_guardian_income">-</span></p>
                    </div>
                </div>
            </div>

            <!-- Educational Background -->
            <div class="bg-orange-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-lg mb-3 text-orange-700 border-b border-orange-200 pb-2">Educational Background</h4>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>School Name</th>
                                <th>Address</th>
                                <th>Inclusive Years</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Elementary</td>
                                <td id="student_modal_elem_name">-</td>
                                <td id="student_modal_elem_address">-</td>
                                <td id="student_modal_elem_years">-</td>
                            </tr>
                            <tr>
                                <td>Junior High School</td>
                                <td id="student_modal_jhs_name">-</td>
                                <td id="student_modal_jhs_address">-</td>
                                <td id="student_modal_jhs_years">-</td>
                            </tr>
                            <tr>
                                <td>Senior High School</td>
                                <td id="student_modal_shs_name">-</td>
                                <td id="student_modal_shs_address">-</td>
                                <td id="student_modal_shs_years">-</td>
                            </tr>
                            <tr>
                                <td>College</td>
                                <td id="student_modal_college_name">-</td>
                                <td id="student_modal_college_address">-</td>
                                <td id="student_modal_college_years">-</td>
                            </tr>
                        </tbody>
                    </table>
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
        document.getElementById('pfp_student_id').value = student.id;
        
        // PFP Logic
        const pfpImage = document.getElementById('pfp_image');
        const pfpPlaceholder = document.getElementById('pfp_placeholder');
        const pfpAddBtn = document.getElementById('pfp_add_btn');
        const pfpEditBtns = document.getElementById('pfp_edit_btns');

        if (student.profile_picture && student.profile_picture.filename) {
            pfpImage.src = '/assets/images/profile_picture/' + student.profile_picture.filename;
            pfpImage.classList.remove('hidden');
            pfpPlaceholder.classList.add('hidden');
            pfpAddBtn.classList.add('hidden');
            pfpEditBtns.classList.remove('hidden');
        } else {
            pfpImage.src = '';
            pfpImage.classList.add('hidden');
            pfpPlaceholder.classList.remove('hidden');
            pfpAddBtn.classList.remove('hidden');
            pfpEditBtns.classList.add('hidden');
        }

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

        // Contact Information
        document.getElementById('student_modal_mobile').textContent = student.contact?.mobile_number || '-';
        document.getElementById('student_modal_telephone').textContent = student.contact?.telephone_number || '-';
        document.getElementById('student_modal_email').textContent = student.contact?.email || '-';
        document.getElementById('student_modal_zip').textContent = student.contact?.zip_code || '-';
        document.getElementById('student_modal_present_address').textContent = student.contact?.present_address || '-';
        document.getElementById('student_modal_permanent_address').textContent = student.contact?.permanent_address || '-';

        // Family Background
        document.getElementById('student_modal_mother_name').textContent = student.guardian?.mother_name || '-';
        document.getElementById('student_modal_mother_occ').textContent = student.guardian?.mother_occupation || '-';
        document.getElementById('student_modal_mother_contact').textContent = student.guardian?.mother_contact_number || '-';
        document.getElementById('student_modal_mother_income').textContent = student.guardian?.mother_monthly_income || '-';
        
        document.getElementById('student_modal_father_name').textContent = student.guardian?.father_name || '-';
        document.getElementById('student_modal_father_occ').textContent = student.guardian?.father_occupation || '-';
        document.getElementById('student_modal_father_contact').textContent = student.guardian?.father_contact_number || '-';
        document.getElementById('student_modal_father_income').textContent = student.guardian?.father_monthly_income || '-';
        
        document.getElementById('student_modal_guardian_name').textContent = student.guardian?.guardian_name || '-';
        document.getElementById('student_modal_guardian_occ').textContent = student.guardian?.guardian_occupation || '-';
        document.getElementById('student_modal_guardian_contact').textContent = student.guardian?.guardian_contact_number || '-';
        document.getElementById('student_modal_guardian_income').textContent = student.guardian?.guardian_monthly_income || '-';

        // Educational Background
        document.getElementById('student_modal_elem_name').textContent = student.academic_history?.elementary_school_name || '-';
        document.getElementById('student_modal_elem_address').textContent = student.academic_history?.elementary_school_address || '-';
        document.getElementById('student_modal_elem_years').textContent = student.academic_history?.elementary_inclusive_years || '-';
        
        document.getElementById('student_modal_jhs_name').textContent = student.academic_history?.junior_school_name || '-';
        document.getElementById('student_modal_jhs_address').textContent = student.academic_history?.junior_school_address || '-';
        document.getElementById('student_modal_jhs_years').textContent = student.academic_history?.junior_inclusive_years || '-';
        
        document.getElementById('student_modal_shs_name').textContent = student.academic_history?.senior_school_name || '-';
        document.getElementById('student_modal_shs_address').textContent = student.academic_history?.senior_school_address || '-';
        document.getElementById('student_modal_shs_years').textContent = student.academic_history?.senior_inclusive_years || '-';
        
        document.getElementById('student_modal_college_name').textContent = student.academic_history?.college_school_name || '-';
        document.getElementById('student_modal_college_address').textContent = student.academic_history?.college_school_address || '-';
        document.getElementById('student_modal_college_years').textContent = student.academic_history?.college_inclusive_years || '-';

        // Open the modal
        document.getElementById('student_detail_modal').showModal();
    }

    function uploadProfilePicture(input) {
        if (!input.files || input.files.length === 0) return;

        const studentId = document.getElementById('pfp_student_id').value;
        const file = input.files[0];
        const formData = new FormData();
        formData.append('profile_picture', file);

        fetch(`/registrar/official-students/${studentId}/profile-picture`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const pfpImage = document.getElementById('pfp_image');
                pfpImage.src = '/assets/images/profile_picture/' + data.filename;
                pfpImage.classList.remove('hidden');
                document.getElementById('pfp_placeholder').classList.add('hidden');
                document.getElementById('pfp_add_btn').classList.add('hidden');
                document.getElementById('pfp_edit_btns').classList.remove('hidden');
                
                // Show success toast (using daisyui/standard logic if available or just alert)
                alert('Profile picture uploaded successfully.');
                
                // We should update the global row data to have the new profile picture but for now a reload or subsequent modal opens will fetch it
                setTimeout(() => window.location.reload(), 500);
            } else {
                alert('Upload failed: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(err => {
            console.error('Error uploading profile picture:', err);
            alert('Upload failed.');
        });
    }

    function deleteProfilePicture() {
        if (!confirm('Are you sure you want to delete this profile picture?')) return;

        const studentId = document.getElementById('pfp_student_id').value;

        fetch(`/registrar/official-students/${studentId}/profile-picture`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('pfp_image').src = '';
                document.getElementById('pfp_image').classList.add('hidden');
                document.getElementById('pfp_placeholder').classList.remove('hidden');
                document.getElementById('pfp_add_btn').classList.remove('hidden');
                document.getElementById('pfp_edit_btns').classList.add('hidden');
                
                document.getElementById('pfp_upload').value = ''; // Reset input
                alert('Profile picture deleted successfully.');
                setTimeout(() => window.location.reload(), 500);
            }
        })
        .catch(err => {
            console.error('Error deleting profile picture:', err);
            alert('Delete failed.');
        });
    }
</script>
