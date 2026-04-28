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
                    <input type="number" step="0.01" name="unit" class="input input-bordered w-full" placeholder="Enter subject unit" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Lecture Hour</span>
                    </label>
                    <input type="number" step="0.01" name="lech" class="input input-bordered w-full" placeholder="Enter lecture hour" required>
                </div>


                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Lecture Unit</span>
                    </label>
                    <input type="number" step="0.01" name="lecu" class="input input-bordered w-full" placeholder="Enter lecture unit" required>
                </div>


                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Laboratory Hour</span>
                    </label>
                    <input type="number" step="0.01" name="labh" class="input input-bordered w-full" placeholder="Enter laboratory hour" required>
                </div>


                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Laboratory Unit</span>
                    </label>
                    <input type="number" step="0.01" name="labu" class="input input-bordered w-full" placeholder="Enter laboratory unit" required>
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
                    <td>{{$subject->status}}</td>
                    <td>
                        <button class="text-green-600 hover:underline" onclick="editSubject({{ $subject->id }}, '{{ $subject->code }}', '{{ $subject->description }}', {{ $subject->unit }}, {{ $subject->lech }}, {{ $subject->lecu }}, {{ $subject->labh }}, {{ $subject->labu }}, '{{ $subject->type }}', '{{ $subject->status }}')">edit</button>
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
        function editSubject(id, code, description, unit, lech, lecu, labh, labu, type, status) {
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
                            <input type="number" step="0.01" name="unit" class="input input-bordered w-full" value="${unit}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Lecture Hour</span>
                            </label>
                            <input type="number" step="0.01" name="lech" class="input input-bordered w-full" value="${lech}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Lecture Unit</span>
                            </label>
                            <input type="number" step="0.01" name="lecu" class="input input-bordered w-full" value="${lecu}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Laboratory Hour</span>
                            </label>
                            <input type="number" step="0.01" name="labh" class="input input-bordered w-full" value="${labh}" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Laboratory Unit</span>
                            </label>
                            <input type="number" step="0.01" name="labu" class="input input-bordered w-full" value="${labu}" required>
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
    </script>
</x-registrar_sidebar>