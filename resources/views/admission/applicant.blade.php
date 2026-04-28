<x-admission_sidebar>

    @include('partials.notifications')
    @include('partials.applicant-modal')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl flex-1">Applicant</h2>

        <!-- Academic Year Selector -->
        <form method="GET" action="{{ route('admission.applicant') }}" class="flex items-center gap-2">
            <input type="hidden" name="search" value="{{ $search }}">
            <input type="hidden" name="sort" value="{{ $sortColumn }}">
            <input type="hidden" name="direction" value="{{ $sortDirection }}">
            <label for="academic_year" class="text-sm font-medium text-gray-600 whitespace-nowrap">Academic Year:</label>
            <select name="academic_year" id="academic_year_filter" onchange="this.form.submit()"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-main-primary focus:border-main-primary">
                @foreach($academicYears as $year)
                    <option value="{{ $year }}" {{ $selectedYear === $year ? 'selected' : '' }}>{{ $year }}</option>
                @endforeach
            </select>
        </form>
        
        <form id="deleteForm" action="{{ route('admission.applicant.delete') }}" method="POST" class="inline">
            @csrf
            <input type="hidden" name="applicant_ids[]" id="deleteApplicantIds">
            <button 
                type="button" 
                id="deleteBtn"
                onclick="confirmDelete()"
                class="btn bg-gray-400 text-white cursor-not-allowed"
                disabled
            >
                Delete Selected
            </button>
        </form>
    </div>

    <!-- API-based Search -->
    <div class="flex justify-end items-center mb-4">
        <div class="flex items-center gap-2">
            <div class="relative">
                <input type="text" id="searchInput" value="{{ $search }}" placeholder="Search all fields..." 
                    class="input input-bordered"/>
                <div id="searchLoading" class="absolute right-3 top-1/2 -translate-y-1/2 hidden">
                    <span class="loading loading-spinner loading-xs"></span>
                </div>
            </div>
            <button type="button" id="searchBtn" class="btn btn-sm btn-primary">Search</button>
            <button type="button" id="clearSearchBtn" class="btn btn-sm btn-ghost {{ $search ? '' : 'hidden' }}">Clear</button>
        </div>
    </div>

    <form id="interviewForm" action="{{ route('admission.applicant.mark-interview') }}" method="POST">
        @csrf
        
        <div class="flex justify-end items-center gap-4 mb-4">
            <select name="action" id="actionSelect" class="select select-bordered" required>
                <option value="">Select Action</option>
                <option value="markForInterview">Mark For Interview</option>
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
            <button type="submit" id="proceedBtn" class="btn bg-gray-400 text-white cursor-not-allowed" disabled>
                Proceed
            </button>
        </div>
        
        <!--TABLE-->
        <div class="overflow-x-auto bg-white shadow">
            <table class="table">
                <!-- head -->
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" class="checkbox">
                        </th>
                        @php
                            $sortUrl = function($column) use ($selectedYear, $search, $sortColumn, $sortDirection) {
                                $newDirection = ($sortColumn === $column && $sortDirection === 'asc') ? 'desc' : 'asc';
                                return route('admission.applicant', [
                                    'academic_year' => $selectedYear,
                                    'search' => $search,
                                    'sort' => $column,
                                    'direction' => $newDirection
                                ]);
                            };
                            $sortIcon = function($column) use ($sortColumn, $sortDirection) {
                                if ($sortColumn !== $column) return '⇅';
                                return $sortDirection === 'asc' ? '↑' : '↓';
                            };
                        @endphp
                        <th>
                            <a href="{{ $sortUrl('id') }}" class="flex items-center gap-1 hover:text-primary">
                                Id <span class="text-xs {{ $sortColumn === 'id' ? 'opacity-100' : 'opacity-40' }}">{{ $sortIcon('id') }}</span>
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortUrl('application_no') }}" class="flex items-center gap-1 hover:text-primary">
                                Application No. <span class="text-xs {{ $sortColumn === 'application_no' ? 'opacity-100' : 'opacity-40' }}">{{ $sortIcon('application_no') }}</span>
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortUrl('last_name') }}" class="flex items-center gap-1 hover:text-primary">
                                Applicant Name <span class="text-xs {{ $sortColumn === 'last_name' ? 'opacity-100' : 'opacity-40' }}">{{ $sortIcon('last_name') }}</span>
                            </a>
                        </th>
                        <th>
                            <a href="{{ $sortUrl('status') }}" class="flex items-center gap-1 hover:text-primary">
                                Status <span class="text-xs {{ $sortColumn === 'status' ? 'opacity-100' : 'opacity-40' }}">{{ $sortIcon('status') }}</span>
                            </a>
                        </th>
                        <th>Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($applicants as $applicant)
                    <tr>
                        <td>
                            <input 
                                type="checkbox" 
                                name="applicant_ids[]" 
                                value="{{ $applicant->id }}" 
                                class="checkbox applicant-checkbox"
                                data-status="{{ $applicant->status }}"
                            >
                        </td>
                        <td>{{$applicant->id}}</td>
                        <td>{{$applicant->application_no}}</td>
                        <td>{{$applicant->first_name}} {{$applicant->last_name}}</td>
                        <td>{{$applicant->status}}</td>
                        <td>
                            <a href="{{ route('admission.print.applicant.details', ['id' => $applicant->id]) }}" target="_blank" type="button" 
                                class="btn btn-sm btn-ghost text-success">
                                Print
                            </a>
                            <button type="button" 
                                class="btn btn-sm btn-ghost text-primary"
                                onclick="openApplicantModal({{ json_encode($applicant) }}, {{ json_encode($applicant->admission) }})"
                            >
                                View Details
                            </button>
                            <a href="{{ route('admission.applicant.edit', ['id' => $applicant->id]) }}" 
                                class="btn btn-sm btn-ghost text-blue-600">
                                Edit
                            </a>
                            @if($applicant->status !== 'admitted')
                            <button type="button" 
                                class="btn btn-sm btn-ghost text-red-600"
                                onclick="confirmSingleDelete({{ $applicant->id }}, '{{ $applicant->first_name }} {{ $applicant->last_name }}')"
                            >
                                Delete
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500 py-8">
                            @if($search)
                                No applicants found matching "{{ $search }}"
                            @else
                                No applicants found for {{ $selectedYear }}
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="mt-4">
            {{ $applicants->links() }}
        </div>
    </form>

    @include('partials.applicant-delete-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const actionSelect = document.getElementById('actionSelect');
            const scheduleSelect = document.getElementById('scheduleSelect');
            const scheduleLoading = document.getElementById('scheduleLoading');
            const proceedBtn = document.getElementById('proceedBtn');
            const deleteBtn = document.getElementById('deleteBtn');
            const selectAllCheckbox = document.getElementById('selectAll');
            const searchInput = document.getElementById('searchInput');
            const searchBtn = document.getElementById('searchBtn');
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            const searchLoading = document.getElementById('searchLoading');
            const paginationInfo = document.getElementById('paginationInfo');
            const tableBody = document.querySelector('table tbody');
            
            let applicantCheckboxes = document.querySelectorAll('.applicant-checkbox');
            let currentSearch = '{{ $search }}';
            let currentSort = '{{ $sortColumn }}';
            let currentDirection = '{{ $sortDirection }}';
            let currentPage = {{ $applicants->currentPage() }};
            let searchTimeout = null;

            // API Search function
            async function performSearch(search = '', page = 1) {
                searchLoading.classList.remove('hidden');
                
                try {
                    const params = new URLSearchParams({
                        search: search,
                        academic_year: '{{ $selectedYear }}',
                        sort: currentSort,
                        direction: currentDirection,
                        page: page
                    });
                    
                    const response = await fetch(`{{ route('admission.api.applicants.search') }}?${params}`);
                    const result = await response.json();
                    
                    if (result.success) {
                        renderTable(result.data, search);
                        renderPagination(result.pagination, search);
                        currentSearch = search;
                        currentPage = result.pagination.current_page;
                        
                        // Update URL without reload
                        const url = new URL(window.location);
                        if (search) {
                            url.searchParams.set('search', search);
                        } else {
                            url.searchParams.delete('search');
                        }
                        url.searchParams.set('page', page);
                        window.history.replaceState({}, '', url);
                        
                        // Show/hide clear button
                        clearSearchBtn.classList.toggle('hidden', !search);
                        
                        // Re-attach checkbox listeners
                        reattachCheckboxListeners();
                    }
                } catch (error) {
                    console.error('Search error:', error);
                } finally {
                    searchLoading.classList.add('hidden');
                }
            }

            function renderTable(applicants, search) {
                if (applicants.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center text-gray-500 py-8">
                                ${search ? `No applicants found matching "${search}"` : 'No applicants found for {{ $selectedYear }}'}
                            </td>
                        </tr>
                    `;
                    return;
                }
                
                tableBody.innerHTML = applicants.map(applicant => `
                    <tr>
                        <td>
                            <input 
                                type="checkbox" 
                                name="applicant_ids[]" 
                                value="${applicant.id}" 
                                class="checkbox applicant-checkbox"
                                data-status="${applicant.status}"
                            >
                        </td>
                        <td>${applicant.id}</td>
                        <td>${applicant.application_no}</td>
                        <td>${applicant.first_name} ${applicant.last_name}</td>
                        <td>${applicant.status}</td>
                        <td>
                            <a href="{{ url('admission/print-applicant-details') }}/${applicant.id}" target="_blank" 
                                class="btn btn-sm btn-ghost text-success">Print</a>
                            <button type="button" 
                                class="btn btn-sm btn-ghost text-primary"
                                onclick='openApplicantModal(${JSON.stringify(applicant)}, ${JSON.stringify(applicant.admission)})'>
                                View Details
                            </button>
                            <a href="{{ url('admission/applicant') }}/${applicant.id}/edit" 
                                class="btn btn-sm btn-ghost text-blue-600">Edit</a>
                            ${applicant.status !== 'admitted' ? `
                                <button type="button" 
                                    class="btn btn-sm btn-ghost text-red-600"
                                    onclick="confirmSingleDelete(${applicant.id}, '${applicant.first_name} ${applicant.last_name}')">
                                    Delete
                                </button>
                            ` : ''}
                        </td>
                    </tr>
                `).join('');
            }

            function renderPagination(pagination, search) {
                paginationInfo.textContent = `Showing ${pagination.from || 0} - ${pagination.to || 0} of ${pagination.total} applicants`;
                
                // Update pagination links
                const paginationContainer = document.querySelector('.mt-4');
                if (paginationContainer && pagination.last_page > 1) {
                    let paginationHtml = '<nav class="flex items-center justify-between"><div class="flex justify-between flex-1 sm:hidden">';
                    
                    // Mobile prev/next
                    if (pagination.current_page > 1) {
                        paginationHtml += `<button type="button" onclick="goToPage(${pagination.current_page - 1})" class="btn btn-sm">Previous</button>`;
                    }
                    if (pagination.current_page < pagination.last_page) {
                        paginationHtml += `<button type="button" onclick="goToPage(${pagination.current_page + 1})" class="btn btn-sm">Next</button>`;
                    }
                    paginationHtml += '</div>';
                    
                    // Desktop pagination
                    paginationHtml += '<div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-center"><div class="join">';
                    for (let i = 1; i <= pagination.last_page; i++) {
                        if (i === pagination.current_page) {
                            paginationHtml += `<button class="join-item btn btn-sm btn-active">${i}</button>`;
                        } else if (i === 1 || i === pagination.last_page || Math.abs(i - pagination.current_page) <= 2) {
                            paginationHtml += `<button type="button" onclick="goToPage(${i})" class="join-item btn btn-sm">${i}</button>`;
                        } else if (Math.abs(i - pagination.current_page) === 3) {
                            paginationHtml += '<button class="join-item btn btn-sm btn-disabled">...</button>';
                        }
                    }
                    paginationHtml += '</div></div></nav>';
                    
                    paginationContainer.innerHTML = paginationHtml;
                } else if (paginationContainer) {
                    paginationContainer.innerHTML = '';
                }
            }

            // Make goToPage globally accessible
            window.goToPage = function(page) {
                performSearch(currentSearch, page);
            };

            function reattachCheckboxListeners() {
                applicantCheckboxes = document.querySelectorAll('.applicant-checkbox');
                applicantCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const allChecked = Array.from(applicantCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(applicantCheckboxes).some(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = someChecked && !allChecked;
                        updateButtonState();
                    });
                });
                updateButtonState();
            }

            function updateButtonState() {
                const actionSelected = actionSelect.value !== '';
                const scheduleSelected = scheduleSelect.value !== '';
                applicantCheckboxes = document.querySelectorAll('.applicant-checkbox');
                const anyChecked = Array.from(applicantCheckboxes).some(cb => cb.checked);
                
                // Check if any non-admitted applicants are selected for delete button
                const anyDeletableChecked = Array.from(applicantCheckboxes).some(cb => cb.checked && cb.dataset.status !== 'admitted');
                
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

                // Delete Button - only enable if non-admitted applicants are selected
                if (anyDeletableChecked) {
                    deleteBtn.disabled = false;
                    deleteBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    deleteBtn.classList.add('bg-red-600', 'hover:bg-red-700');
                } else {
                    deleteBtn.disabled = true;
                    deleteBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
                    deleteBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
                }
            }

            // Search event listeners
            searchBtn.addEventListener('click', function() {
                performSearch(searchInput.value.trim(), 1);
            });

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    performSearch(searchInput.value.trim(), 1);
                }
            });

            // Real-time search with debounce
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    performSearch(searchInput.value.trim(), 1);
                }, 500);
            });

            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                performSearch('', 1);
            });

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
                
                if (action === 'markForInterview') {
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
                    const allChecked = Array.from(applicantCheckboxes).every(cb => cb.checked);
                    const someChecked = Array.from(applicantCheckboxes).some(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = someChecked && !allChecked;
                    updateButtonState();
                });
            });

            // Select all checkbox
            selectAllCheckbox.addEventListener('change', function() {
                applicantCheckboxes = document.querySelectorAll('.applicant-checkbox');
                applicantCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
                updateButtonState();
            });

            // Initial state
            updateButtonState();
        });

        function confirmDelete() {
            document.getElementById('deleteConfirmModal').showModal();
        }

        function submitDelete() {
            const deleteForm = document.getElementById('deleteForm');
            const applicantCheckboxes = document.querySelectorAll('.applicant-checkbox:checked');
            
            // Clear existing hidden inputs
            const existingInputs = deleteForm.querySelectorAll('input[name="applicant_ids[]"]:not(#deleteApplicantIds)');
            existingInputs.forEach(input => input.remove());
            
            // Add hidden inputs for each selected applicant
            applicantCheckboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'applicant_ids[]';
                input.value = checkbox.value;
                deleteForm.appendChild(input);
            });
            
            // Remove the placeholder input
            document.getElementById('deleteApplicantIds').remove();
            
            // Submit the form
            deleteForm.submit();
        }

        let singleDeleteApplicantId = null;

        function confirmSingleDelete(id, name) {
            singleDeleteApplicantId = id;
            document.getElementById('singleDeleteName').textContent = name;
            document.getElementById('singleDeleteConfirmModal').showModal();
        }

        function submitSingleDelete() {
            document.getElementById('singleDeleteId').value = singleDeleteApplicantId;
            document.getElementById('singleDeleteForm').submit();
        }
    </script>

</x-admission_sidebar>