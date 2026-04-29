<x-registrar_sidebar>

    <div class="m-4 font-bold text-4xl">
        <h2>Academic Year</h2>
    </div>

    @include('partials.notifications')
    <div class="m-4 grid">
        <button class="btn w-auto justify-self-end bg-white shadow" onclick="form_modal.showModal()">Add Academic Year</button>
    </div>
    <dialog id="form_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add Academic Year</h3>
            <form action="{{ route('registrar.academic_year.create') }}" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
                @csrf

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Code</span>
                    </label>
                    <input type="text" name="code" class="input input-bordered w-full" placeholder="Enter academic year code" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <input type="text" name="description" class="input input-bordered w-full" placeholder="Enter academic year description" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Start Year</span>
                    </label>
                    <input type="text" name="start_year" class="input input-bordered w-full" placeholder="Enter start year" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">End Year</span>
                    </label>
                    <input type="text" name="end_year" class="input input-bordered w-full" placeholder="Enter end year" required>
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
                    <button type="submit" class="btn btn-primary">Save Academic Year</button>
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
                    <th>Start Year</th>
                    <th>End Year</th>
                    <th>Status</th>
                    <th data-no-sort></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($academicYears as $academicYear)
                <tr>
                    <td>{{$academicYear->id}}</td>
                    <td>{{$academicYear->code}}</td>
                    <td>{{$academicYear->description}}</td>
                    <td>{{$academicYear->start_year}}</td>
                    <td>{{$academicYear->end_year}}</td>
                    <td>{{$academicYear->status}}</td>
                    <td>
                        <button class="text-green-600 hover:underline" onclick="editAcademicYear({{ $academicYear->id }}, '{{ $academicYear->code }}', '{{ $academicYear->description }}', '{{ $academicYear->start_year }}', '{{ $academicYear->end_year }}', '{{ $academicYear->status }}')">edit</button>
                        <form action="{{ route('registrar.academic_year.delete', $academicYear->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this academic year?')">
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
        function editAcademicYear(id, code, description, startYear, endYear, status) {
            document.getElementById('form_modal').innerHTML = `
                <div class="modal-box w-11/12 max-w-5xl">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <h3 class="text-lg font-bold mb-4">Edit Academic Year</h3>
                    <form action="/registrar/academic-years/${id}/update" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
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
                                <span class="label-text">Start Year</span>
                            </label>
                            <input type="text" name="start_year" class="input input-bordered w-full" value="${startYear}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">End Year</span>
                            </label>
                            <input type="text" name="end_year" class="input input-bordered w-full" value="${endYear}" required>
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
                            <button type="submit" class="btn btn-primary">Update Academic Year</button>
                        </div>
                    </form>
                </div>
            `;
            form_modal.showModal();
        }
    </script>
</x-registrar_sidebar>
