<x-admission_sidebar>

    @include('partials.notifications')
    @include('partials.applicant-modal')
    @include('partials.edit-interview-modal')
    @include('partials.print-list-modal')

    <form id="interviewForm" action="{{ route('admission.interview.process-action') }}" method="POST">
        @csrf
        
        <div class="flex items-center gap-4 mb-4">
            <h2 class="font-bold text-4xl flex-1">Applicant (for interview)</h2>
            <button 
                type="button" 
                class="btn btn-outline"
                onclick="openPrintModal('{{ route('admission.print.interview.list') }}')"
            >
                Print
            </button>
            <select name="action" id="actionSelect" class="select select-bordered" required>
                <option value="">Select Action</option>
                <option value="reschedule">Reschedule Interview</option>
                <option value="markForExamination">Mark For Examination</option>
                <option value="markForEvaluation">Mark For Evaluation</option>
            </select>
            <div class="relative">
                <select name="schedule_id" id="scheduleSelect" class="select select-bordered min-w-[250px]" required>
                    <option value="">Select Schedule</option>
                </select>
                <div id="scheduleLoading" class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-75 rounded-lg opacity-0 pointer-events-none transition-opacity duration-200">
                    <span class="loading loading-spinner loading-sm"></span>
                </div>
            </div>
            <button 
                type="submit" 
                id="proceedBtn"
                class="btn bg-gray-400 text-white cursor-not-allowed"
                disabled
            >
                Proceed
            </button>
        </div>
        
        <!--TABLE-->
        <div data-table-wrapper>
        <div class="overflow-x-auto bg-white shadow">
            <table class="table" data-sortable-table>
                <!-- head -->
                <thead>
                    <tr>
                        <th data-no-sort>
                            <input type="checkbox" id="selectAll" class="checkbox">
                        </th>
                        <th>Id</th>
                        <th>Application No.</th>
                        <th>Applicant Name</th>
                        <th>Score</th>
                        <th>Remark</th>
                        <th>Result</th>
                        <th>Schedule</th>
                        <th data-no-sort></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applicants as $applicant)
                    <tr>
                        <td>
                            <input type="checkbox" name="applicant_ids[]" value="{{ $applicant->id }}" class="checkbox applicant-checkbox">
                        </td>
                        <td>{{$applicant->id}}</td>
                        <td>{{$applicant->applicant->application_no}}</td>
                        <td>{{$applicant->applicant->first_name}} {{$applicant->applicant->last_name}}</td>
                        <td>{{$applicant->interview_score ?? '-'}}</td>
                        <td>{{$applicant->interview_remark ?? '-'}}</td>
                        <td>{{$applicant->interview_result}}</td>
                        <td>{{date('Y-m-d', strtotime($applicant->interviewSchedule->date))}} | {{ date('g:i A', strtotime($applicant->interviewSchedule->start_time)) }} - {{ date('g:i A', strtotime($applicant->interviewSchedule->end_time)) }}</td>
                        <td>
                            <button 
                                type="button" 
                                class="btn btn-sm btn-ghost text-primary"
                                onclick="openApplicantModal({{ json_encode($applicant->applicant) }}, {{ json_encode($applicant) }})">
                                View Details
                            </button>
                            <button 
                                type="button" 
                                class="btn btn-sm btn-ghost text-yellow-600"
                                onclick="openInterviewEditModal({{ json_encode($applicant) }})">
                                Edit Score/Remarks
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        </div>
    </form>

    @include('partials.table-sort-search')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const actionSelect = document.getElementById('actionSelect');
            const scheduleSelect = document.getElementById('scheduleSelect');
            const scheduleLoading = document.getElementById('scheduleLoading');
            const proceedBtn = document.getElementById('proceedBtn');
            const selectAllCheckbox = document.getElementById('selectAll');
            const applicantCheckboxes = document.querySelectorAll('.applicant-checkbox');

            function updateButtonState() {
                const actionSelected = actionSelect.value !== '';
                const scheduleSelected = scheduleSelect.value !== '';
                const anyChecked = Array.from(applicantCheckboxes).some(cb => cb.checked);
                
                // Proceed Button - requires action, schedule, and selected applicants
                const canProceed = actionSelected && anyChecked && scheduleSelected;
                if (canProceed) {
                    proceedBtn.disabled = false;
                    proceedBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    proceedBtn.classList.add('bg-black', 'hover:bg-gray-800');
                } else {
                    proceedBtn.disabled = true;
                    proceedBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                    proceedBtn.classList.remove('bg-black', 'hover:bg-gray-800');
                }
            }

            // Async function to load schedules based on action
            async function loadSchedules(processType) {
                scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';
                
                if (!processType) {
                    scheduleSelect.disabled = false;
                    updateButtonState();
                    return;
                }

                scheduleLoading.classList.remove('opacity-0', 'pointer-events-none');
                scheduleSelect.disabled = true;

                try {
                    const response = await fetch(`{{ route('admission.api.schedules') }}?process=${processType}`);
                    const data = await response.json();

                    scheduleSelect.innerHTML = '<option value="">Select Schedule</option>';
                    
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(schedule => {
                            const option = document.createElement('option');
                            option.value = schedule.id;
                            option.textContent = schedule.label;
                            scheduleSelect.appendChild(option);
                        });
                    } else {
                        scheduleSelect.innerHTML = '<option value="">No schedules available</option>';
                    }
                } catch (error) {
                    console.error('Error loading schedules:', error);
                    scheduleSelect.innerHTML = '<option value="">Error loading schedules</option>';
                } finally {
                    scheduleLoading.classList.add('opacity-0', 'pointer-events-none');
                    scheduleSelect.disabled = false;
                    updateButtonState();
                }
            }

            // Action select change - load corresponding schedules
            actionSelect.addEventListener('change', function() {
                const action = this.value;
                let processType = '';
                
                if (action === 'reschedule') {
                    processType = 'interview';
                } else if (action === 'markForExamination') {
                    processType = 'exam';
                } else if (action === 'markForEvaluation') {
                    processType = 'evaluation';
                }
                
                loadSchedules(processType);
            });

            // Schedule select change
            scheduleSelect.addEventListener('change', updateButtonState);

            // Individual checkbox change
            applicantCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Update select all checkbox state
                    const allChecked = Array.from(applicantCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(applicantCheckboxes).some(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    
                    updateButtonState();
                });
            });

            // Select all checkbox
            selectAllCheckbox.addEventListener('change', function() {
                applicantCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateButtonState();
            });

            // Initial state
            updateButtonState();
        });
    </script>

</x-admission_sidebar>