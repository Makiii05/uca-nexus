<x-department_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl flex-1">Grading Components</h2>
        <button type="button" class="btn btn-neutral btn-sm" onclick="component_create_modal.showModal()">Add Component</button>
    </div>

    <dialog id="component_create_modal" class="modal">
        <div class="modal-box max-w-2xl">
            <form method="dialog">
                <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">x</button>
            </form>
            <h3 class="text-lg font-bold mb-4">Add Grading Component</h3>
            <form action="{{ route('department.grading_components.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Code</span></label>
                    <input type="text" name="code" class="input input-bordered w-full" placeholder="e.g. WW" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <input type="text" name="description" class="input input-bordered w-full" placeholder="e.g. Written Works" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Percentage</span></label>
                    <input type="number" name="percentage" step="0.01" min="0" max="100" class="input input-bordered w-full" required>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="component_create_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <dialog id="component_edit_modal" class="modal">
        <div class="modal-box max-w-2xl" id="componentEditModalBody"></div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <div data-table-wrapper>
        <div class="overflow-x-auto bg-white shadow">
            <table class="table" data-sortable-table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Percentage</th>
                        <th data-no-sort>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($components as $component)
                        <tr>
                            <td>{{ $component->code }}</td>
                            <td>{{ $component->description }}</td>
                            <td>{{ number_format((float) $component->percentage, 2) }}%</td>
                            <td>
                                <button type="button" class="text-green-600 hover:underline"
                                onclick="openEditComponentModal({{ $component->id }}, '{{ $component->code }}', '{{ $component->description }}', {{ $component->percentage }})"
                                >
                                    edit
                                </button>
                                <form action="{{ route('department.grading_components.delete', $component->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this component?')">
                                    @csrf
                                    <button type="submit" class="text-red-600 hover:underline">delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-gray-500 py-8">No grading components yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.table-sort-search')

    <script>
        function openEditComponentModal(id, code, description, percentage) {
            const component = {
                id,
                code,
                description,
                percentage
            };
            const container = document.getElementById('componentEditModalBody');
            container.innerHTML = `
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">x</button>
                </form>
                <h3 class="text-lg font-bold mb-4">Edit Grading Component</h3>
                <form action="/department/grading-components/${component.id}/update" method="POST" class="space-y-4">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Code</span></label>
                        <input type="text" name="code" class="input input-bordered w-full" value="${component.code}" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Description</span></label>
                        <input type="text" name="description" class="input input-bordered w-full" value="${component.description}" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Percentage</span></label>
                        <input type="number" name="percentage" step="0.01" min="0" max="100" class="input input-bordered w-full" value="${component.percentage}" required>
                    </div>
                    <div class="modal-action">
                        <button type="button" class="btn btn-ghost" onclick="component_edit_modal.close()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            `;

            document.getElementById('component_edit_modal').showModal();
        }
    </script>

</x-department_sidebar>
