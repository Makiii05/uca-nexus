<x-department_sidebar>

    @include('partials.notifications')
    @include('partials.dept-student-full-modal')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl">Subject Offering</h2>
        @if(isset($academicTerm))
            <h2 class="flex-1 text-2xl"><span>(Academic Term: <strong>{{ $academicTerm->description }}</strong>)</span></h2>
        @endif
    </div>
    
    <div class="flex gap-5">
        <!--LEFT PANEL: PROSPECTUS + SUBJECT SEARCH-->
        <div class="overflow-x-auto bg-white shadow w-1/2">
            <!-- Tabs -->
            <div class="flex border-b">
                <button type="button" id="tabProspectus" onclick="switchTab('prospectus')"
                    class="flex-1 py-3 px-4 text-sm font-semibold text-center bg-black text-white transition-colors duration-200">
                    Search by Prospectus
                </button>
                <button type="button" id="tabSubject" onclick="switchTab('subject')"
                    class="flex-1 py-3 px-4 text-sm font-semibold text-center bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors duration-200">
                    Search by Subject
                </button>
            </div>

            <!-- Prospectus Search Tab -->
            <div id="panelProspectus">
                <div class="p-4">
                    <form action="{{ route('department.subject_offering.search') }}" method="POST" class="grow flex gap-2 items-center">
                        @csrf
                        @if(isset($academicTerm))
                        <input type="hidden" name="academic_term_id" value="{{ $academicTerm->id }}" />
                        @endif
                        <span class="text-sm font-semibold px-3 py-1 bg-base-200 rounded">{{ $department->description ?? 'N/A' }}</span>
                        <select name="curriculum" id="curriculumSelect" class="select select-bordered select-sm" required>
                            <option value="">--Select Curriculum--</option>
                            @foreach ($curricula as $curriculum)
                            <option value="{{ $curriculum->id }}" @if(isset($old_curriculum) && $old_curriculum == $curriculum->id) selected @endif>{{ $curriculum->curriculum }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-sm btn-neutral">Search</button>
                    </form>
                </div>
                <div>
                    @if(isset($prospectuses))
                        <div id="prospectusContainer">
                        @if ($prospectuses->count() > 0)
                        <div class="m-4">
                            @foreach ($prospectuses->groupBy('level_id') as $levelId => $groupedByLevel)
                            @php
                                $level = $groupedByLevel->first()->level;
                            @endphp
                            <details class="collapse bg-base-100 border-base-300 border mb-3">
                                <summary class="collapse-title font-semibold">{{ $level->program->code}} - {{ $level->description }}</summary>
                                <div class="collapse-content text-sm">
                                    @foreach ($groupedByLevel->sortBy('term.description')->groupBy('term_id') as $termId => $groupedByTerm)
                                    @php
                                        $term = $groupedByTerm->first()->term;
                                    @endphp
                                    <details class="collapse bg-base-100 border-base-300 border mb-3">
                                        <summary class="collapse-title font-semibold">
                                            {{ $term?->description ?? 'No Academic Term' }}
                                        </summary>
                                        <div class="collapse-content text-sm">
                                            <table class="table table-zebra">
                                                <thead>
                                                    <tr>
                                                        <th>Code</th>
                                                        <th>Description</th>
                                                        <th>Unit</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($groupedByTerm->sortBy('subject.description') as $prospectus)
                                                    <tr class="cursor-pointer hover:bg-blue-50 transition-colors duration-150"
                                                        onclick="openProspectusGradingModal({{ $prospectus->subject->id }}, {{ $level->program->id }}, {{ $level->id }})">
                                                        <td>{{ $prospectus->subject->code }}</td>
                                                        <td>{{ $prospectus->subject->description }}</td>
                                                        <td>{{ $prospectus->subject->unit }}</td>
                                                        <td class="w-10 text-center">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                                            </svg>   
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </details>
                                    @endforeach
                                </div>  
                            </details>
                            @endforeach
                        </div>
                        @else
                        <div class="m-4 alert bg-blue-400 text-white">
                            <span>No prospectuses found for the selected criteria.</span>
                        </div>
                        @endif
                        </div>
                    @else
                        <div id="prospectusContainer"></div>
                    @endif
                </div>
            </div>

            <!-- Subject Search Tab -->
            <div id="panelSubject" class="hidden">
                <div class="p-4">
                    <div class="flex gap-2 items-center">
                        <input type="text" id="subjectSearchInput" placeholder="Search subject code or description..." class="input input-bordered grow" />
                    </div>
                </div>
                <div class="px-4 pb-4">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="subjectSearchResults">
                            <tr>
                                <td colspan="3" class="text-center text-gray-500 py-8">Type to search subjects...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    
        <!--SUBJECT OFFERING TABLE-->
        <div class="overflow-x-auto shadow w-1/2 bg-white p-2">
            <div class="bg-white" data-table-wrapper>
                <div class="p-4 flex items-center justify-between">
                    <h3 class="font-semibold text-lg">Subject Offerings</h3>
                    @if(isset($academicTerm) && isset($subjectOfferings) && $subjectOfferings->count() > 0)
                        <a href="{{ route('department.print.subject_offerings', ['academic_term_id' => $academicTerm->id]) }}" target="_blank" class="btn btn-sm btn-neutral">
                            Print PDF
                        </a>
                    @endif
                </div>
                <table class="table table-zebra" data-sortable-table>
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Units</th>
                            <th>Class Size</th>
                            <th data-no-sort>Action</th>
                        </tr>
                    </thead>
                    <tbody id="subjectOfferingTableBody">
                        @if(isset($subjectOfferings) && $subjectOfferings->count() > 0)
                            @foreach ($subjectOfferings as $offering)
                            <tr id="offering-{{ $offering->id }}">
                                <td>{{ $offering->code }}</td>
                                <td>{{ $offering->description }}</td>
                                <td>{{ $offering->subject->unit ?? '-' }}</td>
                                <td class="{{ $offering->enlistments->count() > $offering->class_size ? 'text-red-600' : 'text-green-600' }}">{{ $offering->enlistments->count() }}/{{ $offering->class_size }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-ghost text-red-600 font-semibold" onclick="removeOffering({{ $offering->id }})">Remove</button>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr id="no-offerings-row">
                                <td colspan="5" class="text-center text-gray-500 py-8">No subject offerings yet.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('partials.table-sort-search')

    <!-- Program and Level Selection Modal (for subject search tab - subjects without program context) -->
    <dialog id="programSelectModal" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold mb-4">Select Program and Level</h3>
            <p class="text-sm text-gray-500 mb-3">Choose the program and level this subject will be offered under:</p>
            <input type="hidden" id="pendingSubjectId" value="" />
            <div class="form-control mb-3">
                <label class="label"><span class="label-text">Program</span></label>
                <select id="programSelect" class="select select-bordered w-full" required onchange="loadLevelsForProgram(this.value)">
                    <option value="">-- Select Program --</option>
                    @foreach ($programs as $program)
                        <option value="{{ $program->id }}">{{ $program->code }} - {{ $program->description }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-control mb-3">
                <label class="label"><span class="label-text">Level (Year)</span></label>
                <select id="levelSelect" class="select select-bordered w-full" disabled>
                    <option value="">-- Select Program First --</option>
                </select>
            </div>
            <div class="form-control mb-3">
                <label class="label"><span class="label-text">Grading System</span></label>
                <select id="subjectGradingSelect" class="select select-bordered w-full" required>
                    <option value="">-- Select Grading System --</option>
                    @foreach (($gradingSystems ?? collect()) as $gradingSystem)
                        <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->description }} ({{ number_format((float) $gradingSystem->total_percentage, 2) }}%)</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('programSelectModal').close();">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmAddWithProgramAndLevel()">Add to Offerings</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <dialog id="prospectusGradingModal" class="modal">
        <div class="modal-box">
            <h3 class="text-lg font-bold mb-4">Select Grading System</h3>
            <p class="text-sm text-gray-500 mb-3">Choose the grading system for this subject offering.</p>
            <input type="hidden" id="prospectusPendingSubjectId" value="" />
            <input type="hidden" id="prospectusPendingProgramId" value="" />
            <input type="hidden" id="prospectusPendingLevelId" value="" />
            <div class="form-control mb-3">
                <label class="label"><span class="label-text">Grading System</span></label>
                <select id="prospectusGradingSelect" class="select select-bordered w-full" required>
                    <option value="">-- Select Grading System --</option>
                    @foreach (($gradingSystems ?? collect()) as $gradingSystem)
                        <option value="{{ $gradingSystem->id }}">{{ $gradingSystem->description }} ({{ number_format((float) $gradingSystem->total_percentage, 2) }}%)</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('prospectusGradingModal').close();">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmAddFromProspectus()">Add to Offerings</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

</x-department_sidebar>

<script>
    const curriculaApiUrl = '{{ url("/department/api/curricula-by-department") }}';
    const subjectOfferingApiUrl = '{{ url("/department/api/subject-offering") }}';
    const addSubjectOfferingUrl = '{{ route("department.subject_offering.add") }}';
    const removeSubjectOfferingUrl = '{{ url("/department/subject-offering") }}';
    const searchSubjectsUrl = '{{ route("department.api.subjects.search") }}';
    const levelsByProgramUrl = '{{ url("/department/api/levels-by-program") }}';
    const gradingSystemsCount = {{ ($gradingSystems ?? collect())->count() }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const academicTermId = {{ isset($academicTerm) ? $academicTerm->id : 'null' }};
    const departmentId = {{ $departmentId ?? 'null' }};

    // Tab switching
    function switchTab(tab) {
        const tabProspectus = document.getElementById('tabProspectus');
        const tabSubject = document.getElementById('tabSubject');
        const panelProspectus = document.getElementById('panelProspectus');
        const panelSubject = document.getElementById('panelSubject');

        if (tab === 'prospectus') {
            tabProspectus.className = 'flex-1 py-3 px-4 text-sm font-semibold text-center bg-black text-white transition-colors duration-200';
            tabSubject.className = 'flex-1 py-3 px-4 text-sm font-semibold text-center bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors duration-200';
            panelProspectus.classList.remove('hidden');
            panelSubject.classList.add('hidden');
        } else {
            tabSubject.className = 'flex-1 py-3 px-4 text-sm font-semibold text-center bg-black text-white transition-colors duration-200';
            tabProspectus.className = 'flex-1 py-3 px-4 text-sm font-semibold text-center bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors duration-200';
            panelSubject.classList.remove('hidden');
            panelProspectus.classList.add('hidden');
        }
    }

    // For prospectus rows — program and level are already known
    function addOfferingWithProgramAndLevel(subjectId, programId, levelId, gradingId) {
        if (!academicTermId) {
            alert('No academic term selected.');
            return;
        }
        if (!gradingId) {
            alert('Please select a grading system.');
            return;
        }
        submitAddOffering(subjectId, programId, levelId, gradingId);
    }

    function openProspectusGradingModal(subjectId, programId, levelId) {
        if (!academicTermId) {
            alert('No academic term selected.');
            return;
        }

        if (gradingSystemsCount < 1) {
            alert('No grading systems found for your department. Please create one first.');
            return;
        }

        document.getElementById('prospectusPendingSubjectId').value = subjectId;
        document.getElementById('prospectusPendingProgramId').value = programId;
        document.getElementById('prospectusPendingLevelId').value = levelId;
        document.getElementById('prospectusGradingSelect').value = '';
        document.getElementById('prospectusGradingModal').showModal();
    }

    function confirmAddFromProspectus() {
        const subjectId = document.getElementById('prospectusPendingSubjectId').value;
        const programId = document.getElementById('prospectusPendingProgramId').value;
        const levelId = document.getElementById('prospectusPendingLevelId').value;
        const gradingId = document.getElementById('prospectusGradingSelect').value;

        if (!gradingId) {
            alert('Please select a grading system.');
            return;
        }

        document.getElementById('prospectusGradingModal').close();
        addOfferingWithProgramAndLevel(subjectId, programId, levelId, gradingId);
    }

    // For subject search rows — need to pick a program and level first
    function openProgramModal(subjectId) {
        if (!academicTermId) {
            alert('No academic term selected.');
            return;
        }
        document.getElementById('pendingSubjectId').value = subjectId;
        document.getElementById('programSelect').value = '';
        document.getElementById('levelSelect').innerHTML = '<option value="">-- Select Program First --</option>';
        document.getElementById('levelSelect').disabled = true;
        document.getElementById('subjectGradingSelect').value = '';

        if (gradingSystemsCount < 1) {
            alert('No grading systems found for your department. Please create one first.');
            return;
        }

        document.getElementById('programSelectModal').showModal();
    }

    // Load levels when program changes
    async function loadLevelsForProgram(programId) {
        const levelSelect = document.getElementById('levelSelect');
        
        if (!programId) {
            levelSelect.innerHTML = '<option value="">-- Select Program First --</option>';
            levelSelect.disabled = true;
            return;
        }

        levelSelect.innerHTML = '<option value="">Loading...</option>';
        levelSelect.disabled = true;

        try {
            const response = await fetch(`${levelsByProgramUrl}/${programId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const levels = await response.json();

            levelSelect.innerHTML = '<option value="">-- Select Level --</option>';
            levels.forEach(level => {
                const option = document.createElement('option');
                option.value = level.id;
                option.textContent = level.description;
                levelSelect.appendChild(option);
            });
            levelSelect.disabled = false;
        } catch (error) {
            console.error('Error loading levels:', error);
            levelSelect.innerHTML = '<option value="">-- Error loading levels --</option>';
            levelSelect.disabled = false;
        }
    }

    function confirmAddWithProgramAndLevel() {
        const subjectId = document.getElementById('pendingSubjectId').value;
        const programId = document.getElementById('programSelect').value;
        const levelId = document.getElementById('levelSelect').value;
        const gradingId = document.getElementById('subjectGradingSelect').value;
        if (!programId) {
            alert('Please select a program.');
            return;
        }
        if (!levelId) {
            alert('Please select a level.');
            return;
        }
        if (!gradingId) {
            alert('Please select a grading system.');
            return;
        }
        document.getElementById('programSelectModal').close();
        submitAddOffering(subjectId, programId, levelId, gradingId);
    }

    // Shared add offering function
    async function submitAddOffering(subjectId, programId, levelId = null, gradingId = null) {
        try {
            const payload = {
                academic_term_id: academicTermId,
                subject_id: subjectId,
                program_id: programId,
                grading_id: gradingId,
            };
            if (levelId) {
                payload.level_id = levelId;
            }

            const response = await fetch(addSubjectOfferingUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(payload)
            });

            if (response.status === 422) {
                const data = await response.json();
                alert(data.error || 'Subject already added.');
                return;
            }

            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

            const newOffering = await response.json();
            appendOfferingRow(newOffering);
        } catch (error) {
            console.error('Error adding subject offering:', error);
            alert('Error adding subject offering.');
        }
    }

    // Remove subject offering
    async function removeOffering(offeringId) {
        const confirmed = await confirmDialog('Remove this subject from offerings?', {
            title: 'Confirm Removal',
            confirmText: 'Remove',
            confirmClass: 'btn-error'
        });
        if (!confirmed) return;

        try {
            const response = await fetch(`${removeSubjectOfferingUrl}/${offeringId}/remove`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

            document.getElementById(`offering-${offeringId}`)?.remove();

            const tbody = document.getElementById('subjectOfferingTableBody');
            if (tbody.children.length === 0) {
                tbody.innerHTML = '<tr id="no-offerings-row"><td colspan="4" class="text-center text-gray-500 py-8">No subject offerings yet.</td></tr>';
            }
        } catch (error) {
            console.error('Error removing subject offering:', error);
            alert('Error removing subject offering.');
        }
    }

    // Append a new offering row to the table
    function appendOfferingRow(offering) {
        const tbody = document.getElementById('subjectOfferingTableBody');
        const noRow = document.getElementById('no-offerings-row');
        if (noRow) noRow.remove();

        const row = document.createElement('tr');
        row.id = `offering-${offering.id}`;
        row.innerHTML = `
            <td>${offering.code}</td>
            <td>${offering.description}</td>
            <td>${offering.subject?.unit ?? '-'}</td>
            <td class="text-green-600">0/40</td>
            <td><button type="button" class="btn btn-sm btn-ghost text-red-600 font-semibold" onclick="removeOffering(${offering.id})">Remove</button></td>
        `;
        tbody.prepend(row);
    }

    // Load subject offerings by academic term (for refresh)
    async function loadSubjectOfferings() {
        if (!academicTermId || !departmentId) return;

        try {
            const response = await fetch(`${subjectOfferingApiUrl}/${academicTermId}/${departmentId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const offerings = await response.json();

            const tbody = document.getElementById('subjectOfferingTableBody');
            tbody.innerHTML = '';

            if (offerings.length > 0) {
                offerings.forEach(offering => appendOfferingRow(offering));
            } else {
                tbody.innerHTML = '<tr id="no-offerings-row"><td colspan="5" class="text-center text-gray-500 py-8">No subject offerings yet.</td></tr>';
            }
        } catch (error) {
            console.error('Error loading subject offerings:', error);
        }
    }

    // Load curricula by department (dynamic)
    async function loadCurriculaByDepartment(departmentId, selectElement, selectedCurriculumId = null) {
        if (!departmentId) {
            selectElement.innerHTML = '<option value="">--Select Curriculum--</option>';
            selectElement.disabled = false;
            return;
        }

        selectElement.innerHTML = '<option value="">Loading...</option>';
        selectElement.disabled = true;

        try {
            const response = await fetch(`${curriculaApiUrl}/${departmentId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const curricula = await response.json();

            selectElement.innerHTML = '<option value="">--Select Curriculum--</option>';
            curricula.forEach(c => {
                const option = document.createElement('option');
                option.value = c.id;
                option.textContent = c.curriculum;
                if (selectedCurriculumId && c.id == selectedCurriculumId) {
                    option.selected = true;
                }
                selectElement.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading curricula:', error);
            selectElement.innerHTML = '<option value="">--Error loading curricula--</option>';
        } finally {
            selectElement.disabled = false;
        }
    }

    // Curricula are now rendered server-side, no need for dynamic loading

    // Subject search with debounce
    let searchTimeout = null;
    document.getElementById('subjectSearchInput')?.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        const tbody = document.getElementById('subjectSearchResults');

        if (query.length < 1) {
            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-8">Type to search subjects...</td></tr>';
            return;
        }

        tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-4">Searching...</td></tr>';

        searchTimeout = setTimeout(async () => {
            try {
                const response = await fetch(`${searchSubjectsUrl}?q=${encodeURIComponent(query)}`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const subjects = await response.json();

                tbody.innerHTML = '';
                if (subjects.length > 0) {
                    subjects.forEach(subject => {
                        const row = document.createElement('tr');
                        row.className = 'cursor-pointer hover:bg-blue-50 transition-colors duration-150';
                        row.onclick = () => openProgramModal(subject.id);
                        row.innerHTML = `
                            <td>${subject.code}</td>
                            <td>${subject.description}</td>
                            <td>${subject.unit}</td>
                            <td class="w-10 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>   
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-8">No subjects found.</td></tr>';
                }
            } catch (error) {
                console.error('Error searching subjects:', error);
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-red-500 py-8">Error searching subjects.</td></tr>';
            }
        }, 300);
    });
</script>        