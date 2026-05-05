<x-department_sidebar>
    @include('partials.notifications')

    @php
        $backParams = array_filter([
            'academic_term_id' => request()->query('academic_term_id'),
            'subject_offering_id' => request()->query('subject_offering_id'),
            'period' => request()->query('period'),
        ], fn ($value) => $value !== null && $value !== '');
    @endphp

    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('department.grade_report', $backParams) }}" class="btn btn-ghost btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Back
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Grade Report</h1>
                <p class="text-sm text-gray-500">{{ $academicTerm->description }} • {{ $period }}</p>
            </div>
        </div>

        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Student No.</p>
                        <p class="text-base">{{ $student->student_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Name</p>
                        <p class="text-base">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Program</p>
                        <p class="text-base">{{ $student->program?->code ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 font-semibold">Year Level</p>
                        <p class="text-base">{{ $student->level?->description ?? '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white shadow-lg">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table border border-gray-200 w-full">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Description</th>
                                <th class="w-24">Units</th>
                                <th class="w-32">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row['subject_code'] }}</td>
                                    <td>{{ $row['description'] }}</td>
                                    <td>{{ $row['units'] }}</td>
                                    <td>{{ $row['grade'] !== null ? number_format($row['grade'], 2) : '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-sm text-gray-500 py-8">No enlisted subjects for this academic term.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-right font-semibold">Total Units</td>
                                <td class="font-semibold">{{ number_format($totalUnits, 0) }}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right font-bold">General Average</td>
                                <td class="font-bold">{{ $generalAverage !== null ? number_format($generalAverage, 2) : '—' }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-department_sidebar>
