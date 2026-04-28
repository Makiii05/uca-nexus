<x-registrar_sidebar>

    <div class="m-4 font-bold text-4xl">
        <h2>Curricula</h2>
    </div>

    @include('partials.notifications')
    <div class="m-4 grid">
        <button class="btn w-auto justify-self-end bg-white shadow" onclick="form_modal.showModal()">Add Curriculum</button>
    </div>
    <dialog id="form_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add Curriculum</h3>
            <form action="{{ route('registrar.curriculum.create') }}" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
                @csrf
                
                <div class="form-control col-span-2">
                    <label class="label">
                        <span class="label-text">Curriculum</span>
                    </label>
                    <input type="text" name="curriculum" class="input input-bordered w-full" placeholder="Enter Curriculum" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Department</span>
                    </label>
                    <select name="department" class="select select-bordered w-full" required>
                        @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->code }} - {{ $department->description }}</option>
                        @endforeach
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
                    <button type="submit" class="btn btn-primary">Save Curriculum</button>
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
                    <th>Curriculum</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th data-no-sort></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($curricula as $curriculum )
                <tr>
                    <td>{{$curriculum->id}}</td>
                    <td>{{$curriculum->curriculum}}</td>
                    <td>{{$curriculum->department->code}}</td>
                    <td>{{$curriculum->status}}</td>
                    <td>
                        <button class="text-green-600 hover:underline" onclick="editCurriculum({{ $curriculum->id }}, '{{ $curriculum->curriculum }}', {{ $curriculum->department_id }}, '{{ $curriculum->status }}')" >edit</button>
                        <form action="{{ route('registrar.curriculum.delete', $curriculum->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this curriculum?')">
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
        function editCurriculum(id, curriculum, departmentId, status) {
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
                    <h3 class="text-lg font-bold mb-4">Edit Curriculum</h3>
                    <form action="/registrar/curricula/${id}/update" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
                        @csrf
                        
                        <div class="form-control col-span-2">
                            <label class="label">
                                <span class="label-text">Curriculum</span>
                            </label>
                            <input type="text" name="curriculum" class="input input-bordered w-full" value="${curriculum}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Department</span>
                            </label>
                            <select name="department" class="select select-bordered w-full" required>
                                ${deptOptions}
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
                            <button type="submit" class="btn btn-primary">Update Curriculum</button>
                        </div>
                    </form>
                </div>
            `;
            form_modal.showModal();
        }
    </script>
</x-registrar_sidebar>