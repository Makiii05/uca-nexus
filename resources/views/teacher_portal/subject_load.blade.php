<x-teacher_portal_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl flex-1">Subject Load</h2>
    </div>

    <div class="flex flex-wrap items-end gap-3 mb-4">
        <form method="GET" class="flex items-end gap-2">
            <div class="form-control">
                <label class="label"><span class="label-text">Academic Term</span></label>
                <select name="academic_term_id" class="select select-bordered select-sm" onchange="this.form.submit()" @if($academicTerms->isEmpty()) disabled @endif>
                    <option value="">-- Select Academic Term --</option>
                    @foreach ($academicTerms as $term)
                        <option value="{{ $term->id }}" @if(optional($academicTerm)->id === $term->id) selected @endif>
                            {{ $term->description }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    @if ($academicTerms->isEmpty())
        <div class="alert bg-blue-400 text-white">
            <span>No academic terms available for your assigned subjects.</span>
        </div>
    @elseif (!isset($academicTerm) || !$academicTerm)
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
                            <th>Action</th>
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
                                <td>
                                    <a href="{{ route('teacher_portal.input_grade', $teacherOffering->id) }}" class="btn btn-neutral btn-sm">
                                        Input Grade
                                    </a>
                                </td>
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

</x-teacher_portal_sidebar>
