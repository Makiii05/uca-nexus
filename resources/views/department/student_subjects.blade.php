<x-department_sidebar>

    @include('partials.notifications')

    <!-- Toast notification container -->
    <div id="toastContainer" class="fixed top-4 right-4 z-50 flex flex-col gap-2"></div>

    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('department.enlistment', ['academic_term_id' => $academicTerm->id]) }}" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Back
        </a>
        <h2 class="font-bold text-4xl">Enlistment</h2>
    </div>
    
    <div class="mb-4 text-lg gap-1">
        <p><strong>Student No.: </strong>{{ $student->student_number}}</p>
        <p><strong>Name: </strong>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</p>
        <p><strong>School Year: </strong>{{ $academicTerm->description }}</p>
        <div class="flex items-center gap-2 mt-2">
            <select name="level" id="level" class="select select-bordered select-sm">
                @if (isset($levels))
                    @foreach ($levels as $level)
                        <option value="{{ $level->id }}" {{ $level->id == $student->level_id ? 'selected' : '' }}>{{ $level->description }}</option>
                    @endforeach
                @endif
            </select>
            <select name="status" id="status" class="select select-bordered select-sm">
                <option value="regular" {{ $student->status == 'regular' ? 'selected' : '' }}>Regular</option>
                <option value="irregular" {{ $student->status == 'irregular' ? 'selected' : '' }}>Irregular</option>
            </select>
            <button id="submitUpdate" class="btn btn-neutral btn-sm">Update</button>
        </div>
        <div id="updateMessage" class="text-sm mt-1"></div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-2 mb-4 text-lg">
        <button class="btn btn-neutral btn-sm" onclick="addSubjectModal.showModal()">Add Subject</button>
        <button class="btn btn-neutral btn-sm" onclick="addSectionModal.showModal()">Add by Section</button>
    </div>

    <!-- Add Subject Modal -->
    <dialog id="addSubjectModal" class="modal">
        <div class="modal-box w-11/12 max-w-3xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add Subject</h3>
            <div class="form-control mb-4">
                <input type="text" id="subjectSearch" placeholder="Search subjects..." class="input input-bordered w-full" oninput="searchSubjects()">
            </div>
            <div class="overflow-x-auto max-h-96">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="subjectOfferingsTable">
                        <tr><td colspan="4" class="text-center">Loading...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Add by Section Modal -->
    <dialog id="addSectionModal" class="modal">
        <div class="modal-box">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add by Section</h3>
            <p class="text-sm text-gray-600 mb-4">Select a section to add all its subjects at once.</p>
            <div class="flex flex-wrap gap-2" id="sectionsList">
                <span class="text-gray-500">Loading sections...</span>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Enlisted Subjects Table -->
    <div class="overflow-x-auto bg-white shadow">
        <table class="table">
            <thead>
                <tr>
                    <th>Section</th>
                    <th>Subject Code</th>
                    <th>Description</th>
                    <th>Final Grade</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="enlistmentTable">
                <tr><td colspan="5" class="text-center">Loading...</td></tr>
            </tbody>
        </table>
    </div>

<script>
    const studentId = {{ $student->id }};
    const academicTermId = {{ $academicTerm->id }};
    const csrfToken = '{{ csrf_token() }}';

    // Toast notification function
    function showToast(message, type = 'info') {
        const container = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        const textColor = type === 'success' ? 'text-green-600' : type === 'error' ? 'text-red-600' : 'text-gray-600';
        toast.className = `bg-white border shadow-lg rounded-lg px-4 py-3 ${textColor} text-sm`;
        toast.textContent = message;
        container.appendChild(toast);
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    // Update status and level
    document.getElementById('submitUpdate').addEventListener('click', function(event) {
        event.preventDefault();
        const level = document.getElementById('level').value;
        const status = document.getElementById('status').value;
        const msgEl = document.getElementById('updateMessage');

        fetch('{{ route("department.enlistment.update", ["id" => $student->id]) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ level, status })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Updated successfully!', 'success');
            } else {
                showToast('Update failed.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error updating.', 'error');
        });
    });

    // Load enlistment data
    function loadEnlistments() {
        fetch(`{{ url('department/api/enlistments') }}/${studentId}/${academicTermId}`)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('enlistmentTable');
                if (data.success && data.data.length > 0) {
                    tbody.innerHTML = data.data.map(item => `
                        <tr>
                            <td>${item.code}</td>
                            <td>${item.subject_code}</td>
                            <td>${item.subject_description}</td>
                            <td>${item.final_grade || '-'}</td>
                            <td>
                                <button class="text-red-600 hover:underline text-sm" onclick="removeEnlistment(${item.id})">remove</button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="5" class="text-center text-gray-500">No subjects enlisted yet.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('enlistmentTable').innerHTML = '<tr><td colspan="5" class="text-center text-error">Error loading data.</td></tr>';
            });
    }

    // Load subject offerings
    function loadSubjectOfferings(search = '') {
        const url = new URL(`{{ url('department/api/subject-offerings') }}/${academicTermId}`, window.location.origin);
        if (search) url.searchParams.append('search', search);

        fetch(url)
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('subjectOfferingsTable');
                if (data.success && data.data.length > 0) {
                    tbody.innerHTML = data.data.map(item => `
                        <tr>
                            <td>${item.code}</td>
                            <td>${item.subject_code}</td>
                            <td>${item.subject_description}</td>
                            <td>
                                <button class="text-blue-600 hover:underline text-sm" onclick="addSubject(${item.id})">add</button>
                            </td>
                        </tr>
                    `).join('');
                } else {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-gray-500">No subject offerings found.</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('subjectOfferingsTable').innerHTML = '<tr><td colspan="4" class="text-center text-error">Error loading data.</td></tr>';
            });
    }

    // Search subjects with debounce
    let searchTimeout;
    function searchSubjects() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            const search = document.getElementById('subjectSearch').value;
            loadSubjectOfferings(search);
        }, 300);
    }

    // Load sections
    function loadSections() {
        fetch(`{{ url('department/api/sections') }}/${academicTermId}`)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('sectionsList');
                if (data.success && data.data.length > 0) {
                    container.innerHTML = data.data.map(section => `
                        <button type="button" class="btn btn-ghost btn-sm border border-gray-300" onclick="addBySection('${section}')">${section}</button>
                    `).join('');
                } else {
                    container.innerHTML = '<span class="text-gray-500">No sections available.</span>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('sectionsList').innerHTML = '<span class="text-error">Error loading sections.</span>';
            });
    }

    // Add single subject
    function addSubject(subjectOfferingId) {
        fetch('{{ route("department.api.enlistment.add") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                student_id: studentId,
                academic_term_id: academicTermId,
                subject_offering_id: subjectOfferingId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadEnlistments();
                showToast('Subject added successfully.', 'success');
            } else {
                showToast(data.message || 'Failed to add subject.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error adding subject.', 'error');
        });
    }

    // Add all subjects by section
    function addBySection(sectionCode) {
        fetch('{{ route("department.api.enlistment.add-section") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                student_id: studentId,
                academic_term_id: academicTermId,
                section_code: sectionCode
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadEnlistments();
                document.getElementById('addSectionModal').close();
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Failed to add subjects.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error adding subjects.', 'error');
        });
    }

    // Remove enlistment
    function removeEnlistment(id) {
        if (!confirm('Are you sure you want to remove this subject?')) return;

        fetch(`{{ url('department/api/enlistment') }}/${id}/remove`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                loadEnlistments();
                showToast('Subject removed.', 'success');
            } else {
                showToast(data.message || 'Failed to remove subject.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error removing subject.', 'error');
        });
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        loadEnlistments();
    });

    // Load data when modals open
    document.getElementById('addSubjectModal').addEventListener('show', loadSubjectOfferings);
    document.getElementById('addSectionModal').addEventListener('show', loadSections);

    // Also load when modal button is clicked (fallback)
    document.querySelector('button[onclick="addSubjectModal.showModal()"]').addEventListener('click', () => loadSubjectOfferings());
    document.querySelector('button[onclick="addSectionModal.showModal()"]').addEventListener('click', () => loadSections());
</script>

</x-department_sidebar>
