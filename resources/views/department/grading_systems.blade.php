<x-department_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl flex-1">Grading Systems</h2>
        <button type="button" class="btn btn-neutral btn-sm" onclick="grading_system_create_modal.showModal()" @if($components->isEmpty()) disabled @endif>
            Add Grading System
        </button>
    </div>

    @if ($components->isEmpty())
        <div class="alert bg-blue-400 text-white mb-4">
            <span>Please add grading components first before creating a grading system.</span>
        </div>
    @endif

    <dialog id="grading_system_create_modal" class="modal">
        <div class="modal-box max-w-3xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">x</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add Grading System</h3>
            <form action="{{ route('department.grading_systems.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <input type="text" name="description" class="input input-bordered w-full" placeholder="e.g. Midterm Grading System" required>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Components</span></label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 border rounded p-3 max-h-64 overflow-y-auto">
                        @foreach ($components as $component)
                            <label class="label cursor-pointer justify-start gap-3">
                                <input type="checkbox" name="component_ids[]" value="{{ $component->id }}" class="checkbox checkbox-sm add-component-checkbox" data-percentage="{{ (float) $component->percentage }}" onchange="updateCreateTotal()">
                                <span class="label-text">{{ $component->code }} - {{ $component->description }} ({{ number_format((float) $component->percentage, 2) }}%)</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Total Percentage</span></label>
                    <input type="number" id="createTotalPercentage" class="input input-bordered w-full" readonly value="0.00">
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="grading_system_create_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <dialog id="grading_system_edit_modal" class="modal">
        <div class="modal-box max-w-3xl" id="gradingSystemEditModalBody"></div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <div data-table-wrapper>
        <div class="overflow-x-auto bg-white shadow">
            <table class="table" data-sortable-table>
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Components</th>
                        <th>Total Percentage</th>
                        <th data-no-sort>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($gradingSystems as $gradingSystem)
                        <tr>
                            <td>{{ $gradingSystem->description }}</td>
                            <td>
                                @if ($gradingSystem->components->isNotEmpty())
                                    {{ $gradingSystem->components->map(fn($c) => $c->code . ' (' . number_format((float) $c->percentage, 2) . '%)')->implode(', ') }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ number_format((float) $gradingSystem->total_percentage, 2) }}%</td>
                            <td>
                                <button
                                    type="button"
                                    class="text-green-600 hover:underline"
                                    onclick='openEditGradingSystemModal(
                                        {{ $gradingSystem->id }},
                                        @json($gradingSystem->description),
                                        @json($gradingSystem->components->pluck("id")->values()->all())
                                    )'
                                >
                                    edit
                                </button>
                                <form action="{{ route('department.grading_systems.delete', $gradingSystem->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this grading system?')">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:underline">delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-8">No grading systems yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.table-sort-search')
    @php
        $componentData = $components->map(fn($c) => [
            'id' => $c->id,
            'code' => $c->code,
            'description' => $c->description,
            'percentage' => (float) $c->percentage,
        ])->values();
    @endphp
    <script>
        const availableComponents = @json($componentData);


        function computeTotalFromSelector(selector) {
            let total = 0;
            document.querySelectorAll(selector).forEach((checkbox) => {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.dataset.percentage || '0');
                }
            });
            return total;
        }

        function updateCreateTotal() {
            const total = computeTotalFromSelector('.add-component-checkbox');
            document.getElementById('createTotalPercentage').value = total.toFixed(2);
        }

        function updateEditTotal() {
            const total = computeTotalFromSelector('.edit-component-checkbox');
            document.getElementById('editTotalPercentage').value = total.toFixed(2);
        }

        function openEditGradingSystemModal(id, description, selectedComponentIds) {
            const selectedSet = new Set((selectedComponentIds || []).map((value) => Number(value)));
            const optionsHtml = availableComponents.map((component) => {
                const checked = selectedSet.has(Number(component.id)) ? 'checked' : '';
                return `
                    <label class="label cursor-pointer justify-start gap-3">
                        <input type="checkbox" name="component_ids[]" value="${component.id}" class="checkbox checkbox-sm edit-component-checkbox" data-percentage="${component.percentage}" ${checked} onchange="updateEditTotal()">
                        <span class="label-text">${component.code} - ${component.description} (${Number(component.percentage).toFixed(2)}%)</span>
                    </label>
                `;
            }).join('');

            const container = document.getElementById('gradingSystemEditModalBody');
            container.innerHTML = `
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">x</button>
                </form>
                <h3 class="text-lg font-bold mb-4">Edit Grading System</h3>
                <form action="/department/grading-systems/${id}/update" method="POST" class="space-y-4">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Description</span></label>
                        <input type="text" name="description" class="input input-bordered w-full" value="${description}" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Components</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 border rounded p-3 max-h-64 overflow-y-auto">
                            ${optionsHtml}
                        </div>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Total Percentage</span></label>
                        <input type="number" id="editTotalPercentage" class="input input-bordered w-full" readonly value="0.00">
                    </div>
                    <div class="modal-action">
                        <button type="button" class="btn btn-ghost" onclick="grading_system_edit_modal.close()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            `;

            document.getElementById('grading_system_edit_modal').showModal();
            updateEditTotal();
        }
    </script>

</x-department_sidebar>
