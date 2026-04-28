<x-registrar_sidebar>

    <div class="m-4 font-bold text-4xl">
        <h2>Level</h2>
    </div>

    @include('partials.notifications')
    <div class="m-4 grid">
        <button class="btn w-auto justify-self-end bg-white shadow" onclick="form_modal.showModal()">Add Level</button>
    </div>
    <dialog id="form_modal" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add Level</h3>
            <form action="{{ route('registrar.level.create') }}" method="POST" class="space-y-4 space-x-4 grid grid-cols-2">
                @csrf
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Code</span>
                    </label>
                    <input type="text" name="code" class="input input-bordered w-full" placeholder="Enter level code" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <input type="text" name="description" class="input input-bordered w-full" placeholder="Enter level description" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Program</span>
                    </label>
                    <select name="program_id" class="select select-bordered w-full" required>
                        <option value="" disabled selected>Select a program</option>
                        @foreach ($programs as $program)
                            <option value="{{ $program->id }}">{{ $program->code }} - {{ $program->description }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Order</span>
                    </label>
                    <input type="number" name="order" class="input input-bordered w-full" placeholder="Enter order (e.g., 1, 2, 3)" min="0" value="0" required>
                </div>

                <div class="modal-action col-span-2">
                    <button type="button" class="btn" onclick="form_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Level</button>
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
                    <th>Program</th>
                    <th>Order</th>
                    <th data-no-sort></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($levels as $level)
                <tr>
                    <td>{{ $level->id }}</td>
                    <td>{{ $level->code }}</td>
                    <td>{{ $level->description }}</td>
                    <td>{{ $level->program->code ?? 'N/A' }} - {{ $level->program->description ?? '' }}</td>
                    <td>{{ $level->order }}</td>
                    <td>
                        <button class="text-green-600 hover:underline" onclick="editLevel({{ $level->id }}, '{{ $level->code }}', '{{ $level->description }}', {{ $level->program_id }}, {{ $level->order }})">edit</button>
                        <form action="{{ route('registrar.level.delete', $level->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this level?')">
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
        function editLevel(id, code, description, programId, order) {
            const programOptions = @json($programs);
            let programOptionsHtml = '<option value="" disabled>Select a program</option>';
            programOptions.forEach(program => {
                const selected = program.id === programId ? 'selected' : '';
                programOptionsHtml += `<option value="${program.id}" ${selected}>${program.code} - ${program.description}</option>`;
            });

            document.getElementById('form_modal').innerHTML = `
                <div class="modal-box w-11/12 max-w-5xl">
                    <form method="dialog">
                        <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                    </form>
                    <h3 class="text-lg font-bold mb-4">Edit Level</h3>
                    <form action="/registrar/levels/${id}/update" method="POST" class="space-y-4">
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
                                <span class="label-text">Program</span>
                            </label>
                            <select name="program_id" class="select select-bordered w-full" required>
                                ${programOptionsHtml}
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Order</span>
                            </label>
                            <input type="number" name="order" class="input input-bordered w-full" value="${order}" min="0" required>
                        </div>

                        <div class="modal-action">
                            <button type="button" class="btn" onclick="form_modal.close()">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Level</button>
                        </div>
                    </form>
                </div>
            `;
            form_modal.showModal();
        }
    </script>
</x-registrar_sidebar>
