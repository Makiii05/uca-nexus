<x-registrar_sidebar>

    <div class="m-4 font-bold text-4xl">
        <h2>Teacher</h2>
    </div>

    @include('partials.notifications')

    <div class="m-4 grid">
        <button class="btn w-auto justify-self-end bg-white shadow" onclick="openTeacherCreateModal()">Add Teacher</button>
    </div>

    <x-teacher-form-modal />

    <!--TABLE-->
    <div data-table-wrapper>
        <div class="overflow-x-auto bg-white shadow">
            <table class="table" data-sortable-table>
                <thead>
                    <tr>
                        <th></th>
                        <th>Code</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Portal Status</th>
                        <th data-no-sort></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->id }}</td>
                            <td>{{ $teacher->code }}</td>
                            <td>{{ $teacher->first_name }}</td>
                            <td>{{ $teacher->middle_name ?? 'N/A' }}</td>
                            <td>{{ $teacher->last_name }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->status }}</td>
                            <td>{{ $teacher->account?->status ?? 'off' }}</td>
                            <td>
                                <button class="text-green-600 hover:underline" onclick='openTeacherEditModal(@json($teacher))'>edit</button>

                                <form action="{{ route('registrar.teacher.toggle-account', $teacher->id) }}" method="POST" style="display:inline;" onsubmit="return confirmSubmit(this, 'Are you sure you want to change this teacher\'s portal account status?')">
                                    @csrf
                                    @if(($teacher->account?->status ?? 'off') === 'on')
                                        <button type="submit" class="text-orange-600 hover:underline">close account</button>
                                    @else
                                        <button type="submit" class="text-blue-600 hover:underline">open account</button>
                                    @endif
                                </form>

                                <form action="{{ route('registrar.teacher.delete', $teacher->id) }}" method="POST" style="display:inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this teacher?')">
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
        const TEACHER_CREATE_ACTION = @json(route('registrar.teacher.create'));
        const TEACHER_UPDATE_BASE = @json(url('/registrar/teachers'));

        function setTeacherModalMode(mode) {
            const titleEl = document.getElementById('teacher-modal-title');
            const submitEl = document.getElementById('teacher-modal-submit');

            if (!titleEl || !submitEl) return;

            if (mode === 'edit') {
                titleEl.textContent = 'Edit Teacher';
                submitEl.textContent = 'Update Teacher';
            } else {
                titleEl.textContent = 'Add Teacher';
                submitEl.textContent = 'Save Teacher';
            }
        }

        function openTeacherCreateModal() {
            const form = document.getElementById('teacher-form');
            if (!form) return;

            setTeacherModalMode('create');

            form.action = TEACHER_CREATE_ACTION;
            document.getElementById('teacher_code').value = '';
            document.getElementById('teacher_first_name').value = '';
            document.getElementById('teacher_middle_name').value = '';
            document.getElementById('teacher_last_name').value = '';
            document.getElementById('teacher_email').value = '';
            document.getElementById('teacher_status').value = 'active';

            form_modal.showModal();
        }

        function openTeacherEditModal(teacher) {
            const form = document.getElementById('teacher-form');
            if (!form) return;

            setTeacherModalMode('edit');

            form.action = `${TEACHER_UPDATE_BASE}/${teacher.id}/update`;
            document.getElementById('teacher_code').value = teacher.code ?? '';
            document.getElementById('teacher_first_name').value = teacher.first_name ?? '';
            document.getElementById('teacher_middle_name').value = teacher.middle_name ?? '';
            document.getElementById('teacher_last_name').value = teacher.last_name ?? '';
            document.getElementById('teacher_email').value = teacher.email ?? '';
            document.getElementById('teacher_status').value = teacher.status ?? 'active';

            form_modal.showModal();
        }
    </script>

</x-registrar_sidebar>
