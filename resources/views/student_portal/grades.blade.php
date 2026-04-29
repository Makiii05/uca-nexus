<x-student_portal_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl flex-1">Grades</h2>

        <form method="GET" class="flex items-end gap-2">
            <div class="form-control">
                <label class="label"><span class="label-text">Academic Term</span></label>
                <select name="academic_term_id" class="select select-bordered select-sm" onchange="this.form.submit()" @if(($academicTerms ?? collect())->isEmpty()) disabled @endif>
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

    @if (($academicTerms ?? collect())->isEmpty())
        <div class="alert bg-blue-400 text-white">
            <span>No enrolled subjects found for your account.</span>
        </div>
    @elseif (!isset($academicTerm) || !$academicTerm)
        <div class="alert bg-blue-400 text-white">
            <span>Please select an academic term to view your grades.</span>
        </div>
    @else
        <div class="bg-white shadow rounded-lg p-6">
            <div class="overflow-x-auto">
                <table class="table table-zebra table-sm">
                    <thead>
                        <tr>
                            <th>Section</th>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Program</th>
                            <th>Level</th>
                            <th>Final Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($enlistments as $enlistment)
                            <tr>
                                <td>{{ $enlistment->subjectOffering?->code ?? '-' }}</td>
                                <td>{{ $enlistment->subjectOffering?->subject?->code ?? '-' }}</td>
                                <td>{{ $enlistment->subjectOffering?->description ?? '-' }}</td>
                                <td>{{ $enlistment->subjectOffering?->program?->code ?? '-' }}</td>
                                <td>{{ $enlistment->subjectOffering?->level?->description ?? '-' }}</td>
                                <td>{{ $enlistment->final_grade ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-gray-500 py-4">No grades found for this academic term.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</x-student_portal_sidebar>
