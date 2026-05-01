<x-registrar_sidebar>
    @include('partials.notifications')

    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Grade Approval</h1>
                <p class="text-sm text-gray-500">Review submitted grades, then approve or reject them.</p>
            </div>
        </div>

        <div class="card bg-white shadow-lg">
            <div class="card-body space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                    <label class="form-control w-full">
                        <div class="label"><span class="label-text font-semibold">Department</span></div>
                        <select id="department-select" class="select select-bordered w-full">
                            <option value="">Select department</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->code }} - {{ $department->description }}</option>
                            @endforeach
                        </select>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text font-semibold">Teacher</span></div>
                        <select id="teacher-select" class="select select-bordered w-full" disabled>
                            <option value="">Select teacher</option>
                        </select>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text font-semibold">Academic Term</span></div>
                        <select id="academic-term-select" class="select select-bordered w-full" disabled>
                            <option value="">Select academic term</option>
                        </select>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text font-semibold">Subject Offering</span></div>
                        <select id="subject-offering-select" class="select select-bordered w-full" disabled>
                            <option value="">Select subject offering</option>
                        </select>
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text font-semibold">Period</span></div>
                        <select id="period-select" class="select select-bordered w-full" disabled>
                            <option value="">Select period</option>
                        </select>
                    </label>
                </div>

                <div id="summary-bar" class="hidden rounded-lg border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700"></div>
            </div>
        </div>

        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-3">
                        <h2 class="text-lg font-semibold text-gray-800">Submitted Grades</h2>
                        <span id="grade-count" class="badge badge-neutral badge-lg">0 grades</span>
                    </div>
                    <button type="button" id="bulk-approve-btn" class="btn btn-success btn-sm" disabled>
                        Approve Selected
                    </button>
                </div>

                <div role="tablist" class="tabs tabs-lift mb-4">
                    <input type="radio" name="grade_tab" role="tab" class="tab" aria-label="Submitted" id="tab-submitted" checked />
                    <div role="tabpanel" class="tab-content p-0">
                        <div class="overflow-x-auto">
                            <table class="table border border-gray-200 w-full">
                                <thead id="grade-table-head-submitted"></thead>
                                <tbody id="grade-table-body-submitted"></tbody>
                            </table>
                        </div>
                        <p id="grade-empty-state-submitted" class="hidden mt-4 text-sm text-gray-500">No submitted grades match the selected filters.</p>
                    </div>

                    <input type="radio" name="grade_tab" role="tab" class="tab" aria-label="Approved" id="tab-approved" />
                    <div role="tabpanel" class="tab-content p-0">
                        <div class="overflow-x-auto">
                            <table class="table border border-gray-200 w-full">
                                <thead id="grade-table-head-approved"></thead>
                                <tbody id="grade-table-body-approved"></tbody>
                            </table>
                        </div>
                        <p id="grade-empty-state-approved" class="hidden mt-4 text-sm text-gray-500">No approved grades match the selected filters.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const apiBase = '{{ url('/registrar/api/grade-approval') }}';
        const csrfToken = '{{ csrf_token() }}';

        const departmentSelect = document.getElementById('department-select');
        const teacherSelect = document.getElementById('teacher-select');
        const academicTermSelect = document.getElementById('academic-term-select');
        const subjectOfferingSelect = document.getElementById('subject-offering-select');
        const periodSelect = document.getElementById('period-select');
        const summaryBar = document.getElementById('summary-bar');
        const bulkApproveBtn = document.getElementById('bulk-approve-btn');
        const gradeCount = document.getElementById('grade-count');

        const tabSubmitted = document.getElementById('tab-submitted');
        const tabApproved = document.getElementById('tab-approved');

        let currentTab = 'submitted';
        let currentSummary = null;
        let currentGradesSubmitted = [];
        let currentGradesApproved = [];

        function getTableElements(tab) {
            const suffix = tab === 'submitted' ? 'submitted' : 'approved';
            return {
                tableHead: document.getElementById(`grade-table-head-${suffix}`),
                tableBody: document.getElementById(`grade-table-body-${suffix}`),
                emptyState: document.getElementById(`grade-empty-state-${suffix}`),
            };
        }

        function resetSelect(select, placeholder, disabled = true) {
            select.innerHTML = `<option value="">${placeholder}</option>`;
            select.disabled = disabled;
        }

        function fillSelect(select, items, placeholder, getValue, getLabel) {
            select.innerHTML = `<option value="">${placeholder}</option>` + items.map(item => `<option value="${getValue(item)}">${getLabel(item)}</option>`).join('');
            select.disabled = false;
        }

        function clearAllTables() {
            currentSummary = null;
            currentGradesSubmitted = [];
            currentGradesApproved = [];
            summaryBar.classList.add('hidden');
            summaryBar.innerHTML = '';

            ['submitted', 'approved'].forEach(tab => {
                const { tableHead, tableBody, emptyState } = getTableElements(tab);
                tableHead.innerHTML = '';
                tableBody.innerHTML = '';
                emptyState.classList.add('hidden');
            });

            gradeCount.textContent = '0 grades';
            if (bulkApproveBtn) {
                bulkApproveBtn.disabled = true;
            }
        }

        function resetDownstream(from = 'department') {
            if (from === 'department') {
                resetSelect(teacherSelect, 'Select teacher');
                resetSelect(academicTermSelect, 'Select academic term');
                resetSelect(subjectOfferingSelect, 'Select subject offering');
                resetSelect(periodSelect, 'Select period');
            }

            if (from === 'teacher') {
                resetSelect(subjectOfferingSelect, 'Select subject offering');
            }

            if (from === 'term') {
                resetSelect(subjectOfferingSelect, 'Select subject offering');
                resetSelect(periodSelect, 'Select period');
            }

            if (from === 'offering') {
            }

            clearAllTables();
        }

        async function fetchJson(url) {
            const response = await fetch(url, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                },
            });

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}`);
            }

            return response.json();
        }

        function selectedValue(select) {
            return select.value || '';
        }

        function maybeLoadGrades() {
            const departmentId = selectedValue(departmentSelect);
            const teacherId = selectedValue(teacherSelect);
            const academicTermId = selectedValue(academicTermSelect);
            const subjectOfferingId = selectedValue(subjectOfferingSelect);
            const period = selectedValue(periodSelect);

            if (!departmentId || !teacherId || !academicTermId || !subjectOfferingId || !period) {
                return;
            }

            loadGrades();
        }

        async function loadTeachersAndTerms(departmentId) {
            resetSelect(teacherSelect, 'Loading teachers...', true);
            resetSelect(academicTermSelect, 'Loading academic terms...', true);
            resetSelect(subjectOfferingSelect, 'Select subject offering');
            resetSelect(periodSelect, 'Select period');
            clearAllTables();

            const [teachers, academicTerms] = await Promise.all([
                fetchJson(`${apiBase}/teachers?department_id=${encodeURIComponent(departmentId)}`),
                fetchJson(`${apiBase}/academic-terms?department_id=${encodeURIComponent(departmentId)}`),
            ]);

            fillSelect(teacherSelect, teachers, 'Select teacher', item => item.id, item => `${item.code ? item.code + ' - ' : ''}${item.name}`);
            fillSelect(academicTermSelect, academicTerms, 'Select academic term', item => item.id, item => `${item.code ? item.code + ' - ' : ''}${item.description}`);
        }

        async function loadSubjectOfferings() {
            const departmentId = selectedValue(departmentSelect);
            const teacherId = selectedValue(teacherSelect);
            const academicTermId = selectedValue(academicTermSelect);

            if (!departmentId || !teacherId || !academicTermId) {
                return;
            }

            resetSelect(subjectOfferingSelect, 'Loading subject offerings...', true);
            resetSelect(periodSelect, 'Select period');
            clearAllTables();

            const offerings = await fetchJson(`${apiBase}/subject-offerings?department_id=${encodeURIComponent(departmentId)}&teacher_id=${encodeURIComponent(teacherId)}&academic_term_id=${encodeURIComponent(academicTermId)}`);

            fillSelect(subjectOfferingSelect, offerings, 'Select subject offering', item => item.id, item => {
                const subjectPart = item.subject_code ? `${item.subject_code} - ${item.subject_description}` : item.offering_description;
                return `${item.offering_code ? item.offering_code + ' - ' : ''}${subjectPart}`;
            });
        }

        async function loadPeriods() {
            const academicTermId = selectedValue(academicTermSelect);

            if (!academicTermId) {
                resetSelect(periodSelect, 'Select period');
                return;
            }

            resetSelect(periodSelect, 'Loading periods...', true);
            clearAllTables();

            const data = await fetchJson(`${apiBase}/periods?academic_term_id=${encodeURIComponent(academicTermId)}`);
            fillSelect(periodSelect, data.periods || [], 'Select period', item => item, item => item);
        }

        function renderGradesTable(data, tab) {
            const isSubmitted = tab === 'submitted';
            if (isSubmitted) {
                currentGradesSubmitted = data.grades || [];
            } else {
                currentGradesApproved = data.grades || [];
            }

            currentSummary = data.summary;

            const { tableHead, tableBody, emptyState } = getTableElements(tab);

            const summaryParts = [
                `Department: ${currentSummary.department?.label || '-'}`,
                `Teacher: ${currentSummary.teacher?.label || '-'}`,
                `Academic Term: ${currentSummary.academic_term?.label || '-'}`,
                `Subject Offering: ${currentSummary.subject_offering?.label || '-'}`,
                `Period: ${currentSummary.period || '-'}`,
            ];

            if (isSubmitted) {
                summaryBar.textContent = summaryParts.join(' | ');
                summaryBar.classList.remove('hidden');
            }

            tableHead.innerHTML = '';
            tableBody.innerHTML = '';

            const topRow = document.createElement('tr');
            const topHeader = document.createElement('th');
            topHeader.colSpan = 4;
            topHeader.className = 'text-left text-slate-700 border-b border-slate-200 pb-2';
            topHeader.textContent = summaryParts.join(' | ');
            topRow.appendChild(topHeader);
            tableHead.appendChild(topRow);

            const columnRow = document.createElement('tr');
            columnRow.className = 'divide-x text-center';

            const checkboxHeader = document.createElement('th');
            checkboxHeader.className = 'bg-slate-200 w-14';
            checkboxHeader.innerHTML = `<input type="checkbox" id="select-all-grades-${tab}" class="checkbox checkbox-sm" />`;
            columnRow.appendChild(checkboxHeader);

            ['Student Name', 'Grade', 'Action'].forEach((label, index) => {
                const th = document.createElement('th');
                th.className = 'bg-slate-200';
                th.textContent = label;
                columnRow.appendChild(th);
            });

            tableHead.appendChild(columnRow);

            const selectAllCheckbox = document.getElementById(`select-all-grades-${tab}`);
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function () {
                    const checked = this.checked;
                    tableBody.querySelectorAll('.grade-row-check').forEach(input => {
                        input.checked = checked;
                    });
                    updateBulkApproveState();
                });
            }

            const currentGrades = isSubmitted ? currentGradesSubmitted : currentGradesApproved;
            if (currentGrades.length === 0) {
                emptyState.classList.remove('hidden');
                if (isSubmitted) {
                    gradeCount.textContent = '0 grades';
                }
                return;
            }

            if (isSubmitted) {
                gradeCount.textContent = `${currentGrades.length} grade${currentGrades.length === 1 ? '' : 's'}`;
            }

            currentGrades.forEach(grade => {
                const row = document.createElement('tr');
                row.className = 'divide-x';
                row.dataset.gradeId = grade.id;

                const checkboxCell = document.createElement('td');
                checkboxCell.className = 'text-center';
                checkboxCell.innerHTML = `<input type="checkbox" class="checkbox checkbox-sm grade-row-check" data-grade-id="${grade.id}" />`;
                row.appendChild(checkboxCell);

                const studentCell = document.createElement('td');
                studentCell.textContent = grade.student_name || '-';
                row.appendChild(studentCell);

                const gradeCell = document.createElement('td');
                gradeCell.className = 'text-center font-semibold text-gray-800';
                gradeCell.textContent = grade.period_grade ?? '-';
                row.appendChild(gradeCell);

                const actionCell = document.createElement('td');
                actionCell.className = 'text-center';
                actionCell.innerHTML = `
                    <div class="flex items-center justify-center gap-3 text-lg">
                        <span role="button" tabindex="0" class="approve-grade-btn cursor-pointer text-emerald-600 hover:text-emerald-800 transition" data-grade-id="${grade.id}" data-tab="${tab}" title="Approve" aria-label="Approve">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                        </span>
                        <span role="button" tabindex="0" class="reject-grade-btn cursor-pointer text-rose-600 hover:text-rose-800 transition" data-grade-id="${grade.id}" data-tab="${tab}" title="Reject" aria-label="Reject">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6 6 18" />
                            </svg>
                        </span>
                    </div>
                `;
                row.appendChild(actionCell);

                tableBody.appendChild(row);
            });

            emptyState.classList.add('hidden');

            tableBody.querySelectorAll('.approve-grade-btn').forEach(button => {
                button.addEventListener('click', function () {
                    updateGradeStatus(this.dataset.gradeId, 'approved', this.closest('tr'), tab);
                });
                button.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        updateGradeStatus(this.dataset.gradeId, 'approved', this.closest('tr'), tab);
                    }
                });
            });

            tableBody.querySelectorAll('.reject-grade-btn').forEach(button => {
                button.addEventListener('click', function () {
                    updateGradeStatus(this.dataset.gradeId, 'rejected', this.closest('tr'), tab);
                });
                button.addEventListener('keydown', function (event) {
                    if (event.key === 'Enter' || event.key === ' ') {
                        event.preventDefault();
                        updateGradeStatus(this.dataset.gradeId, 'rejected', this.closest('tr'), tab);
                    }
                });
            });

            tableBody.querySelectorAll('.grade-row-check').forEach(input => {
                input.addEventListener('change', updateBulkApproveState);
            });

            updateBulkApproveState();
        }

        async function loadGrades() {
            const departmentId = selectedValue(departmentSelect);
            const teacherId = selectedValue(teacherSelect);
            const academicTermId = selectedValue(academicTermSelect);
            const subjectOfferingId = selectedValue(subjectOfferingSelect);
            const period = selectedValue(periodSelect);

            if (!departmentId || !teacherId || !academicTermId || !subjectOfferingId || !period) {
                return;
            }

            const { tableBody: submittedTableBody } = getTableElements('submitted');
            const { tableBody: approvedTableBody } = getTableElements('approved');

            submittedTableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-500">Loading grades...</td></tr>';
            approvedTableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-gray-500">Loading grades...</td></tr>';

            try {
                const [submittedData, approvedData] = await Promise.all([
                    fetchJson(
                        `${apiBase}/grades?department_id=${encodeURIComponent(departmentId)}&teacher_id=${encodeURIComponent(teacherId)}&academic_term_id=${encodeURIComponent(academicTermId)}&teacher_offering_id=${encodeURIComponent(subjectOfferingId)}&period=${encodeURIComponent(period)}&status=submitted`
                    ),
                    fetchJson(
                        `${apiBase}/grades?department_id=${encodeURIComponent(departmentId)}&teacher_id=${encodeURIComponent(teacherId)}&academic_term_id=${encodeURIComponent(academicTermId)}&teacher_offering_id=${encodeURIComponent(subjectOfferingId)}&period=${encodeURIComponent(period)}&status=approved`
                    ),
                ]);

                renderGradesTable(submittedData, 'submitted');
                renderGradesTable(approvedData, 'approved');
            } catch (error) {
                console.error('Error loading grades:', error);
                submittedTableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-red-600">Failed to load submitted grades.</td></tr>';
                approvedTableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-red-600">Failed to load approved grades.</td></tr>';
                gradeCount.textContent = '0 grades';
            }
        }

        async function updateGradeStatus(gradeId, status, row, tab) {
            try {
                const response = await fetch(`${apiBase}/grades/${gradeId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ status })
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                if (row) {
                    row.remove();
                }

                if (tab === 'submitted') {
                    currentGradesSubmitted = currentGradesSubmitted.filter(grade => String(grade.id) !== String(gradeId));
                    const remainingCount = currentGradesSubmitted.length;
                    gradeCount.textContent = `${remainingCount} grade${remainingCount === 1 ? '' : 's'}`;
                } else {
                    currentGradesApproved = currentGradesApproved.filter(grade => String(grade.id) !== String(gradeId));
                }

                const { tableBody, emptyState } = getTableElements(tab);
                const remainingRows = tableBody.querySelectorAll('tr');
                if (remainingRows.length === 0) {
                    tableBody.innerHTML = '';
                    emptyState.classList.remove('hidden');
                }

                updateBulkApproveState();
            } catch (error) {
                console.error('Error updating grade status:', error);
                alert('Failed to update grade status');
            }
        }

        function getSelectedGradeIds() {
            const { tableBody } = getTableElements(currentTab);
            return Array.from(tableBody.querySelectorAll('.grade-row-check:checked')).map(input => input.dataset.gradeId);
        }

        function updateBulkApproveState() {
            if (!bulkApproveBtn) {
                return;
            }

            bulkApproveBtn.disabled = getSelectedGradeIds().length === 0;
        }

        async function updateGradeStatusOnServer(gradeId, status) {
            return fetch(`${apiBase}/grades/${gradeId}/status`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ status })
            });
        }

        async function bulkApproveSelectedGrades() {
            const selectedGradeIds = getSelectedGradeIds();

            if (selectedGradeIds.length === 0) {
                return;
            }

            const originalText = bulkApproveBtn.textContent;
            bulkApproveBtn.disabled = true;
            bulkApproveBtn.textContent = 'Approving...';

            try {
                const responses = await Promise.all(selectedGradeIds.map(gradeId => updateGradeStatusOnServer(gradeId, 'approved')));
                const failed = responses.filter(response => !response.ok);

                if (failed.length > 0) {
                    throw new Error(`Failed to approve ${failed.length} grade(s)`);
                }

                const { tableBody } = getTableElements(currentTab);
                selectedGradeIds.forEach(gradeId => {
                    const row = tableBody.querySelector(`tr[data-grade-id="${gradeId}"]`);
                    if (row) {
                        row.remove();
                    }
                });

                currentGradesSubmitted = currentGradesSubmitted.filter(grade => !selectedGradeIds.includes(String(grade.id)));

                const remainingCount = currentGradesSubmitted.length;
                gradeCount.textContent = `${remainingCount} grade${remainingCount === 1 ? '' : 's'}`;

                if (remainingCount === 0) {
                    tableBody.innerHTML = '';
                    const { emptyState } = getTableElements('submitted');
                    emptyState.classList.remove('hidden');
                }

                updateBulkApproveState();
                // Refresh approved tab
                if (currentTab === 'submitted') {
                    loadGrades();
                }
            } catch (error) {
                console.error('Error approving selected grades:', error);
                alert('Failed to approve selected grades');
            } finally {
                bulkApproveBtn.textContent = originalText;
                updateBulkApproveState();
            }
        }

        departmentSelect.addEventListener('change', async function () {
            if (!this.value) {
                resetDownstream('department');
                return;
            }

            try {
                await loadTeachersAndTerms(this.value);
            } catch (error) {
                console.error('Error loading department filters:', error);
                alert('Failed to load teachers and academic terms');
                resetDownstream('department');
            }
        });

        teacherSelect.addEventListener('change', async function () {
            resetDownstream('teacher');

            if (!this.value) {
                return;
            }

            try {
                await loadSubjectOfferings();
                await loadPeriods();
            } catch (error) {
                console.error('Error loading teacher filters:', error);
                alert('Failed to load subject offerings');
                resetDownstream('teacher');
            }
        });

        academicTermSelect.addEventListener('change', async function () {
            resetDownstream('term');

            if (!this.value) {
                return;
            }

            try {
                await loadSubjectOfferings();
                await loadPeriods();
            } catch (error) {
                console.error('Error loading academic term filters:', error);
                alert('Failed to load subject offerings and periods');
                resetDownstream('term');
            }
        });

        subjectOfferingSelect.addEventListener('change', function () {
            if (!this.value) {
                resetDownstream('offering');
                return;
            }

            maybeLoadGrades();
        });

        periodSelect.addEventListener('change', function () {
            if (!this.value) {
                clearAllTables();
                return;
            }

            maybeLoadGrades();
        });

        tabSubmitted.addEventListener('change', function () {
            if (this.checked) {
                currentTab = 'submitted';
                updateBulkApproveState();
            }
        });

        tabApproved.addEventListener('change', function () {
            if (this.checked) {
                currentTab = 'approved';
                bulkApproveBtn.disabled = true;
            }
        });

        if (bulkApproveBtn) {
            bulkApproveBtn.addEventListener('click', bulkApproveSelectedGrades);
        }

        clearAllTables();
    </script>
</x-registrar_sidebar>
