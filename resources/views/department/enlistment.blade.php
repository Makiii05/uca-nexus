<x-department_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl">Enlistment</h2>
        @if(isset($academicTerm))
        <h2 class="flex-1 text-2xl"><span>(Academic Term: <strong>{{ $academicTerm->description }}</strong>)</span></h2>
        @endif
    </div>
    
    <!--TABLE-->
    <div class="overflow-x-auto bg-white shadow" data-table-wrapper>
        <table class="table" data-sortable-table>
            <!-- head -->
            <thead>
                <tr>
                    <th>Student No.</th>
                    <th>Full Name</th>
                    <th>Program</th>
                    <th>Level</th>
                    <th>Status</th>
                    <th data-no-sort>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                <tr class="hover:bg-base-200 cursor-pointer" onclick="window.location='#'">
                    <td>{{ $student->student_number }}</td>
                    <td>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</td>
                    <td>{{ $student->program->code ?? '-' }}</td>
                    <td>{{ $student->level->description ?? '-' }}</td>
                    <td>{{ ucfirst($student->status) }}</td>
                    <td>
                        <div class="flex gap-1 flex-wrap">
                            <a 
                                href="{{ route('department.student.subjects', ['student_id' => $student->id, 'academic_term_id' => $academicTerm->id]) }}"
                                type="button" 
                                class="btn btn-sm btn-ghost text-primary font-semibold"
                                onclick="event.stopPropagation();"
                            >
                                View Subject
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-gray-500 py-8">No students found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @include('partials.table-sort-search')

</x-department_sidebar>
