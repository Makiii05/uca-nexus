<x-registrar_sidebar>

    <div class="m-4 font-bold text-4xl">
        <h2>Subject</h2>
    </div>

    @include('partials.notifications')
    <div class="m-4 grid">
        <button class="btn w-auto justify-self-end bg-white shadow" onclick="form_modal.showModal()">Add Subject</button>
    </div>
    <dialog id="form_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add Subject</h3>
            <form action="{{ route('registrar.subject.create') }}" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
                @csrf
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Code</span>
                    </label>
                    <input type="text" name="code" class="input input-bordered w-full" placeholder="Enter subject code" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <input type="text" name="description" class="input input-bordered w-full" placeholder="Enter subject description" required>
                </div>

                <div class="form-control col-span-2">
                    <label class="label">
                        <span class="label-text">Unit</span>
                    </label>
                    <input type="number" step="1" name="unit" class="input input-bordered w-full" placeholder="Enter subject unit" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Lecture Hour</span>
                    </label>
                    <input type="number" step="1" name="lech" class="input input-bordered w-full" placeholder="Enter lecture hour" required>
                </div>


                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Lecture Unit</span>
                    </label>
                    <input type="number" step="1" name="lecu" class="input input-bordered w-full" placeholder="Enter lecture unit" required>
                </div>


                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Laboratory Hour</span>
                    </label>
                    <input type="number" step="1" name="labh" class="input input-bordered w-full" placeholder="Enter laboratory hour" required>
                </div>


                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Laboratory Unit</span>
                    </label>
                    <input type="number" step="1" name="labu" class="input input-bordered w-full" placeholder="Enter laboratory unit" required>
                </div>

                <div class="form-control col-span-2">
                    <label class="label">
                        <span class="label-text">Type</span>
                    </label>
                    <select name="type" class="select select-bordered w-full">
                        <option value="lec">Lecture</option>
                        <option value="lab">Laboratory</option>
                        <option value="lec lab">Lecture and Laboratory</option>
                    </select>
                </div>

                <div class="form-control col-span-2">
                    <label class="label">
                        <span class="label-text">Education Level</span>
                    </label>
                    <select name="education_level" class="select select-bordered w-full">
                        <option value="">Select education level</option>
                        <option value="K12">K12</option>
                        <option value="college" selected>College</option>
                    </select>
                </div>
                
                <div class="form-control col-span-2">
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select name="status" class="select select-bordered w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <div class="modal-action col-span-2">
                    <button type="button" class="btn" onclick="form_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Subject</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Subject Fees Modal -->
    <dialog id="fees_modal" class="modal">
        <div class="modal-box w-11/12 max-w-3xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Subject Fees — <span id="feesSubjectName"></span></h3>

            <!-- Top Section: Add Fee -->
            <div class="border rounded-lg p-4 mb-4 bg-base-200">
                <h4 class="font-semibold mb-2">Assign Fee</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mb-2">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Program</span></label>
                        <select id="feeProgram" class="select select-bordered select-sm w-full" onchange="loadFeeAcademicTerms()">
                            <option value="">Select Program</option>
                            @foreach($programs as $program)
                                <option value="{{ $program->id }}">{{ $program->code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Academic Term</span></label>
                        <select id="feeAcademicTerm" class="select select-bordered select-sm w-full" onchange="loadFeesList()" disabled>
                            <option value="">Select Term</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Fee</span></label>
                        <select id="feeSelect" class="select select-bordered select-sm w-full" disabled>
                            <option value="">Select Fee</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button id="addFeeBtn" class="btn btn-primary btn-sm" onclick="addSubjectFee()" disabled>Add Fee</button>
                </div>
            </div>

            <!-- Bottom Section: Assigned Fees Table -->
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Fee Name</th>
                            <th>Category</th>
                            <th>Amount</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="subjectFeesTable">
                        <tr><td colspan="4" class="text-center text-gray-500">Select a subject to view fees.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!--TABLE-->
    <div data-table-wrapper>
    <div class="overflow-x-auto bg-white shadow">
        <table class="table" data-sortable-table>
            <!-- head -->
            <thead>
                <tr>
                    <th></th>
                    <th>Code</th>
                    <th>Description</th>
                    <th>Unit</th>
                    <th>Lec H</th>
                    <th>Lec U</th>
                    <th>Lab H</th>
                    <th>Lab U</th>
                    <th>Type</th>
                    <th>Education Level</th>
                    <th>Status</th>
                    <th data-no-sort></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subjects as $subject)
                <tr>
                    <td>{{$subject->id}}</td>
                    <td>{{$subject->code}}</td>
                    <td>{{$subject->description}}</td>
                    <td>{{$subject->unit}}</td>
                    <td>{{$subject->lech}}</td>
                    <td>{{$subject->lecu}}</td>
                    <td>{{$subject->labh}}</td>
                    <td>{{$subject->labu}}</td>
                    <td>{{$subject->type}}</td>
                    <td>{{$subject->education_level ?? 'N/A'}}</td>
                    <td>{{$subject->status}}</td>
                    <td>
                        <button class="text-green-600 hover:underline" onclick="editSubject({{ $subject->id }}, '{{ $subject->code }}', '{{ $subject->description }}', {{ $subject->unit }}, {{ $subject->lech }}, {{ $subject->lecu }}, {{ $subject->labh }}, {{ $subject->labu }}, '{{ $subject->type }}', '{{ $subject->education_level }}', '{{ $subject->status }}')">edit</button>
                        <button class="text-blue-600 hover:underline" onclick="openFeesModal({{ $subject->id }}, '{{ $subject->code }}')">fees</button>
                        <form action="{{ route('registrar.subject.delete', $subject->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this subject?')">
                            @csrf
                            <button type="submit" class="text-red-600 hover:underline">delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    </div>

    @include('partials.table-sort-search')
    @include('partials.delete-confirm-modal')
    
    <script>
        const csrfToken = '{{ csrf_token() }}';
        let currentSubjectId = null;

        // Edit Subject function (without weight_category)
        function editSubject(id, code, description, unit, lech, lecu, labh, labu, type, educationLevel, status) {
            document.getElementById('form_modal').innerHTML = `
                <div class="modal-box w-11/12 max-w-5xl">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <h3 class="text-lg font-bold mb-4">Edit Subject</h3>
                    <form action="/registrar/subjects/${id}/update" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
                        @csrf
                        
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Code</span>
                            </label>
                            <input type="text" name="code" class="input input-bordered w-full" value="${code}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Description</span>
                            </label>
                            <input type="text" name="description" class="input input-bordered w-full" value="${description}" required>
                        </div>

                        <div class="form-control col-span-2">
                            <label class="label">
                                <span class="label-text">Unit</span>
                            </label>
                            <input type="number" step="1" name="unit" class="input input-bordered w-full" value="${unit}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Lecture Hour</span>
                            </label>
                            <input type="number" step="1" name="lech" class="input input-bordered w-full" value="${lech}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Lecture Unit</span>
                            </label>
                            <input type="number" step="1" name="lecu" class="input input-bordered w-full" value="${lecu}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Laboratory Hour</span>
                            </label>
                            <input type="number" step="1" name="labh" class="input input-bordered w-full" value="${labh}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Laboratory Unit</span>
                            </label>
                            <input type="number" step="1" name="labu" class="input input-bordered w-full" value="${labu}" required>
                        </div>

                        <div class="form-control col-span-2">
                            <label class="label">
                                <span class="label-text">Type</span>
                            </label>
                            <select name="type" class="select select-bordered w-full">
                                <option value="lec" ${type === 'lec' ? 'selected' : ''}>Lecture</option>
                                <option value="lab" ${type === 'lab' ? 'selected' : ''}>Laboratory</option>
                                <option value="lec lab" ${type === 'lec lab' ? 'selected' : ''}>Lecture and Laboratory</option>
                            </select>
                        </div>

                        <div class="form-control col-span-2">
                            <label class="label">
                                <span class="label-text">Education Level</span>
                            </label>
                            <select name="education_level" class="select select-bordered w-full">
                                <option value="" ${!educationLevel ? 'selected' : ''}>Select education level</option>
                                <option value="K12" ${educationLevel === 'K12' ? 'selected' : ''}>K12</option>
                                <option value="college" ${educationLevel === 'college' ? 'selected' : ''}>College</option>
                            </select>
                        </div>
                        
                        <div class="form-control col-span-2">
                            <label class="label">
                                <span class="label-text">Status</span>
                            </label>
                            <select name="status" class="select select-bordered w-full">
                                <option value="active" ${status === 'active' ? 'selected' : ''}>Active</option>
                                <option value="inactive" ${status === 'inactive' ? 'selected' : ''}>Inactive</option>
                            </select>
                        </div>

                        <div class="modal-action col-span-2">
                            <button type="button" class="btn" onclick="form_modal.close()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Subject</button>
                        </div>
                    </form>
                </div>
            `;
            form_modal.showModal();
        }

        // ========== Subject Fees Modal Logic ==========

        function openFeesModal(subjectId, subjectCode) {
            currentSubjectId = subjectId;
            document.getElementById('feesSubjectName').textContent = subjectCode;
            // Reset dropdowns
            document.getElementById('feeProgram').value = '';
            document.getElementById('feeAcademicTerm').innerHTML = '<option value="">Select Term</option>';
            document.getElementById('feeAcademicTerm').disabled = true;
            document.getElementById('feeSelect').innerHTML = '<option value="">Select Fee</option>';
            document.getElementById('feeSelect').disabled = true;
            document.getElementById('addFeeBtn').disabled = true;
            // Load existing fees for this subject
            loadSubjectFees();
            fees_modal.showModal();
        }

        function loadSubjectFees() {
            fetch(`{{ url('registrar/api/subjects') }}/${currentSubjectId}/fees`)
                .then(r => r.json())
                .then(data => {
                    const tbody = document.getElementById('subjectFeesTable');
                    if (data.success && data.data.length > 0) {
                        tbody.innerHTML = data.data.map(item => `
                            <tr id="sf-row-${item.id}">
                                <td>${item.description}</td>
                                <td>${item.group}</td>
                                <td>${parseFloat(item.amount).toFixed(2)}</td>
                                <td>
                                    <button class="text-red-600 hover:underline text-sm" onclick="removeSubjectFee(${item.id})">delete</button>
                                </td>
                            </tr>
                        `).join('');
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-gray-500">No fees assigned.</td></tr>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('subjectFeesTable').innerHTML = '<tr><td colspan="4" class="text-center text-error">Error loading fees.</td></tr>';
                });
        }

        function loadFeeAcademicTerms() {
            const programId = document.getElementById('feeProgram').value;
            const termSelect = document.getElementById('feeAcademicTerm');
            const feeSelect = document.getElementById('feeSelect');

            termSelect.innerHTML = '<option value="">Select Term</option>';
            termSelect.disabled = true;
            feeSelect.innerHTML = '<option value="">Select Fee</option>';
            feeSelect.disabled = true;
            document.getElementById('addFeeBtn').disabled = true;

            if (!programId) return;

            fetch(`{{ route('registrar.api.subject.academic-terms') }}?program_id=${programId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.length > 0) {
                        data.forEach(term => {
                            const opt = document.createElement('option');
                            opt.value = term.id;
                            opt.textContent = term.description;
                            termSelect.appendChild(opt);
                        });
                        termSelect.disabled = false;
                    }
                })
                .catch(err => console.error(err));
        }

        function loadFeesList() {
            const programId = document.getElementById('feeProgram').value;
            const termId = document.getElementById('feeAcademicTerm').value;
            const feeSelect = document.getElementById('feeSelect');

            feeSelect.innerHTML = '<option value="">Select Fee</option>';
            feeSelect.disabled = true;
            document.getElementById('addFeeBtn').disabled = true;

            if (!termId || !programId) return;

            fetch(`{{ route('registrar.api.subject.fees-by-term') }}?academic_term_id=${termId}&program_id=${programId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.data.length > 0) {
                        data.data.forEach(fee => {
                            const opt = document.createElement('option');
                            opt.value = fee.id;
                            opt.textContent = `${fee.description} (${fee.group}) — ₱${parseFloat(fee.amount).toFixed(2)}`;
                            feeSelect.appendChild(opt);
                        });
                        feeSelect.disabled = false;
                        feeSelect.addEventListener('change', function() {
                            document.getElementById('addFeeBtn').disabled = !this.value;
                        });
                    }
                })
                .catch(err => console.error(err));
        }

        function addSubjectFee() {
            const feeId = document.getElementById('feeSelect').value;
            if (!feeId || !currentSubjectId) return;

            fetch(`{{ url('registrar/api/subjects') }}/${currentSubjectId}/fees`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ fee_id: feeId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadSubjectFees();
                    // Remove the selected fee from dropdown
                    const feeSelect = document.getElementById('feeSelect');
                    const selectedOption = feeSelect.querySelector(`option[value="${feeId}"]`);
                    if (selectedOption) selectedOption.remove();
                    feeSelect.value = '';
                    document.getElementById('addFeeBtn').disabled = true;
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Failed to add fee.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Error adding fee.', 'error');
            });
        }

        function removeSubjectFee(subjectFeeId) {
            fetch(`{{ url('registrar/api/subjects/fees') }}/${subjectFeeId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const row = document.getElementById(`sf-row-${subjectFeeId}`);
                    if (row) row.remove();
                    // Check if table is now empty
                    const tbody = document.getElementById('subjectFeesTable');
                    if (tbody.children.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="4" class="text-center text-gray-500">No fees assigned.</td></tr>';
                    }
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message || 'Failed to remove fee.', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Error removing fee.', 'error');
            });
        }
    </script>
</x-registrar_sidebar>