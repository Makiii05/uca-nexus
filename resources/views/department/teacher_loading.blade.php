<x-department_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl flex-1">Teacher Loading</h2>
        <button type="button" class="btn btn-neutral btn-sm" onclick="openTeacherOfferingModal()">Assign Subject</button>
    </div>

    <x-teacher-offering-modal
        :teacherSelectOptions="$teacherSelectOptions"
        :academicTerms="$academicTerms"
        :departmentId="$departmentId"
    />

    <div data-table-wrapper>
        <div class="overflow-x-auto bg-white shadow">
            <table class="table" data-sortable-table>
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th data-no-sort>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teachers as $teacher)
                        <tr>
                            <td>{{ $teacher->code }}</td>
                            <td>{{ $teacher->last_name }}, {{ $teacher->first_name }} {{ $teacher->middle_name ?? '' }}</td>
                            <td>{{ $teacher->email }}</td>
                            <td>{{ $teacher->status }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-ghost text-primary font-semibold"
                                    onclick="window.location='{{ route('department.teacher_loading.subjects', $teacher->id) }}'">
                                    View Subjects
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-gray-500 py-8">No teachers loaded for this department yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.table-sort-search')

</x-department_sidebar>
