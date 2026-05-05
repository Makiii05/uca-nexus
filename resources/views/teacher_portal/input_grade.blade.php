<x-teacher_portal_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('teacher_portal.subject_load', ['academic_term_id' => $teacherOffering->academic_term_id]) }}" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Back
        </a>
        <h2 class="font-bold text-4xl flex-1">Input Grade</h2>
    </div>

    <div class="card bg-white shadow">
        <div class="card-body">
            <h3 class="text-xl font-semibold text-gray-800 mb-2">Subject Offering</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-semibold text-gray-500">Section</span>
                    <p class="text-base">{{ $teacherOffering->offering?->code ?? '-' }}</p>
                </div>
                <div>
                    <span class="font-semibold text-gray-500">Subject</span>
                    <p class="text-base">{{ $teacherOffering->offering?->subject?->code ?? '-' }} - {{ $teacherOffering->offering?->subject?->description ?? '' }}</p>
                </div>
                <div>
                    <span class="font-semibold text-gray-500">Academic Term</span>
                    <p class="text-base">{{ $teacherOffering->academicTerm?->description ?? '-' }}</p>
                </div>
                <div>
                    <span class="font-semibold text-gray-500">Period</span><br>
                    <select name="period" id="period" class="select select-bordered">
                        <option value="">Select a period</option>
                        @foreach ($periods as $period)
                            <option value="{{ strtolower($period) }}">{{ $period }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <div class="tabs tabs-lift tabs-lg mb-4">
                    <a id="tab-raw-btn" class="tab tab-active">Raw Score Sheet</a>
                    <a id="tab-detailed-btn" class="tab">Detailed Score</a>
                    <a id="tab-direct-btn" class="tab">Direct Score</a>
                </div>

                <div id="raw-score-pane" class="tab-pane">
                    <div id="student-list-container" class="mt-0 hidden">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">Enrolled Students</h3>
                        <div class="overflow-x-auto">
                            <table class="table border border-collapse border-gray-500 w-full">
                                <thead id="table-header"></thead>
                                <tbody id="student-list-body"></tbody>
                            </table>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="button" id="submit-shown-grades-btn" class="btn btn-primary btn-md" disabled>
                                Submit for Approval
                            </button>
                        </div>
                        <p id="no-students-text" class="text-sm text-gray-600 mt-3 hidden">No students are enrolled for this offering.</p>
                    </div>
                </div>

                <div id="detailed-score-pane" class="tab-pane hidden">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Detailed Score</h3>
                    <div class="overflow-x-auto">
                        <table class="table border border-collapse border-gray-500 w-full">
                            <thead id="detailed-table-header"></thead>
                            <tbody id="detailed-table-body"></tbody>
                        </table>
                    </div>
                </div>

                <div id="direct-score-pane" class="tab-pane hidden">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Direct Score</h3>
                    <div class="overflow-x-auto">
                        <table class="table border border-collapse border-gray-500 w-full">
                            <thead id="direct-table-header"></thead>
                            <tbody id="direct-table-body"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const studentGradesApiUrl = '{{ url('/teacher-portal/api/student-grades') }}';
        const csrfToken = '{{ csrf_token() }}';
        const periodSelect = document.getElementById('period');
        const studentListContainer = document.getElementById('student-list-container');
        const tableHeader = document.getElementById('table-header');
        const studentListBody = document.getElementById('student-list-body');
        const detailedTableHeader = document.getElementById('detailed-table-header');
        const detailedTableBody = document.getElementById('detailed-table-body');
        const directTableHeader = document.getElementById('direct-table-header');
        const directTableBody = document.getElementById('direct-table-body');
        const noStudentsText = document.getElementById('no-students-text');
        const submitShownGradesBtn = document.getElementById('submit-shown-grades-btn');
        const subjectEducationLevel = '{{ strtolower($teacherOffering->offering?->subject?->education_level ?? '') }}';

        const directGradeOptions = [
            { value: '1.00', label: '1.00 (99-100)' },
            { value: '1.25', label: '1.25 (96-98)' },
            { value: '1.50', label: '1.50 (93-95)' },
            { value: '1.75', label: '1.75 (90-92)' },
            { value: '2.00', label: '2.00 (87-89)' },
            { value: '2.25', label: '2.25 (84-86)' },
            { value: '2.50', label: '2.50 (81-83)' },
            { value: '2.75', label: '2.75 (78-80)' },
            { value: '3.00', label: '3.00 (75-77)' },
            { value: '4.00', label: '4.00 (Below 75)' },
        ];

        let currentComponents = [];
        let currentColumns = [];
        let currentRawScores = [];
        let currentStudents = [];
        let currentPeriod = '';
        const updateHighestScoreApiBase = '{{ url('/teacher-portal/api/grade-column') }}';
        const createGradeColumnApiBase = '{{ url('/teacher-portal/api/grade-column') }}';
        const rawScoreApiBase = '{{ url('/teacher-portal/api/raw-score') }}';
        const deleteGradeColumnApiBase = '{{ url('/teacher-portal/api/grade-column') }}';

        periodSelect.addEventListener('change', function() {
            loadGrades();
        });

        async function loadGrades() {
            const period = periodSelect.value;
            currentPeriod = period;
            if (!period) {
                studentListContainer.classList.add('hidden');
                if (submitShownGradesBtn) {
                    submitShownGradesBtn.disabled = true;
                }
                return;
            }

            studentListBody.innerHTML = '';
            noStudentsText.classList.add('hidden');
            studentListContainer.classList.remove('hidden');

            try {
                // ✅ Correct — teacherOfferingId goes in the path, period as query param
                const response = await fetch(`${studentGradesApiUrl}/{{ $teacherOffering->id }}?period=${encodeURIComponent(period)}`, {                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();
                const students = data.students || [];
                currentComponents = data.components || [];
                currentColumns = data.columns || [];
                currentRawScores = data.rawScores || [];
                currentStudents = students;

                if (students.length === 0) {
                    noStudentsText.classList.remove('hidden');
                    if (submitShownGradesBtn) {
                        submitShownGradesBtn.disabled = true;
                    }
                    renderDetailedScores([]);
                    renderDirectScores([]);
                    return;
                }
                if (submitShownGradesBtn) {
                    submitShownGradesBtn.disabled = false;
                }
                renderTableHeader();
                renderStudents(students);
                renderDetailedScores(students);
                renderDirectScores(students);
            } catch (error) {
                console.error('Error fetching grades:', error);
                studentListBody.innerHTML = '<tr><td colspan="3" class="text-red-600">Unable to load student list. Check console for details.</td></tr>';
                if (detailedTableBody) {
                    detailedTableBody.innerHTML = '<tr><td colspan="6" class="text-red-600">Unable to load detailed score list.</td></tr>';
                }
                if (directTableBody) {
                    directTableBody.innerHTML = '<tr><td colspan="4" class="text-red-600">Unable to load direct score list.</td></tr>';
                }
            }
        }

        function groupColumnsByComponent() {
            return currentColumns.reduce((groups, column) => {
                if (!groups[column.component_id]) {
                    groups[column.component_id] = [];
                }

                groups[column.component_id].push(column);
                return groups;
            }, {});
        }

        function getNumericValue(value) {
            const parsed = Number(value);
            return Number.isFinite(parsed) ? parsed : 0;
        }

        function isCollegeEducationLevel() {
            return subjectEducationLevel === 'college';
        }

        function convertBase50ToFourScale(baseGrade) {
            const clampedBaseGrade = Math.max(50, Math.min(100, getNumericValue(baseGrade)));
            const scale = 4 - ((clampedBaseGrade - 50) / 50) * 3;
            return Math.max(1, Math.min(4, scale));
        }

        function normalizeDirectGradeValue(value) {
            const formatted = Number(value).toFixed(2);
            return directGradeOptions.some(option => option.value === formatted) ? formatted : '';
        }

        function getDirectGradeLabel(value) {
            const normalized = normalizeDirectGradeValue(value);
            const option = directGradeOptions.find(item => item.value === normalized);
            return option ? option.label : '';
        }

        function calculateStudentComponentScores(student, component, columns) {
            const rawScoreMap = currentRawScores.reduce((map, rawScore) => {
                map[`${rawScore.grade_id}:${rawScore.grade_column_id}`] = rawScore;
                return map;
            }, {});

            // Calculate Total: sum of all raw scores for this student in this component
            let total = 0;
            columns.forEach(column => {
                const rawScore = rawScoreMap[`${student.grade_id}:${column.id}`];
                if (rawScore && rawScore.score) {
                    total += Number(rawScore.score);
                }
            });

            // Calculate component highest score: sum of all highest_scores in this component
            const componentHighestScore = columns.reduce((sum, column) => sum + Number(column.highest_score ?? 0), 0);

            // Calculate PS (Percentage Score): (Total / highest_score) * 100
            const ps = componentHighestScore > 0 ? (total / componentHighestScore) * 100 : 0;

            // Calculate WS (Weighted Score): (Total / highest_score) * 100 * (component.percentage / 100)
            const ws = componentHighestScore > 0 ? ((total / componentHighestScore) * 100) * (component.percentage / 100) : 0;

            return {
                total: total.toFixed(2),
                ps: ps.toFixed(2),
                ws: ws.toFixed(2)
            };
        }

        function calculateStudentGrades(student) {
            const columnsByComponent = groupColumnsByComponent();

            // Calculate Initial Grade (IG): sum of all WS (Weighted Scores) from all components
            let initialGrade = 0;
            currentComponents.forEach(component => {
                const columns = columnsByComponent[component.id] || [];
                const scores = calculateStudentComponentScores(student, component, columns);
                initialGrade += Number(scores.ws);
            });

            // Calculate Period Grade (PD): (IG * 0.5) + 50
            const periodGrade = (initialGrade * 0.5) + 50;

            return {
                initialGrade: initialGrade.toFixed(2),
                periodGrade: periodGrade.toFixed(2)
            };
        }

        function calculateDetailedStudentGradesFromInputs(row) {
            const inputs = row.querySelectorAll('.detailed-component-score-input');
            let initialGrade = 0;

            inputs.forEach(input => {
                const componentPercentage = getNumericValue(input.dataset.componentPercentage);
                const psValue = getNumericValue(input.value);
                initialGrade += (psValue * componentPercentage) / 100;
            });

            const basePeriodGrade = (initialGrade * 0.5) + 50;
            const periodGrade = isCollegeEducationLevel()
                ? convertBase50ToFourScale(basePeriodGrade)
                : basePeriodGrade;

            return {
                initialGrade: initialGrade.toFixed(2),
                periodGrade: periodGrade.toFixed(2),
            };
        }

        function updateDetailedStudentRow(row) {
            const calculated = calculateDetailedStudentGradesFromInputs(row);

            const initialGradeCell = row.querySelector('[data-detailed-field="initial-grade"]');
            const periodGradeCell = row.querySelector('[data-detailed-field="period-grade"]');

            if (initialGradeCell) {
                initialGradeCell.textContent = calculated.initialGrade;
            }

            if (periodGradeCell) {
                periodGradeCell.textContent = calculated.periodGrade;
            }
        }

        function renderDetailedScores(students) {
            const columnsByComponent = groupColumnsByComponent();

            detailedTableHeader.innerHTML = '';
            detailedTableBody.innerHTML = '';

            const headerRow = document.createElement('tr');
            headerRow.className = 'divide-x text-center';

            const studentHeader = document.createElement('th');
            studentHeader.className = 'bg-black text-white';
            studentHeader.textContent = 'Student Name';
            headerRow.appendChild(studentHeader);

            currentComponents.forEach(component => {
                const th = document.createElement('th');
                th.className = 'bg-black text-white';
                th.textContent = `${component.description || component.name || 'Component'} PS (${component.percentage ?? 0}%)`;
                headerRow.appendChild(th);
            });

            const initialGradeHeader = document.createElement('th');
            initialGradeHeader.className = 'bg-yellow-600 text-white';
            initialGradeHeader.textContent = 'Initial Grade';
            headerRow.appendChild(initialGradeHeader);

            const periodGradeHeader = document.createElement('th');
            periodGradeHeader.className = 'bg-green-700 text-white';
            periodGradeHeader.textContent = 'Period Grade';
            headerRow.appendChild(periodGradeHeader);

            const statusHeader = document.createElement('th');
            statusHeader.className = 'bg-slate-700 text-white';
            statusHeader.textContent = 'Status';
            headerRow.appendChild(statusHeader);

            detailedTableHeader.appendChild(headerRow);

            students.forEach(student => {
                const row = document.createElement('tr');
                row.className = 'divide-x';

                const nameCell = document.createElement('td');
                nameCell.textContent = student.student_name || '—';
                row.appendChild(nameCell);

                currentComponents.forEach(component => {
                    const columns = columnsByComponent[component.id] || [];
                    const scores = calculateStudentComponentScores(student, component, columns);

                    const td = document.createElement('td');
                    td.className = 'text-center';
                    td.innerHTML = `
                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            class="input input-sm input-bordered w-24 text-center detailed-component-score-input"
                            data-component-id="${component.id}"
                            data-component-percentage="${component.percentage ?? 0}"
                            value="${scores.ps}"
                            onchange="updateDetailedStudentRow(this.closest('tr'))"
                        />
                    `;
                    row.appendChild(td);
                });

                const initialGradeCell = document.createElement('td');
                initialGradeCell.className = 'text-center font-semibold text-gray-800';
                initialGradeCell.setAttribute('data-detailed-field', 'initial-grade');
                row.appendChild(initialGradeCell);

                const periodGradeCell = document.createElement('td');
                periodGradeCell.className = 'text-center font-semibold text-gray-800';
                periodGradeCell.setAttribute('data-detailed-field', 'period-grade');
                row.appendChild(periodGradeCell);

                const statusCell = document.createElement('td');
                statusCell.className = 'text-center text-gray-800';
                statusCell.textContent = student.status || 'draft';
                row.appendChild(statusCell);

                detailedTableBody.appendChild(row);
                updateDetailedStudentRow(row);
            });
        }

        function updateDirectStudentRow(row) {
            const select = row.querySelector('.direct-period-grade-select');
            const finalGradeCell = row.querySelector('[data-direct-field="final-grade"]');

            if (!select || !finalGradeCell) {
                return;
            }

            const selectedValue = select.value;
            finalGradeCell.textContent = getDirectGradeLabel(selectedValue);

            const gradeId = row.dataset.gradeId;
            currentStudents = currentStudents.map(student => {
                if (String(student.grade_id) === String(gradeId)) {
                    return {
                        ...student,
                        period_grade: selectedValue || null,
                    };
                }

                return student;
            });
        }

        function renderDirectScores(students) {
            directTableHeader.innerHTML = '';
            directTableBody.innerHTML = '';

            const headerRow = document.createElement('tr');
            headerRow.className = 'divide-x text-center';

            ['Student Name', 'Period Grade', 'Final Grade', 'Status'].forEach(label => {
                const th = document.createElement('th');
                th.className = label === 'Status' ? 'bg-slate-700 text-white' : (label === 'Final Grade' ? 'bg-green-700 text-white' : 'bg-black text-white');
                th.textContent = label;
                headerRow.appendChild(th);
            });

            directTableHeader.appendChild(headerRow);

            students.forEach(student => {
                const row = document.createElement('tr');
                row.className = 'divide-x';
                row.dataset.gradeId = student.grade_id;

                const nameCell = document.createElement('td');
                nameCell.textContent = student.student_name || '—';
                row.appendChild(nameCell);

                const periodGradeCell = document.createElement('td');
                periodGradeCell.className = 'text-center';
                const selectedPeriodGrade = normalizeDirectGradeValue(student.period_grade);
                periodGradeCell.innerHTML = `
                    <select class="select select-bordered select-sm direct-period-grade-select" onchange="updateDirectStudentRow(this.closest('tr'))">
                        <option value="">Select grade</option>
                        ${directGradeOptions.map(option => `<option value="${option.value}" ${option.value === selectedPeriodGrade ? 'selected' : ''}>${option.label}</option>`).join('')}
                    </select>
                `;
                row.appendChild(periodGradeCell);

                const finalGradeCell = document.createElement('td');
                finalGradeCell.className = 'text-center font-semibold';
                finalGradeCell.setAttribute('data-direct-field', 'final-grade');
                row.appendChild(finalGradeCell);

                const statusCell = document.createElement('td');
                statusCell.className = 'text-center font-semibold';
                const statusTextDiv = document.createElement('div');
                statusTextDiv.textContent = student.status || 'draft';
                statusCell.appendChild(statusTextDiv);
                row.appendChild(statusCell);

                directTableBody.appendChild(row);
                updateDirectStudentRow(row);
            });
        }

        function renderTableHeader() {
            const columnsByComponent = groupColumnsByComponent();
            const rowSpan = 3;

            tableHeader.innerHTML = '';

            const row1 = document.createElement('tr');
            row1.className = 'divide-x text-center';

            const studentHeader = document.createElement('th');
            studentHeader.className = 'bg-black text-white';
            studentHeader.rowSpan = rowSpan;
            studentHeader.textContent = 'Student Name';
            row1.appendChild(studentHeader);

            currentComponents.forEach(component => {
                const columns = columnsByComponent[component.id] || [];
                const span = Math.max(columns.length + 3, 3);

                const headerCell = document.createElement('th');
                headerCell.className = 'bg-black text-white text-center';
                headerCell.colSpan = span;
                headerCell.innerHTML = `
                    <div class="flex items-center justify-center gap-2">
                        <span>${component.description || component.name || 'Component'}</span>
                        <button type="button" class="btn btn-xs btn-success text-white" onclick="addComponentColumn(${component.id})">+</button>
                    </div>
                `;
                row1.appendChild(headerCell);
            });

            const initialGradeHeader = document.createElement('th');
            initialGradeHeader.className = 'bg-yellow-600 text-white';
            initialGradeHeader.rowSpan = rowSpan;
            initialGradeHeader.textContent = 'Initial Grade';
            row1.appendChild(initialGradeHeader);

            const periodGradeHeader = document.createElement('th');
            periodGradeHeader.className = 'bg-green-700 text-white';
            periodGradeHeader.rowSpan = rowSpan;
            periodGradeHeader.textContent = 'Period Grade';
            row1.appendChild(periodGradeHeader);

            const statusHeader = document.createElement('th');
            statusHeader.className = 'bg-slate-700 text-white';
            statusHeader.rowSpan = rowSpan;
            statusHeader.textContent = 'Status';
            row1.appendChild(statusHeader);

            tableHeader.appendChild(row1);

            const row2 = document.createElement('tr');
            row2.className = 'divide-x text-center';

            currentComponents.forEach(component => {
                const columns = columnsByComponent[component.id] || [];

                columns.forEach(column => {
                    const th = document.createElement('th');
                    th.className = 'bg-white text-gray-800';
                    th.innerHTML = `
                        <div class="flex items-center justify-center gap-2">
                            <span>${column.component_code || ''}${column.column_number}</span>
                            <button type="button" class="text-red-500" onclick="deleteColumn(${column.id})">x</button>
                        </div>
                    `;
                    row2.appendChild(th);
                });

                ['Total', 'PS', 'WS'].forEach(label => {
                    const th = document.createElement('th');
                    th.textContent = label;
                    row2.appendChild(th);
                });
            });

            tableHeader.appendChild(row2);

            const row3 = document.createElement('tr');
            row3.className = 'divide-x text-center';

            currentComponents.forEach(component => {
                const columns = columnsByComponent[component.id] || [];

                columns.forEach(column => {
                    const th = document.createElement('th');
                    th.className = 'bg-white text-gray-700 text-xs';
                    th.innerHTML = `
                        <input
                            type="number"
                            min="0"
                            step="0.01"
                            class="input input-sm input-bordered w-24 text-center"
                            value="${column.highest_score ?? 0}"
                            onchange="updateHighestScore(${column.id}, this.value)"
                        />
                    `;
                    row3.appendChild(th);
                });

                const componentTotal = columns.reduce((sum, column) => sum + Number(column.highest_score ?? 0), 0);

                [String(componentTotal), '100%', `${component.percentage ?? 0}%`].forEach(value => {
                    const th = document.createElement('th');
                    th.className = 'bg-white text-gray-700 text-xs';
                    th.textContent = value;
                    row3.appendChild(th);
                });
            });

            tableHeader.appendChild(row3);
        }

        function renderStudents(students) {
            const columnsByComponent = groupColumnsByComponent();
            const rawScoreMap = currentRawScores.reduce((map, rawScore) => {
                map[`${rawScore.grade_id}:${rawScore.grade_column_id}`] = rawScore;
                return map;
            }, {});

            studentListBody.innerHTML = '';

            students.forEach(student => {
                const row = document.createElement('tr');
                row.classList.add('divide-x');

                const nameCell = document.createElement('td');
                nameCell.textContent = student.student_name || '—';
                row.appendChild(nameCell);

                currentComponents.forEach(component => {
                    const columns = columnsByComponent[component.id] || [];

                    columns.forEach(column => {
                        const rawScore = rawScoreMap[`${student.grade_id}:${column.id}`];
                        const td = document.createElement('td');
                        td.className = 'text-center';
                        td.innerHTML = `
                            <input
                                type="number"
                                class="input input-sm input-bordered w-20"
                                value="${rawScore?.score ?? ''}"
                                placeholder="-"
                                onchange="updateRawScore(${student.grade_id}, ${column.id}, this.value)"
                            />
                        `;
                        row.appendChild(td);
                    });

                    // Calculate Total, PS, WS for this component
                    const scores = calculateStudentComponentScores(student, component, columns);

                    [scores.total, `${scores.ps}%`, `${scores.ws}%`].forEach(value => {
                        const td = document.createElement('td');
                        td.className = 'text-center text-gray-800 font-semibold';
                        td.textContent = value;
                        row.appendChild(td);
                    });
                });

                // Calculate auto-generated grades
                const calculatedGrades = calculateStudentGrades(student);

                const initialGradeCell = document.createElement('td');
                initialGradeCell.className = 'text-center font-semibold text-gray-800';
                initialGradeCell.textContent = calculatedGrades.initialGrade;
                row.appendChild(initialGradeCell);

                const periodGradeCell = document.createElement('td');
                periodGradeCell.className = 'text-center font-semibold text-gray-800';
                periodGradeCell.textContent = calculatedGrades.periodGrade;
                row.appendChild(periodGradeCell);

                const statusCell = document.createElement('td');
                statusCell.className = 'text-center font-semibold';
                statusCell.textContent = student.status || 'draft';
                row.appendChild(statusCell);

                studentListBody.appendChild(row);
            });
        }

        function addComponentColumn(componentId) {
            if (!currentPeriod) {
                return;
            }

            createColumn(componentId, currentPeriod);
        }

        async function createColumn(componentId, period) {
            try {
                const response = await fetch(`${createGradeColumnApiBase}/{{ $teacherOffering->id }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        component_id: componentId,
                        period: period,
                    })
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                await loadGrades();
            } catch (error) {
                console.error('Error creating grade column:', error);
                alert('Failed to create grade column');
            }
        }

        async function deleteColumn(gradeColumnId) {
            const confirmed = await confirmDialog('Delete this grade column and all raw scores under it?', {
                title: 'Confirm Delete',
                confirmText: 'Delete',
                confirmClass: 'btn-error'
            });
            if (!confirmed) return;

            try {
                const response = await fetch(`${deleteGradeColumnApiBase}/${gradeColumnId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                await loadGrades();
            } catch (error) {
                console.error('Error deleting grade column:', error);
                alert('Failed to delete grade column');
            }
        }

        async function updateHighestScore(gradeColumnId, highestScore) {
            try {
                const response = await fetch(`${updateHighestScoreApiBase}/${gradeColumnId}/highest-score`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ highest_score: highestScore })
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();
                currentColumns = currentColumns.map(column => {
                    if (column.id === data.id) {
                        return {
                            ...column,
                            highest_score: data.highest_score,
                        };
                    }

                    return column;
                });

                renderTableHeader();
                renderStudents(currentStudents);
                renderDetailedScores(currentStudents);
                renderDirectScores(currentStudents);
            } catch (error) {
                console.error('Error updating highest score:', error);
                alert('Failed to update highest score');
            }
        }

        async function updateRawScore(gradeId, gradeColumnId, score) {
            try {
                const response = await fetch(`${rawScoreApiBase}/${gradeId}/${gradeColumnId}`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ score: score === '' ? null : Number(score) })
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }

                const data = await response.json();
                currentRawScores = currentRawScores.filter(rawScore => rawScore.id !== data.id);
                currentRawScores.push(data);

                // Re-render students to update Total, PS, WS calculations
                renderStudents(currentStudents);
                renderDetailedScores(currentStudents);
                renderDirectScores(currentStudents);
            } catch (error) {
                console.error('Error updating raw score:', error);
                alert('Failed to update raw score');
            }
        }

        async function submitShownGradesForApproval() {
            if (!currentStudents.length || !submitShownGradesBtn) {
                return;
            }

            const eligibleStudents = currentStudents
                .map(student => {
                    const calculatedGrades = calculateStudentGrades(student);
                    return {
                        student,
                        initialGrade: Number(calculatedGrades.initialGrade),
                        periodGrade: Number(calculatedGrades.periodGrade),
                    };
                })
                .filter(item => item.initialGrade > 0);

            if (eligibleStudents.length === 0) {
                alert('No students with an initial grade above 0 are ready for submission.');
                return;
            }

            const originalText = submitShownGradesBtn.textContent;
            submitShownGradesBtn.disabled = true;
            submitShownGradesBtn.textContent = 'Submitting...';

            try {
                const responses = await Promise.all(eligibleStudents.map(item => fetch(`{{ url('/teacher-portal/api/grade') }}/${item.student.grade_id}/status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        status: 'submitted',
                        initial_grade: item.initialGrade,
                        period_grade: item.periodGrade,
                    })
                })));

                const failedResponses = responses.filter(response => !response.ok);
                if (failedResponses.length > 0) {
                    throw new Error(`Failed to submit ${failedResponses.length} grade(s)`);
                }

                currentStudents = currentStudents.map(student => {
                    const submittedItem = eligibleStudents.find(item => String(item.student.grade_id) === String(student.grade_id));

                    if (!submittedItem) {
                        return student;
                    }

                    return {
                        ...student,
                        status: 'submitted',
                        initial_grade: submittedItem.initialGrade,
                        period_grade: submittedItem.periodGrade,
                    };
                });

                renderStudents(currentStudents);
                renderDetailedScores(currentStudents);
                renderDirectScores(currentStudents);
            } catch (error) {
                console.error('Error submitting for approval:', error);
                alert('Failed to submit for approval');
                submitShownGradesBtn.disabled = false;
                submitShownGradesBtn.textContent = originalText;
            }
        }

        // Tab switching logic (web-style tabs)
        function showTab(paneId, btnId) {
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
            const pane = document.getElementById(paneId);
            if (pane) pane.classList.remove('hidden');

            document.querySelectorAll('.tab').forEach(b => b.classList.remove('tab-active'));
            const btn = document.getElementById(btnId);
            if (btn) btn.classList.add('tab-active');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const rawBtn = document.getElementById('tab-raw-btn');
            const detailedBtn = document.getElementById('tab-detailed-btn');
            const directBtn = document.getElementById('tab-direct-btn');

            if (rawBtn) rawBtn.addEventListener('click', (e) => { e.preventDefault(); showTab('raw-score-pane', 'tab-raw-btn'); });
            if (detailedBtn) detailedBtn.addEventListener('click', (e) => { e.preventDefault(); showTab('detailed-score-pane', 'tab-detailed-btn'); });
            if (directBtn) directBtn.addEventListener('click', (e) => { e.preventDefault(); showTab('direct-score-pane', 'tab-direct-btn'); });
            if (submitShownGradesBtn) submitShownGradesBtn.addEventListener('click', submitShownGradesForApproval);

            // default tab
            showTab('raw-score-pane', 'tab-raw-btn');
        });
    </script>

</x-teacher_portal_sidebar>
