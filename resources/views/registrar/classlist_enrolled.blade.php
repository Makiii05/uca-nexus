<x-registrar_sidebar>

    <div class="m-4 flex justify-between items-center">
        <div>
            <a href="{{ route('registrar.classlist') }}" class="btn btn-ghost btn-sm">
                ← Back to Class List
            </a>
            <h2 class="font-bold text-4xl mt-2">Enrolled Students</h2>
            <p class="text-gray-600 mt-1">
                {{ $subjectOffering->subject->description ?? 'N/A' }} ({{ $subjectOffering->code }}) - 
                {{ $subjectOffering->program->code ?? 'N/A' }} | 
                Year Level: {{ $yearLevel }}
            </p>
        </div>
        <div>
            <a href="{{ route('registrar.classlist.print', $subjectOffering->id) }}" target="_blank" class="btn btn-primary">
                Print Class List
            </a>
        </div>
    </div>

    @include('partials.notifications')

    @php
        $hasTuitionFees = $femaleStudents->contains(fn($e) => $e->or_number !== null) ||
                          $maleStudents->contains(fn($e) => $e->or_number !== null);
    @endphp

    <div class=" gap-4 m-4">
        <!-- FEMALE TABLE -->
        <div class="flex-1 overflow-x-auto bg-white shadow rounded">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th colspan="{{ $hasTuitionFees ? 4 : 3 }}" class="font-bold text-black">Female Students ({{ $femaleStudents->count() }})</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Student Number</th>
                        <th>Name</th>
                        @if($hasTuitionFees)
                            <th>OR#</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($femaleStudents as $enlistment)
                    @php static $femaleCounter = 1; @endphp
                    <tr class="hover:bg-gray-100">
                        <td>{{ $femaleCounter++ }}</td>
                        <td>{{ $enlistment->student->student_number ?? 'N/A' }}</td>
                        <td>{{ $enlistment->student->last_name }}, {{ $enlistment->student->first_name }} {{ $enlistment->student->middle_name }}</td>
                        @if($hasTuitionFees)
                            <td>{{ $enlistment->or_number ?? '' }}</td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $hasTuitionFees ? 4 : 3 }}" class="text-center text-gray-400 py-4">No female students</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- MALE TABLE -->
        <div class="flex-1 overflow-x-auto bg-white shadow rounded">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th colspan="{{ $hasTuitionFees ? 4 : 3 }}" class="font-bold text-black">Male Students ({{ $maleStudents->count() }})</th>
                    </tr>
                    <tr>
                        <th>#</th>
                        <th>Student Number</th>
                        <th>Name</th>
                        @if($hasTuitionFees)
                            <th>OR#</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($maleStudents as $enlistment)
                    @php static $maleCounter = 1; @endphp
                    <tr class="hover:bg-gray-100">
                        <td>{{ $maleCounter++ }}</td>
                        <td>{{ $enlistment->student->student_number ?? 'N/A' }}</td>
                        <td>{{ $enlistment->student->last_name }}, {{ $enlistment->student->first_name }} {{ $enlistment->student->middle_name }}</td>
                        @if($hasTuitionFees)
                            <td>{{ $enlistment->or_number ?? '' }}</td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $hasTuitionFees ? 4 : 3 }}" class="text-center text-gray-400 py-4">No male students</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Total Summary -->
    <div class="m-4 bg-white shadow p-4">
        <p class="font-bold text-center">Total Enrolled: {{ $femaleStudents->count() + $maleStudents->count() }}</p>
    </div>

</x-registrar_sidebar>
