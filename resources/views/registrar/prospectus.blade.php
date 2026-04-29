<x-registrar_sidebar>

    <div class="m-4 font-bold text-4xl">
        <h2>Prospectus</h2>
    </div>

    <div class="m-4 flex">
        <form action="{{ route('registrar.prospectus.search') }}" method="POST" class="grow flex gap-2 items-center">
            @csrf
            <select name="department" id="departmentSelect" class="select select-bordered" required>
                <option value="">--Select Department--</option>
                @foreach ($departments as $department)
                <option value="{{ $department->id }}" @if(isset($old_department) && $old_department == $department->id) selected @endif>{{ $department->description }}</option>
                @endforeach
            </select>
            <select name="curriculum" id="curriculumSelect" class="select select-bordered" required>
                <option value="">--Select Curriculum--</option>
            </select>
            <button type="submit" class="btn bg-white">Search</button>
        </form>
        <button class="btn w-auto justify-self-end bg-white shadow" onclick="form_modal.showModal()">Add Prospectus</button>
    </div>

    
    <!--FORM-->
    <dialog id="form_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add Prospectus</h3>
            <form id="createProspectusForm" class="space-y-4 space-x-4 grid grid-cols-2">
                @csrf
                
                <div class="form-control col-span-2">
                    <label class="label">
                        <span class="label-text">Curriculum</span>
                    </label>
                    <select name="curriculum" id="addModalCurriculum" class="select select-bordered w-full">
                        <option value="">--Select Curriculum--</option>
                        @foreach ($curricula as $curriculum)
                        <option value="{{ $curriculum->id }}" data-department-id="{{ $curriculum->department_id }}">{{ $curriculum->curriculum }} - {{$curriculum->department->description}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Level</span>
                    </label>
                    <select id="addModalLevelSelect" name="level" class="select select-bordered w-full">
                        <option value="">--Select Curriculum First--</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Academic Term</span>
                    </label>
                    <select id="addModalTermSelect" name="term" class="select select-bordered w-full">
                        <option value="">--Select Curriculum First--</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Subject</span>
                    </label>
                    <select name="subject" id="addModalSubject" class="select select-bordered w-full">
                        @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select name="status" id="addModalStatus" class="select select-bordered w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="modal-action col-span-2">
                    <button type="button" class="btn" onclick="form_modal.close()">Cancel</button>
                    <button type="submit" id="saveProspectusBtn" class="btn btn-primary" disabled>
                        <span class="loading loading-spinner loading-sm hidden"></span>
                        Save Prospectus
                    </button>
                </div>
            </form>
        </div>
    </dialog>

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
                                        <th>Curriculum</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Lec Hour</th>
                                        <th>Lab Hour</th>
                                        <th>Lec Unit</th>
                                        <th>Lab Unit</th>
                                        <th>Total Unit</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedByTerm->sortBy('subject.description') as $prospectus)
                                    <tr data-prospectus-id="{{ $prospectus->id }}">
                                        <td>{{ $prospectus->curriculum->curriculum }}</td>
                                        <td>{{ $prospectus->subject->code }}</td>
                                        <td>{{ $prospectus->subject->description }}</td>
                                        <td>{{ $prospectus->subject->lech }}</td>
                                        <td>{{ $prospectus->subject->labh }}</td>
                                        <td>{{ $prospectus->subject->lecu }}</td>
                                        <td>{{ $prospectus->subject->labu }}</td>
                                        <td>{{ $prospectus->subject->unit }}</td>
                                        <td>{{ $prospectus->status }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-ghost edit-btn" data-id="{{ $prospectus->id }}" onclick="openEditModal({{ $prospectus->id }})">Edit</button>
                                            <button type="button" class="btn btn-sm btn-ghost text-red-500 delete-btn" data-id="{{ $prospectus->id }}" onclick="deleteProspectus({{ $prospectus->id }}, this)">
                                                <span class="loading loading-spinner loading-sm hidden"></span>
                                                Delete
                                            </button>
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

    <!-- Dynamic Edit Modal -->
    <dialog id="edit_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Edit Prospectus</h3>
            <form id="editProspectusForm" class="space-y-4 space-x-4 grid grid-cols-2">
                <input type="hidden" id="editProspectusId" value="">
                
                <div class="form-control col-span-2">
                    <label class="label">
                        <span class="label-text">Curriculum</span>
                    </label>
                    <select name="curriculum" id="editModalCurriculum" class="select select-bordered w-full">
                        @foreach ($curricula as $curriculum)
                        <option value="{{ $curriculum->id }}" data-department-id="{{ $curriculum->department_id }}">{{ $curriculum->curriculum }} - {{$curriculum->department->description}}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Level</span>
                    </label>
                    <select name="level" id="editModalLevelSelect" class="select select-bordered w-full">
                        <option value="">--Select Level--</option>
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Academic Term</span>
                    </label>
                    <select name="term" id="editModalTermSelect" class="select select-bordered w-full">
                        <option value="">--Select Curriculum First--</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Subject</span>
                    </label>
                    <select name="subject" id="editModalSubject" class="select select-bordered w-full">
                        @foreach ($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select name="status" id="editModalStatus" class="select select-bordered w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="modal-action col-span-2">
                    <button type="button" class="btn" onclick="edit_modal.close()">Cancel</button>
                    <button type="submit" id="updateProspectusBtn" class="btn btn-primary">
                        <span class="loading loading-spinner loading-sm hidden"></span>
                        Update Prospectus
                    </button>
                </div>
            </form>
        </div>
    </dialog>
</x-registrar_sidebar>

<script>
    const csrfToken = '{{ csrf_token() }}';
    const levelsApiUrl = '{{ url("/registrar/api/levels-by-department") }}';
    const curriculaApiUrl = '{{ url("/registrar/api/curricula-by-department") }}';
    const termsApiUrl = '{{ url("/registrar/api/terms-by-department") }}';
    const prospectusApiUrl = '{{ url("/registrar/api/prospectuses") }}';
    const createUrl = '{{ route("registrar.prospectus.create") }}';
    const updateUrl = '{{ url("/registrar/prospectuses") }}';
    const deleteUrl = '{{ url("/registrar/prospectuses") }}';

    // Store current prospectuses data for edit modal
    let currentProspectusesData = @json($prospectuses ?? []);

    // Load curricula by department for the search selects
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

    // Department change -> load curricula for search
    document.getElementById('departmentSelect').addEventListener('change', function() {
        const curriculumSelect = document.getElementById('curriculumSelect');
        loadCurriculaByDepartment(this.value, curriculumSelect);
    });

    // On page load, if department is pre-selected (after search), load its curricula
    document.addEventListener('DOMContentLoaded', function() {
        const departmentSelect = document.getElementById('departmentSelect');
        const curriculumSelect = document.getElementById('curriculumSelect');
        if (departmentSelect.value) {
            const oldCurriculum = '{{ $old_curriculum ?? '' }}';
            loadCurriculaByDepartment(departmentSelect.value, curriculumSelect, oldCurriculum || null);
        }
    });

    // Async function to fetch and render prospectuses
    async function showProspectus() {
        const departmentSelect = document.getElementById('departmentSelect');
        const curriculumSelect = document.getElementById('curriculumSelect');
        const container = document.getElementById('prospectusContainer');
        
        const department = departmentSelect.value;
        const curriculum = curriculumSelect.value;

        if (!department || !curriculum) {
            container.innerHTML = '';
            return;
        }

        // Show loading state
        container.innerHTML = `
            <div class="m-4 flex justify-center items-center py-8">
                <span class="loading loading-spinner loading-lg"></span>
                <span class="ml-2">Loading prospectuses...</span>
            </div>
        `;

        try {
            const response = await fetch(`${prospectusApiUrl}?department=${department}&curriculum=${curriculum}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();

            if (result.success) {
                currentProspectusesData = result.data;
                renderProspectuses(result.data, result.count);
            } else {
                container.innerHTML = `
                    <div class="m-4 alert bg-red-400 text-white">
                        <span>Error loading prospectuses</span>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading prospectuses:', error);
            container.innerHTML = `
                <div class="m-4 alert bg-red-400 text-white">
                    <span>Error loading prospectuses</span>
                </div>
            `;
        }
    }

    // Render prospectuses HTML (grouped by level, then term)
    function renderProspectuses(data, count) {
        const container = document.getElementById('prospectusContainer');

        if (count === 0) {
            container.innerHTML = `
                <div class="m-4 alert bg-blue-400 text-white">
                    <span>No prospectuses found for the selected criteria.</span>
                </div>
            `;
            return;
        }

        let html = '<div class="m-4">';

        data.forEach(levelGroup => {
            const level = levelGroup.level || {};
            const levelCode = level.program?.code || '';
            const levelTitle = levelCode ? `${levelCode} - ${level.description}` : (level.description || '');

            html += `
                <details class="collapse bg-base-100 border-base-300 border mb-3">
                    <summary class="collapse-title font-semibold">${levelTitle}</summary>
                    <div class="collapse-content text-sm">
            `;

            (levelGroup.terms || []).forEach(termGroup => {
                const term = termGroup.term;
                const termCode = term?.code || '';
                const termDescription = term?.description || '';
                const termTitle = (termCode || termDescription)
                    ? `${termDescription}`
                    : 'No Academic Term';

                html += `
                    <details class="collapse bg-base-100 border-base-300 border mb-3">
                        <summary class="collapse-title font-semibold">${termTitle}</summary>
                        <div class="collapse-content text-sm">
                            <table class="table table-zebra">
                                <thead>
                                    <tr>
                                        <th>Curriculum</th>
                                        <th>Code</th>
                                        <th>Description</th>
                                        <th>Lec Hour</th>
                                        <th>Lab Hour</th>
                                        <th>Lec Unit</th>
                                        <th>Lab Unit</th>
                                        <th>Total Unit</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                `;

                (termGroup.prospectuses || []).forEach(prospectus => {
                    html += `
                        <tr data-prospectus-id="${prospectus.id}">
                            <td>${prospectus.curriculum?.curriculum || ''}</td>
                            <td>${prospectus.subject?.code || ''}</td>
                            <td>${prospectus.subject?.description || ''}</td>
                            <td>${prospectus.subject?.lech || ''}</td>
                            <td>${prospectus.subject?.labh || ''}</td>
                            <td>${prospectus.subject?.lecu || ''}</td>
                            <td>${prospectus.subject?.labu || ''}</td>
                            <td>${prospectus.subject?.unit || ''}</td>
                            <td>${prospectus.status}</td>
                            <td>
                                <button class="btn btn-sm btn-ghost edit-btn" onclick="openEditModal(${prospectus.id})">Edit</button>
                                <button type="button" class="btn btn-sm btn-ghost text-red-500 delete-btn" onclick="deleteProspectus(${prospectus.id}, this)">
                                    <span class="loading loading-spinner loading-sm hidden"></span>
                                    Delete
                                </button>
                            </td>
                        </tr>
                    `;
                });

                html += `
                                </tbody>
                            </table>
                        </div>
                    </details>
                `;
            });

            html += `
                    </div>
                </details>
            `;
        });

        html += '</div>';
        container.innerHTML = html;
    }

    // Find prospectus data by ID
    function findProspectusById(id) {
        if (!Array.isArray(currentProspectusesData)) return null;
        if (currentProspectusesData.length === 0) return null;

        // Server-rendered data shape: flat array of prospectus rows
        const first = currentProspectusesData[0];
        if (first && typeof first === 'object' && 'id' in first) {
            return currentProspectusesData.find(p => p.id == id) || null;
        }

        // API data shape (new): level -> terms -> prospectuses
        for (const group of currentProspectusesData) {
            if (Array.isArray(group?.terms)) {
                for (const termGroup of group.terms) {
                    const found = (termGroup.prospectuses || []).find(p => p.id == id);
                    if (found) return found;
                }
                continue;
            }

            // API data shape (legacy): term -> levels -> prospectuses
            if (Array.isArray(group?.levels)) {
                for (const levelGroup of group.levels) {
                    const found = (levelGroup.prospectuses || []).find(p => p.id == id);
                    if (found) return found;
                }
            }
        }

        return null;
    }

    // Open edit modal with prospectus data
    async function openEditModal(id) {
        const prospectus = findProspectusById(id);
        if (!prospectus) {
            showAlert('error', 'Prospectus not found');
            return;
        }

        // Set the hidden ID
        document.getElementById('editProspectusId').value = id;

        // Set form values
        document.getElementById('editModalCurriculum').value = prospectus.curriculum_id;
        document.getElementById('editModalSubject').value = prospectus.subject_id;
        document.getElementById('editModalStatus').value = prospectus.status;
        document.getElementById('editModalTermSelect').value = prospectus.term_id;

        // Load levels for the selected curriculum's department
        const curriculumSelect = document.getElementById('editModalCurriculum');
        const selectedOption = curriculumSelect.options[curriculumSelect.selectedIndex];
        const departmentId = selectedOption.getAttribute('data-department-id');
        const levelSelect = document.getElementById('editModalLevelSelect');
        const termSelect = document.getElementById('editModalTermSelect');
        const updateBtn = document.getElementById('updateProspectusBtn');

        await loadLevelsByDepartment(departmentId, levelSelect, updateBtn, prospectus.level_id);
        await loadTermsByDepartment(departmentId, termSelect, updateBtn, prospectus.term_id);

        // Show the modal
        edit_modal.showModal();
    }

    // Async function to fetch levels by department
    async function loadLevelsByDepartment(departmentId, levelSelectElement, saveBtn = null, selectedLevelId = null) {
        if (!departmentId) {
            levelSelectElement.innerHTML = '<option value="">--Select Curriculum First--</option>';
            levelSelectElement.disabled = false;
            if (saveBtn) saveBtn.disabled = true;
            return;
        }

        // Show loading state and disable the select
        levelSelectElement.innerHTML = '<option value="">Loading...</option>';
        levelSelectElement.disabled = true;
        if (saveBtn) saveBtn.disabled = true;

        try {
            const response = await fetch(`${levelsApiUrl}/${departmentId}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const levels = await response.json();
            
            // Clear and populate the level select
            levelSelectElement.innerHTML = '<option value="">--Select Level--</option>';
            
            levels.forEach(level => {
                const option = document.createElement('option');
                option.value = level.id;
                option.textContent = (level.program ? level.program.code : level.program_id) + " - " + level.description;
                if (selectedLevelId && level.id == selectedLevelId) {
                    option.selected = true;
                }
                levelSelectElement.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading levels:', error);
            levelSelectElement.innerHTML = '<option value="">--Error loading levels--</option>';
        } finally {
            levelSelectElement.disabled = false;
            if (saveBtn) saveBtn.disabled = false;
        }
    }

    // Async function to fetch academic terms by department
    async function loadTermsByDepartment(departmentId, termSelectElement, saveBtn = null, selectedTermId = null) {
        if (!departmentId) {
            termSelectElement.innerHTML = '<option value="">--Select Curriculum First--</option>';
            termSelectElement.disabled = false;
            if (saveBtn) saveBtn.disabled = true;
            return;
        }

        // Show loading state and disable the select
        termSelectElement.innerHTML = '<option value="">Loading...</option>';
        termSelectElement.disabled = true;
        if (saveBtn) saveBtn.disabled = true;

        try {
            const response = await fetch(`${termsApiUrl}/${departmentId}`);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const terms = await response.json();

            if (!Array.isArray(terms) || terms.length === 0) {
                termSelectElement.innerHTML = '<option value="">--No active academic terms--</option>';
                return;
            }

            termSelectElement.innerHTML = '<option value="">--Select Academic Term--</option>';

            terms.forEach(term => {
                const option = document.createElement('option');
                option.value = term.id;

                const code = term.code || '';
                const desc = term.description || '';
                option.textContent = code && desc ? `${code} - ${desc}` : (code || desc);

                if (selectedTermId && term.id == selectedTermId) {
                    option.selected = true;
                }
                termSelectElement.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading terms:', error);
            termSelectElement.innerHTML = '<option value="">--Error loading terms--</option>';
        } finally {
            termSelectElement.disabled = false;
            if (saveBtn) saveBtn.disabled = false;
        }
    }

    // Listen for curriculum changes in the add modal -> load levels by department
    document.getElementById('addModalCurriculum').addEventListener('change', async function() {
        const selectedOption = this.options[this.selectedIndex];
        const departmentId = selectedOption.getAttribute('data-department-id');
        const levelSelect = document.getElementById('addModalLevelSelect');
        const termSelect = document.getElementById('addModalTermSelect');
        const saveBtn = document.getElementById('saveProspectusBtn');
        
        await loadLevelsByDepartment(departmentId, levelSelect, saveBtn);
        await loadTermsByDepartment(departmentId, termSelect, saveBtn);
    });

    // Listen for curriculum changes in the edit modal -> load levels by department
    document.getElementById('editModalCurriculum').addEventListener('change', async function() {
        const selectedOption = this.options[this.selectedIndex];
        const departmentId = selectedOption.getAttribute('data-department-id');
        const levelSelect = document.getElementById('editModalLevelSelect');
        const termSelect = document.getElementById('editModalTermSelect');
        const updateBtn = document.getElementById('updateProspectusBtn');
        
        await loadLevelsByDepartment(departmentId, levelSelect, updateBtn);
        await loadTermsByDepartment(departmentId, termSelect, updateBtn);
    });

    // Create Prospectus - Async
    document.getElementById('createProspectusForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const saveBtn = document.getElementById('saveProspectusBtn');
        const spinner = saveBtn.querySelector('.loading');
        
        // Disable all form elements and show loading
        setFormLoading(this, true);
        saveBtn.disabled = true;
        spinner.classList.remove('hidden');

        const formData = {
            curriculum: document.getElementById('addModalCurriculum').value,
            level: document.getElementById('addModalLevelSelect').value,
            term: document.getElementById('addModalTermSelect').value,
            subject: document.getElementById('addModalSubject').value,
            status: document.getElementById('addModalStatus').value,
        };

        try {
            const response = await fetch(createUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showAlert('success', data.message);
                form_modal.close();
                // Refresh prospectuses without page reload
                await showProspectus();
            } else {
                showAlert('error', data.message || 'Failed to create prospectus');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while creating prospectus');
        } finally {
            setFormLoading(this, false);
            spinner.classList.add('hidden');
        }
    });

    // Update Prospectus - Async
    document.getElementById('editProspectusForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const prospectusId = document.getElementById('editProspectusId').value;
        const updateBtn = document.getElementById('updateProspectusBtn');
        const spinner = updateBtn.querySelector('.loading');
        
        // Disable all form elements and show loading
        setFormLoading(this, true);
        updateBtn.disabled = true;
        spinner.classList.remove('hidden');

        const formData = {
            curriculum: document.getElementById('editModalCurriculum').value,
            level: document.getElementById('editModalLevelSelect').value,
            term: document.getElementById('editModalTermSelect').value,
            subject: document.getElementById('editModalSubject').value,
            status: document.getElementById('editModalStatus').value,
        };

        try {
            const response = await fetch(`${updateUrl}/${prospectusId}/update`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showAlert('success', data.message);
                edit_modal.close();
                // Refresh prospectuses without page reload
                await showProspectus();
            } else {
                showAlert('error', data.message || 'Failed to update prospectus');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while updating prospectus');
        } finally {
            setFormLoading(this, false);
            updateBtn.disabled = false;
            spinner.classList.add('hidden');
        }
    });

    // Delete Prospectus - Async
    async function deleteProspectus(id, btn) {
        if (!confirm('Are you sure you want to delete this prospectus?')) return;
        
        const spinner = btn.querySelector('.loading');
        const row = btn.closest('tr');
        
        // Disable button and show loading
        btn.disabled = true;
        spinner.classList.remove('hidden');

        try {
            const response = await fetch(`${deleteUrl}/${id}/delete`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok && data.success) {
                showAlert('success', data.message);
                // Remove the row from the table with animation
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            } else {
                showAlert('error', data.message || 'Failed to delete prospectus');
                btn.disabled = false;
                spinner.classList.add('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            showAlert('error', 'An error occurred while deleting prospectus');
            btn.disabled = false;
            spinner.classList.add('hidden');
        }
    }

    // Helper function to enable/disable form elements
    function setFormLoading(form, loading) {
        const elements = form.querySelectorAll('select, input, button');
        elements.forEach(el => {
            if (loading) {
                el.disabled = true;
            } else {
                el.disabled = false;
            }
        });
    }

    // Helper function to show alerts
    function showAlert(type, message) {
        // Remove any existing alerts
        const existingAlert = document.querySelector('.dynamic-alert');
        if (existingAlert) existingAlert.remove();

        const alertClass = type === 'success' ? 'bg-green-400' : 'bg-red-400';
        const alertHtml = `
            <div class="dynamic-alert m-4 alert ${alertClass} text-white">
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="btn btn-sm btn-ghost">✕</button>
            </div>
        `;
        
        const container = document.querySelector('.m-4.font-bold');
        container.insertAdjacentHTML('afterend', alertHtml);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            const alert = document.querySelector('.dynamic-alert');
            if (alert) alert.remove();
        }, 5000);
    }
</script>
