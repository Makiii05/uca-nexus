<!-- Edit Exam Modal -->
<dialog id="edit_exam_modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="text-lg font-bold mb-4">Edit Exam Scores & Remarks</h3>
        
        <!-- Applicant Info -->
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Application No.</span>
                    <p class="font-medium" id="exam_modal_application_no">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Applicant Name</span>
                    <p class="font-medium" id="exam_modal_applicant_name">-</p>
                </div>
            </div>
        </div>

        <form id="editExamForm" class="space-y-4">
            <input type="hidden" id="edit_exam_admission_id" name="admission_id">
            
            <!-- Score Fields -->
            <div class="grid grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Math Score</span>
                    </label>
                    <input 
                        type="number" 
                        id="edit_math_score"
                        name="math_score" 
                        class="input input-bordered w-full exam-score-input" 
                        placeholder="Enter score" 
                        min="0"
                        step="0.01"
                    >
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Science Score</span>
                    </label>
                    <input 
                        type="number" 
                        id="edit_science_score"
                        name="science_score" 
                        class="input input-bordered w-full exam-score-input" 
                        placeholder="Enter score" 
                        min="0"
                        step="0.01"
                    >
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">English Score</span>
                    </label>
                    <input 
                        type="number" 
                        id="edit_english_score"
                        name="english_score" 
                        class="input input-bordered w-full exam-score-input" 
                        placeholder="Enter score" 
                        min="0"
                        step="0.01"
                    >
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Filipino Score</span>
                    </label>
                    <input 
                        type="number" 
                        id="edit_filipino_score"
                        name="filipino_score" 
                        class="input input-bordered w-full exam-score-input" 
                        placeholder="Enter score" 
                        min="0"
                        step="0.01"
                    >
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Abstract Score</span>
                    </label>
                    <input 
                        type="number" 
                        id="edit_abstract_score"
                        name="abstract_score" 
                        class="input input-bordered w-full exam-score-input" 
                        placeholder="Enter score" 
                        min="0"
                        step="0.01"
                    >
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Total Score</span>
                    </label>
                    <input 
                        type="number" 
                        id="edit_exam_score"
                        name="exam_score" 
                        class="input input-bordered w-full bg-gray-100" 
                        placeholder="Auto-calculated"
                        readonly
                    >
                </div>
            </div>

            <div class="form-control">
                <label class="label">
                    <span class="label-text">Result</span>
                </label>
                <select id="edit_exam_result" name="exam_result" class="select select-bordered w-full" required>
                    <option value="pending">Pending</option>
                    <option value="passed">Passed</option>
                    <option value="failed">Failed</option>
                </select>
            </div>

            <!-- Error Message -->
            <div id="edit_exam_error" class="alert alert-error hidden">
                <span id="edit_exam_error_text"></span>
            </div>

            <!-- Success Message -->
            <div id="edit_exam_success" class="alert alert-success hidden">
                <span>Exam scores updated successfully!</span>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" onclick="edit_exam_modal.close()">Cancel</button>
                <button type="submit" id="saveExamBtn" class="btn btn-primary">
                    <span id="saveExamBtnText">Save Changes</span>
                    <span id="saveExamBtnLoading" class="hidden loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    function openExamEditModal(admission) {
        // Reset form state
        document.getElementById('edit_exam_error').classList.add('hidden');
        document.getElementById('edit_exam_success').classList.add('hidden');
        document.getElementById('saveExamBtn').disabled = false;
        
        // Populate applicant info
        document.getElementById('exam_modal_application_no').textContent = admission.applicant?.application_no || '-';
        document.getElementById('exam_modal_applicant_name').textContent = 
            (admission.applicant?.first_name || '') + ' ' + (admission.applicant?.last_name || '');
        
        // Populate form fields
        document.getElementById('edit_exam_admission_id').value = admission.id;
        document.getElementById('edit_math_score').value = admission.math_score || '';
        document.getElementById('edit_science_score').value = admission.science_score || '';
        document.getElementById('edit_english_score').value = admission.english_score || '';
        document.getElementById('edit_filipino_score').value = admission.filipino_score || '';
        document.getElementById('edit_abstract_score').value = admission.abstract_score || '';
        document.getElementById('edit_exam_score').value = admission.exam_score || '';
        document.getElementById('edit_exam_result').value = admission.exam_result || 'pending';
        
        // Calculate total score
        calculateTotalScore();
        
        // Show modal
        document.getElementById('edit_exam_modal').showModal();
    }

    function calculateTotalScore() {
        const mathScore = parseFloat(document.getElementById('edit_math_score').value) || 0;
        const scienceScore = parseFloat(document.getElementById('edit_science_score').value) || 0;
        const englishScore = parseFloat(document.getElementById('edit_english_score').value) || 0;
        const filipinoScore = parseFloat(document.getElementById('edit_filipino_score').value) || 0;
        const abstractScore = parseFloat(document.getElementById('edit_abstract_score').value) || 0;
        
        const total = mathScore + scienceScore + englishScore + filipinoScore + abstractScore;
        document.getElementById('edit_exam_score').value = total;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Add event listeners to score inputs for auto-calculation
        const scoreInputs = document.querySelectorAll('.exam-score-input');
        scoreInputs.forEach(input => {
            input.addEventListener('input', calculateTotalScore);
        });

        const form = document.getElementById('editExamForm');
        const saveBtn = document.getElementById('saveExamBtn');
        const saveBtnText = document.getElementById('saveExamBtnText');
        const saveBtnLoading = document.getElementById('saveExamBtnLoading');
        const errorDiv = document.getElementById('edit_exam_error');
        const errorText = document.getElementById('edit_exam_error_text');
        const successDiv = document.getElementById('edit_exam_success');

        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            // Hide messages
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            
            // Show loading state
            saveBtn.disabled = true;
            saveBtnText.textContent = 'Saving...';
            saveBtnLoading.classList.remove('hidden');

            const admissionId = document.getElementById('edit_exam_admission_id').value;
            const formData = {
                math_score: document.getElementById('edit_math_score').value || null,
                science_score: document.getElementById('edit_science_score').value || null,
                english_score: document.getElementById('edit_english_score').value || null,
                filipino_score: document.getElementById('edit_filipino_score').value || null,
                abstract_score: document.getElementById('edit_abstract_score').value || null,
                exam_score: document.getElementById('edit_exam_score').value || null,
                exam_result: document.getElementById('edit_exam_result').value,
            };

            try {
                const response = await fetch(`/admission/exam/${admissionId}/update`, {
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
                updateExamTableRow(admissionId, formData);

                // Close modal after short delay
                setTimeout(() => {
                    document.getElementById('edit_exam_modal').close();
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

        function updateExamTableRow(admissionId, formData) {
            // Find and update the table row with new data
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const idCell = row.querySelector('td:nth-child(2)');
                if (idCell && idCell.textContent == admissionId) {
                    const mathCell = row.querySelector('td:nth-child(5)');
                    const scienceCell = row.querySelector('td:nth-child(6)');
                    const englishCell = row.querySelector('td:nth-child(7)');
                    const filipinoCell = row.querySelector('td:nth-child(8)');
                    const abstractCell = row.querySelector('td:nth-child(9)');
                    const totalCell = row.querySelector('td:nth-child(10)');
                    const resultCell = row.querySelector('td:nth-child(12)');
                    
                    if (mathCell) mathCell.textContent = formData.math_score || '-';
                    if (scienceCell) scienceCell.textContent = formData.science_score || '-';
                    if (englishCell) englishCell.textContent = formData.english_score || '-';
                    if (filipinoCell) filipinoCell.textContent = formData.filipino_score || '-';
                    if (abstractCell) abstractCell.textContent = formData.abstract_score || '-';
                    if (totalCell) totalCell.textContent = formData.exam_score || '-';
                    if (resultCell) resultCell.textContent = formData.exam_result;
                }
            });
        }
    });
</script>
