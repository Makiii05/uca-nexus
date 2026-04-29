<x-department_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('department.teacher_loading') }}" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Back
        </a>
        <h2 class="font-bold text-4xl flex-1">{{ $teacher->first_name }}'s subject</h2>
    </div>

    <div class="flex flex-wrap items-end gap-3 mb-4">
        <form method="GET" class="flex items-end gap-2">
            <div class="form-control">
                <label class="label"><span class="label-text">Academic Term</span></label>
                <select name="academic_term_id" class="select select-bordered select-sm" onchange="this.form.submit()">
                    <option value="">-- Select Academic Term --</option>
                    @foreach ($academicTerms as $term)
                        <option value="{{ $term->id }}" @if(optional($academicTerm)->id === $term->id) selected @endif>
                            {{ $term->description }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <button type="button" class="btn btn-neutral btn-sm"
            onclick="openTeacherOfferingModal({ teacherId: {{ $teacher->id }}, academicTermId: {{ optional($academicTerm)->id ?? 'null' }}, lockTeacher: true })">
            Assign Subject
        </button>
    </div>

    <x-teacher-offering-modal
        :teacherSelectOptions="collect([$teacher])"
        :academicTerms="$academicTerms"
        :departmentId="$departmentId"
    />

    @if (!isset($academicTerm) || !$academicTerm)
        <div class="alert bg-blue-400 text-white">
            <span>Please select an academic term to view assigned subjects.</span>
        </div>
    @else
        <div data-table-wrapper>
            <div class="overflow-x-auto bg-white shadow">
                <table class="table" data-sortable-table>
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Program</th>
                            <th>Level</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teacherOfferings as $teacherOffering)
                            <tr>
                                <td>{{ $teacherOffering->offering?->code ?? '-' }}</td>
                                <td>{{ $teacherOffering->offering?->subject?->code ?? '-' }}</td>
                                <td>{{ $teacherOffering->offering?->description ?? '-' }}</td>
                                <td>{{ $teacherOffering->offering?->program?->code ?? '-' }}</td>
                                <td>{{ $teacherOffering->offering?->level?->description ?? '-' }}</td>
                                <td>{{ $teacherOffering->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-8">No subjects assigned for this academic term.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('partials.table-sort-search')
    @endif

</x-department_sidebar>
