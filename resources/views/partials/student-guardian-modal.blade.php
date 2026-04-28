<!-- Student Guardian Modal -->
<dialog id="student_guardian_modal" class="modal">
    <div class="modal-box w-11/12 max-w-4xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="font-bold text-2xl mb-2">Family Background</h3>
        <p class="text-gray-500 mb-6">Student: <span id="guardian_modal_student_number" class="font-medium">-</span></p>
        
        <!-- Family Background -->
        <div class="bg-purple-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-lg mb-3 text-primary">Family Information</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Mother -->
                <div class="border-r border-purple-200 pr-4">
                    <h5 class="font-medium text-purple-700 mb-2">Mother</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Name</span>
                            <p class="font-medium" id="guardian_modal_mother_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Occupation</span>
                            <p class="font-medium" id="guardian_modal_mother_occupation">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Contact</span>
                            <p class="font-medium" id="guardian_modal_mother_contact_number">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Monthly Income</span>
                            <p class="font-medium" id="guardian_modal_mother_monthly_income">-</p>
                        </div>
                    </div>
                </div>
                <!-- Father -->
                <div class="border-r border-purple-200 pr-4">
                    <h5 class="font-medium text-purple-700 mb-2">Father</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Name</span>
                            <p class="font-medium" id="guardian_modal_father_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Occupation</span>
                            <p class="font-medium" id="guardian_modal_father_occupation">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Contact</span>
                            <p class="font-medium" id="guardian_modal_father_contact_number">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Monthly Income</span>
                            <p class="font-medium" id="guardian_modal_father_monthly_income">-</p>
                        </div>
                    </div>
                </div>
                <!-- Guardian -->
                <div>
                    <h5 class="font-medium text-purple-700 mb-2">Guardian</h5>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Name</span>
                            <p class="font-medium" id="guardian_modal_guardian_name">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Occupation</span>
                            <p class="font-medium" id="guardian_modal_guardian_occupation">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Contact</span>
                            <p class="font-medium" id="guardian_modal_guardian_contact_number">-</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Monthly Income</span>
                            <p class="font-medium" id="guardian_modal_guardian_monthly_income">-</p>
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
    function openStudentGuardianModal(guardian, studentNumber) {
        document.getElementById('guardian_modal_student_number').textContent = studentNumber || '-';
        
        if (guardian) {
            // Mother
            document.getElementById('guardian_modal_mother_name').textContent = guardian.mother_name || '-';
            document.getElementById('guardian_modal_mother_occupation').textContent = guardian.mother_occupation || '-';
            document.getElementById('guardian_modal_mother_contact_number').textContent = guardian.mother_contact_number || '-';
            document.getElementById('guardian_modal_mother_monthly_income').textContent = guardian.mother_monthly_income || '-';
            
            // Father
            document.getElementById('guardian_modal_father_name').textContent = guardian.father_name || '-';
            document.getElementById('guardian_modal_father_occupation').textContent = guardian.father_occupation || '-';
            document.getElementById('guardian_modal_father_contact_number').textContent = guardian.father_contact_number || '-';
            document.getElementById('guardian_modal_father_monthly_income').textContent = guardian.father_monthly_income || '-';
            
            // Guardian
            document.getElementById('guardian_modal_guardian_name').textContent = guardian.guardian_name || '-';
            document.getElementById('guardian_modal_guardian_occupation').textContent = guardian.guardian_occupation || '-';
            document.getElementById('guardian_modal_guardian_contact_number').textContent = guardian.guardian_contact_number || '-';
            document.getElementById('guardian_modal_guardian_monthly_income').textContent = guardian.guardian_monthly_income || '-';
        } else {
            // Clear all fields
            const fields = ['mother_name', 'mother_occupation', 'mother_contact_number', 'mother_monthly_income',
                           'father_name', 'father_occupation', 'father_contact_number', 'father_monthly_income',
                           'guardian_name', 'guardian_occupation', 'guardian_contact_number', 'guardian_monthly_income'];
            fields.forEach(field => {
                document.getElementById('guardian_modal_' + field).textContent = '-';
            });
        }

        document.getElementById('student_guardian_modal').showModal();
    }
</script>
