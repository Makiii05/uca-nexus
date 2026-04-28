<!-- Edit Interview Modal -->
<dialog id="edit_interview_modal" class="modal">
    <div class="modal-box w-11/12 max-w-lg">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="text-lg font-bold mb-4">Edit Interview Score & Remarks</h3>
        
        <!-- Applicant Info -->
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Application No.</span>
                    <p class="font-medium" id="edit_modal_application_no">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Applicant Name</span>
                    <p class="font-medium" id="edit_modal_applicant_name">-</p>
                </div>
            </div>
        </div>

        <form id="editInterviewForm" class="space-y-4">
            <input type="hidden" id="edit_admission_id" name="admission_id">
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Interview Score</span>
                </label>
                <input 
                    type="text" 
                    id="edit_interview_score"
                    name="interview_score" 
                    class="input input-bordered w-full" 
                    placeholder="Enter interview score" 
                    required
                >
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Remarks</span>
                </label>
                <textarea 
                    id="edit_interview_remark"
                    name="interview_remark" 
                    class="textarea textarea-bordered w-full" 
                    rows="3"
                    placeholder="Enter interview remarks"
                    required
                ></textarea>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Result</span>
                </label>
                <select id="edit_interview_result" name="interview_result" class="select select-bordered w-full" required>
                    <option value="pending">Pending</option>
                    <option value="passed">Passed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            <!-- Error Message -->
            <div id="edit_interview_error" class="alert alert-error hidden">
                <span id="edit_interview_error_text"></span>
            </div>

            <!-- Success Message -->
            <div id="edit_interview_success" class="alert alert-success hidden">
                <span>Interview updated successfully!</span>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" onclick="edit_interview_modal.close()">Cancel</button>
                <button type="submit" id="saveInterviewBtn" class="btn btn-primary">
                    <span id="saveInterviewBtnText">Save Changes</span>
                    <span id="saveInterviewBtnLoading" class="hidden loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    function openInterviewEditModal(admission) {
        // Reset form state
        document.getElementById('edit_interview_error').classList.add('hidden');
        document.getElementById('edit_interview_success').classList.add('hidden');
        document.getElementById('saveInterviewBtn').disabled = false;
        
        // Populate applicant info
        document.getElementById('edit_modal_application_no').textContent = admission.applicant?.application_no || '-';
        document.getElementById('edit_modal_applicant_name').textContent = 
            (admission.applicant?.first_name || '') + ' ' + (admission.applicant?.last_name || '');
        
        // Populate form fields
        document.getElementById('edit_admission_id').value = admission.id;
        document.getElementById('edit_interview_score').value = admission.interview_score || '';
        document.getElementById('edit_interview_remark').value = admission.interview_remark || '';
        document.getElementById('edit_interview_result').value = admission.interview_result || 'pending';
        
        // Show modal
        document.getElementById('edit_interview_modal').showModal();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('editInterviewForm');
        const saveBtn = document.getElementById('saveInterviewBtn');
        const saveBtnText = document.getElementById('saveInterviewBtnText');
        const saveBtnLoading = document.getElementById('saveInterviewBtnLoading');
        const errorDiv = document.getElementById('edit_interview_error');
        const errorText = document.getElementById('edit_interview_error_text');
        const successDiv = document.getElementById('edit_interview_success');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Hide messages
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            
            // Show loading state
            saveBtn.disabled = true;
            saveBtnText.textContent = 'Saving...';
            saveBtnLoading.classList.remove('hidden');

            const admissionId = document.getElementById('edit_admission_id').value;
            const formData = {
                interview_score: document.getElementById('edit_interview_score').value,
                interview_remark: document.getElementById('edit_interview_remark').value,
                interview_result: document.getElementById('edit_interview_result').value,
            };

            try {
                const response = await fetch(`/admission/interview/${admissionId}/update`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(formData),
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'An error occurred while saving.');
                }

                // Show success
                successDiv.classList.remove('hidden');
                
                // Update the table row
                updateTableRow(admissionId, formData);

                // Close modal after short delay
                setTimeout(() => {
                    document.getElementById('edit_interview_modal').close();
                    // Optionally reload page to reflect changes
                    // window.location.reload();
                }, 1000);

            } catch (error) {
                errorText.textContent = error.message;
                errorDiv.classList.remove('hidden');
            } finally {
                // Reset button state
                saveBtn.disabled = false;
                saveBtnText.textContent = 'Save Changes';
                saveBtnLoading.classList.add('hidden');
            }
        });

        function updateTableRow(admissionId, formData) {
            // Find and update the table row with new data
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const idCell = row.querySelector('td:nth-child(2)');
                if (idCell && idCell.textContent == admissionId) {
                    const scoreCell = row.querySelector('td:nth-child(5)');
                    const remarkCell = row.querySelector('td:nth-child(6)');
                    const resultCell = row.querySelector('td:nth-child(7)');
                    
                    if (scoreCell) scoreCell.textContent = formData.interview_score || '-';
                    if (remarkCell) remarkCell.textContent = formData.interview_remark || '-';
                    if (resultCell) resultCell.textContent = formData.interview_result;
                }
            });
        }
    });
</script>
