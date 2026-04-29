<x-registrar_sidebar>

    <div class="m-4 font-bold text-4xl">
        <h2>Program</h2>
    </div>

    @include('partials.notifications')
    <div class="m-4 grid">
        <button class="btn w-auto justify-self-end bg-white shadow" onclick="form_modal.showModal()">Add Program</button>
    </div>
    <dialog id="form_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add Program</h3>
            <form action="{{ route('registrar.program.create') }}" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
                @csrf
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Code</span>
                    </label>
                    <input type="text" name="code" class="input input-bordered w-full" placeholder="Enter program code" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <input type="text" name="description" class="input input-bordered w-full" placeholder="Enter program description" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Department</span>
                    </label>
                    <select name="department" class="select select-bordered w-full">
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->code }} - {{ $department->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Enrollment Type</span>
                    </label>
                    <select name="enrollment_type" class="select select-bordered w-full">
                        <option value="yearly">Yearly</option>
                        <option value="semester">Semester</option>
                    </select>
                </div>

                <div class="form-control">
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
                    <button type="submit" class="btn btn-primary">Save Program</button>
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
                    <th>Department</th>
                    <th>Enrollment Type</th>
                    <th>Status</th>
                    <th data-no-sort></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($programs as $program)
                <tr>
                    <td>{{$program->id}}</td>
                    <td>{{$program->code}}</td>
                    <td>{{$program->description}}</td>
                    <td>{{$program->department->code}}</td>
                    <td>{{$program->enrollment_type}}</td>
                    <td>{{$program->status}}</td>
                    <td>
                        <button class="text-green-600 hover:underline" onclick="editProgram({{ $program->id }}, '{{ $program->code }}', '{{ $program->description }}', {{ $program->department_id }}, '{{ $program->enrollment_type }}', '{{ $program->status }}')">edit</button>
                        <form action="{{ route('registrar.program.delete', $program->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this program?')">
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
        function editProgram(id, code, description, departmentId, enrollmentType, status) {
            let deptOptions = `
                @foreach ($departments as $department)
                <option value="{{ $department->id }}" ${departmentId === {{ $department->id }} ? 'selected' : ''}>{{ $department->code }} - {{ $department->description }}</option>
                @endforeach
            `;
            
            document.getElementById('form_modal').innerHTML = `
                <div class="modal-box w-11/12 max-w-5xl">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <h3 class="text-lg font-bold mb-4">Edit Program</h3>
                    <form action="/registrar/programs/${id}/update" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
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

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Department</span>
                            </label>
                            <select name="department" class="select select-bordered w-full">
                                ${deptOptions}
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Enrollment Type</span>
                            </label>
                            <select name="enrollment_type" class="select select-bordered w-full">
                                <option value="yearly" ${enrollmentType === 'yearly' ? 'selected' : ''}>Yearly</option>
                                <option value="semester" ${enrollmentType === 'semester' ? 'selected' : ''}>Semester</option>
                            </select>
                        </div>

                        <div class="form-control">
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
                            <button type="submit" class="btn btn-primary">Update Program</button>
                        </div>
                    </form>
                </div>
            `;
            form_modal.showModal();
        }
    </script>
</x-registrar_sidebar>