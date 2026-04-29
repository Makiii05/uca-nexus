<x-registrar_sidebar>

    <div class="m-4 font-bold text-4xl">
        <h2>Academic Term</h2>
    </div>

    @include('partials.notifications')
    <div class="m-4 grid">
        <button class="btn w-auto justify-self-end bg-white shadow" onclick="form_modal.showModal()">Add Academic Term</button>
    </div>
    <dialog id="form_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add Academic Term</h3>
            <form action="{{ route('registrar.academic_term.create') }}" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
                @csrf
                
                <div class="form-control col-span-2">
                    <label class="label">
                        <span class="label-text">Code</span>
                    </label>
                    <input type="text" name="code" class="input input-bordered w-full" placeholder="Enter academic term code" required>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <input type="text" name="description" class="input input-bordered w-full" placeholder="Enter academic term description" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Type</span>
                    </label>
                    <select name="type" class="select select-bordered w-full" required>
                        <option value="semester">Semester</option>
                        <option value="full year">Full Year</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Department</span>
                    </label>
                    <select name="department" class="select select-bordered w-full" required>
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->description }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Academic Year</span>
                    </label>
                    <select name="academic_year_id" class="select select-bordered w-full" required>
                        <option value="">Select Academic Year</option>
                        @foreach ($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}">{{ $academicYear->label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Start Date</span>
                    </label>
                    <input type="date" name="start_date" class="input input-bordered w-full" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">End Date</span>
                    </label>
                    <input type="date" name="end_date" class="input input-bordered w-full" required>
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
                    <button type="submit" class="btn btn-primary">Save Academic Term</button>
                </div>
            </form>
        </div>
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
                    <th>Type</th>
                    <th>Department</th>
                    <th>Academic Year</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th data-no-sort></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($academicTerms as $academicTerm)
                <tr>
                    <td>{{$academicTerm->id}}</td>
                    <td>{{$academicTerm->code}}</td>
                    <td>{{$academicTerm->description ?? 'N/A'}}</td>
                    <td>{{ ucfirst($academicTerm->type) }}</td>
                    <td>{{$academicTerm->department->code ?? 'N/A'}}</td>
                    <td>{{$academicTerm->academicYear?->label ?? 'N/A'}}</td>
                    <td>{{$academicTerm->start_date}}</td>
                    <td>{{$academicTerm->end_date}}</td>
                    <td>{{$academicTerm->status}}</td>
                    <td>
                        <button class="text-green-600 hover:underline" onclick="editAcademicTerm({{ $academicTerm->id }}, '{{ $academicTerm->code }}', '{{ $academicTerm->description }}', '{{ $academicTerm->type }}', {{ $academicTerm->department_id }}, {{ $academicTerm->academic_year_id }}, '{{ $academicTerm->start_date }}', '{{ $academicTerm->end_date }}', '{{ $academicTerm->status }}')">edit</button>
                        <form action="{{ route('registrar.academic_term.delete', $academicTerm->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this academic term?')">
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
        function editAcademicTerm(id, code, description, type, departmentId, academicYearId, startDate, endDate, status) {
            document.getElementById('form_modal').innerHTML = `
                <div class="modal-box w-11/12 max-w-5xl">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <h3 class="text-lg font-bold mb-4">Edit Academic Term</h3>
                    <form action="/registrar/academic-terms/${id}/update" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
                        @csrf
                        
                        <div class="form-control col-span-2">
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

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Type</span>
                            </label>
                            <select name="type" class="select select-bordered w-full" required>
                                <option value="semester" ${type === 'semester' ? 'selected' : ''}>Semester</option>
                                <option value="full year" ${type === 'full year' ? 'selected' : ''}>Full Year</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Department</span>
                            </label>
                            <select name="department" class="select select-bordered w-full" required>
                                @foreach ($departments as $department)
                                <option value="{{ $department->id }}" ${departmentId === {{ $department->id }} ? 'selected' : ''}>{{ $department->description }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Academic Year</span>
                            </label>
                            <select name="academic_year_id" class="select select-bordered w-full" required>
                                <option value="">Select Academic Year</option>
                                @foreach ($academicYears as $academicYear)
                                <option value="{{ $academicYear->id }}" ${academicYearId === {{ $academicYear->id }} ? 'selected' : ''}>{{ $academicYear->label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Start Date</span>
                            </label>
                            <input type="date" name="start_date" class="input input-bordered w-full" value="${startDate}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">End Date</span>
                            </label>
                            <input type="date" name="end_date" class="input input-bordered w-full" value="${endDate}" required>
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
                            <button type="submit" class="btn btn-primary">Update Academic Term</button>
                        </div>
                    </form>
                </div>
            `;
            form_modal.showModal();
        }
    </script>
</x-registrar_sidebar>
