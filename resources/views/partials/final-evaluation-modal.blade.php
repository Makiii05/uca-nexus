<!-- Final Evaluation Modal -->
<dialog id="final_evaluation_modal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        
        <h3 class="text-lg font-bold mb-4">Final Evaluation</h3>
        
        <!-- Applicant Info -->
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <span class="text-sm text-gray-500">Application No.</span>
                    <p class="font-medium" id="eval_modal_application_no">-</p>
                </div>
                <div>
                    <span class="text-sm text-gray-500">Applicant Name</span>
                    <p class="font-medium" id="eval_modal_applicant_name">-</p>
                </div>
            </div>
        </div>

        <!-- Scores Section -->
        <div class="bg-blue-50 p-4 rounded-lg mb-4">
            <h4 class="font-semibold text-md mb-3 text-primary">Assessment Scores</h4>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center">
                    <span class="text-sm text-gray-500">Interview Score</span>
                    <p class="font-bold text-xl" id="eval_modal_interview_score">-</p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500">Exam Score</span>
                    <p class="font-bold text-xl" id="eval_modal_exam_score">-</p>
                </div>
                <div class="text-center">
                    <span class="text-sm text-gray-500">Final Score</span>
                    <p class="font-bold text-xl text-primary" id="eval_modal_final_score">-</p>
                </div>
            </div>
        </div>

        <form id="finalEvaluationForm" class="space-y-4">
            <input type="hidden" id="eval_admission_id" name="admission_id">
            <input type="hidden" id="eval_applicant_id" name="applicant_id">

            <!-- Decision Dropdown -->
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Decision <span class="text-error">*</span></span>
                </label>
                <select 
                    id="eval_decision"
                    name="decision" 
                    class="select select-bordered w-full"
                    required
                >
                    <option value="" disabled selected>Select decision</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>

            <!-- Department Selection (Only shown when accepted) -->
            <div id="department_selection_container" class="form-control hidden">
                <label class="label">
                    <span class="label-text font-semibold">Assign to Program <span class="text-error">*</span></span>
                </label>
                <select 
                    id="eval_assigned_program"
                    name="program" 
                    class="select select-bordered w-full"
                >
                    <option value="" disabled selected>Select program</option>
                    <!-- Options will be populated dynamically based on applicant's program choices -->
                </select>
                <label class="label">
                    <span class="label-text-alt text-gray-500">Programs available are based on the applicant's choices during admission</span>
                </label>
            </div>

            <!-- Error Message -->
            <div id="eval_error" class="alert alert-error hidden">
                <span id="eval_error_text"></span>
            </div>

            <!-- Success Message -->
            <div id="eval_success" class="alert alert-success hidden">
                <span>Evaluation submitted successfully!</span>
            </div>

            <!-- Form Actions -->
            <div class="modal-action">
                <button type="button" class="btn" onclick="final_evaluation_modal.close()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitEvaluationBtn">
                    <span id="submitEvalBtnText">Submit Evaluation</span>
                    <span id="submitEvalBtnLoading" class="hidden loading loading-spinner loading-sm"></span>
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    // Programs and Levels lookup data from database
    const programsLookup = @json(\App\Models\Program::all()->keyBy('id')->map(fn($p) => ['code' => $p->code, 'description' => $p->description]));
    const levelsLookup = @json(\App\Models\Level::all()->keyBy('id')->map(fn($l) => ['code' => $l->code, 'description' => $l->description]));

    // Helper function to get program name by ID
    function getProgramName(id) {
        if (!id) return null;
        const program = programsLookup[id];
        return program ? `${program.code} - ${program.description}` : null;
    }

    // Helper function to get level name by ID
    function getLevelName(id) {
        if (!id) return null;
        const level = levelsLookup[id];
        return level ? level.description : null;
    }

    // Helper function to find program ID by level name (for nursery, kindergarten, etc.)
    function getProgramIdByLevelName(levelName) {
        if (!levelName) return null;
        const lowerLevelName = levelName.toLowerCase();
        for (const [id, program] of Object.entries(programsLookup)) {
            if (program.code.toLowerCase() === lowerLevelName || 
                program.description.toLowerCase() === lowerLevelName ||
                program.description.toLowerCase().includes(lowerLevelName) ||
                lowerLevelName.includes(program.code.toLowerCase())) {
                return parseInt(id);
            }
        }
        return null;
    }

    // Show/hide department selection based on decision
    document.getElementById('eval_decision').addEventListener('change', function() {
        const departmentContainer = document.getElementById('department_selection_container');
        const assignedProgramSelect = document.getElementById('eval_assigned_program');
        
        if (this.value === 'accepted') {
            departmentContainer.classList.remove('hidden');
            assignedProgramSelect.setAttribute('required', 'required');
        } else {
            departmentContainer.classList.add('hidden');
            assignedProgramSelect.removeAttribute('required');
            assignedProgramSelect.value = '';
        }
    });

    // Function to open the final evaluation modal with data
    // admission: contains interview_score, exam_score, final_score, id (admission_id)
    // applicant: contains application_no, first_name, last_name, middle_name, first_program_choice, second_program_choice, third_program_choice, strand, level, year_level, id (applicant_id)
    function openFinalEvaluationModal(admission, applicant) {
        // Populate applicant info from applicant data
        document.getElementById('eval_modal_application_no').textContent = applicant.application_no || '-';
        const applicantName = `${applicant.last_name || ''}, ${applicant.first_name || ''} ${applicant.middle_name || ''}`.trim();
        document.getElementById('eval_modal_applicant_name').textContent = applicantName || '-';
        
        // Populate scores from admission data
        document.getElementById('eval_modal_interview_score').textContent = admission.interview_score ?? '-';
        document.getElementById('eval_modal_exam_score').textContent = admission.exam_score ?? '-';
        document.getElementById('eval_modal_final_score').textContent = admission.final_score ?? '-';
        
        // Populate hidden fields
        document.getElementById('eval_admission_id').value = admission.id || '';
        document.getElementById('eval_applicant_id').value = applicant.id || '';
        
        // Reset form state
        document.getElementById('eval_decision').value = '';
        document.getElementById('eval_assigned_program').value = '';
        document.getElementById('department_selection_container').classList.add('hidden');
        
        // Populate program choices dropdown
        const programSelect = document.getElementById('eval_assigned_program');
        programSelect.innerHTML = '<option value="" disabled selected>Select program</option>';
        
        // Check if applicant has strand or program choices
        const hasStrand = applicant.strand;
        const hasProgramChoices = applicant.first_program_choice || applicant.second_program_choice || applicant.third_program_choice;
        
        if (hasStrand || hasProgramChoices) {
            // Display strand and/or program choices with their corresponding names
            if (hasStrand) {
                const strandName = getProgramName(applicant.strand);
                if (strandName) {
                    const option = document.createElement('option');
                    option.value = applicant.strand;
                    option.textContent = `Strand: ${strandName}`;
                    programSelect.appendChild(option);
                }
            }
            
            if (applicant.first_program_choice) {
                const programName = getProgramName(applicant.first_program_choice);
                if (programName) {
                    const option = document.createElement('option');
                    option.value = applicant.first_program_choice;
                    option.textContent = `1st Choice: ${programName}`;
                    programSelect.appendChild(option);
                }
            }
            
            if (applicant.second_program_choice) {
                const programName = getProgramName(applicant.second_program_choice);
                if (programName) {
                    const option = document.createElement('option');
                    option.value = applicant.second_program_choice;
                    option.textContent = `2nd Choice: ${programName}`;
                    programSelect.appendChild(option);
                }
            }
            
            if (applicant.third_program_choice) {
                const programName = getProgramName(applicant.third_program_choice);
                if (programName) {
                    const option = document.createElement('option');
                    option.value = applicant.third_program_choice;
                    option.textContent = `3rd Choice: ${programName}`;
                    programSelect.appendChild(option);
                }
            }
        } else {
            // No strand or program choices - use level (nursery, kindergarten, grade level, JHS)
            // Find the matching program ID based on level name
            const levelName = applicant.level;
            if (levelName) {
                const programId = getProgramIdByLevelName(levelName);
                if (programId) {
                    const programName = getProgramName(programId);
                    const option = document.createElement('option');
                    option.value = programId;
                    option.textContent = programName || levelName;
                    programSelect.appendChild(option);
                } else {
                    // Fallback if no matching program found - still show the level but without ID
                    const option = document.createElement('option');
                    option.value = levelName;
                    option.textContent = levelName;
                    programSelect.appendChild(option);
                }
            }
        }

        // Open modal
        final_evaluation_modal.showModal();
    }

    // Form submission handler
    document.getElementById('finalEvaluationForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const admissionId = document.getElementById('eval_admission_id').value;
        const submitBtn = document.getElementById('submitEvaluationBtn');
        const submitBtnText = document.getElementById('submitEvalBtnText');
        const submitBtnLoading = document.getElementById('submitEvalBtnLoading');
        const errorDiv = document.getElementById('eval_error');
        const errorText = document.getElementById('eval_error_text');
        const successDiv = document.getElementById('eval_success');
        
        // Hide messages
        errorDiv.classList.add('hidden');
        successDiv.classList.add('hidden');
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtnText.textContent = 'Submitting...';
        submitBtnLoading.classList.remove('hidden');
        
        const formData = {
            decision: document.getElementById('eval_decision').value,
            program: document.getElementById('eval_assigned_program').value,
        };
        
        try {
            const response = await fetch(`/admission/evaluation/${admissionId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });
            
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'An error occurred while submitting.');
            }
            
            // Show success
            successDiv.classList.remove('hidden');
            
            // Close modal after short delay and reload
            setTimeout(() => {
                final_evaluation_modal.close();
                window.location.reload();
            }, 1000);
            
        } catch (error) {
            errorText.textContent = error.message;
            errorDiv.classList.remove('hidden');
        } finally {
            // Reset button state
            submitBtn.disabled = false;
            submitBtnText.textContent = 'Submit Evaluation';
            submitBtnLoading.classList.add('hidden');
        }
    });

    // Reset modal when closed
    document.getElementById('final_evaluation_modal').addEventListener('close', function() {
        document.getElementById('finalEvaluationForm').reset();
        document.getElementById('department_selection_container').classList.add('hidden');
        document.getElementById('eval_error').classList.add('hidden');
        document.getElementById('eval_success').classList.add('hidden');
    });
</script>
