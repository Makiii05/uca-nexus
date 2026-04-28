<x-accounting_sidebar>

    @include('partials.notifications')

    <div class="m-4">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-4">
                <a href="{{ route('accounting.fee') }}" class="btn btn-ghost btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    Back
                </a>
                <h2 class="font-bold text-4xl">Fee Ledger</h2>
            </div>
            <a href="{{ route('accounting.fee.ledger.print', $fee->id) }}" target="_blank" class="btn btn-primary btn-sm">
                Print Ledger
            </a>
        </div>

        <!-- Fee Information Card -->
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                    <label class="text-sm font-semibold text-gray-500">Academic Term</label>
                    <p class="text-lg font-medium">{{ $fee->academicTerm->description ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-500">Fee Description</label>
                    <p class="text-lg font-medium">
                        @if($isUnitFee)
                            {{ $fee->description }} ({{ number_format($fee->amount, 2) }} per unit)
                        @else
                            {{ $fee->description }}
                        @endif
                    </p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-500">Fee Type</label>
                    <p class="text-lg font-medium">{{ ucfirst($fee->group) }} {{ $fee->type ? '- ' . $fee->type : '' }}</p>
                </div>
                <div>
                    <label class="text-sm font-semibold text-gray-500">{{ $isUnitFee ? 'Rate per Unit' : 'Amount per Student' }}</label>
                    <p class="text-lg font-medium text-green-600">{{ number_format($fee->amount, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white shadow rounded-lg p-4">
            <h3 class="text-lg font-bold mb-4">Students with this Fee</h3>
            
            <div data-table-wrapper>
            <div class="overflow-x-auto">
                <table class="table table-zebra table-compact" data-sortable-table>
                    <thead>
                        <tr>
                            <th class="w-12">#</th>
                            <th>Student Number</th>
                            <th>Student Name</th>
                            <th>Program</th>
                            <th>Level</th>
                            @if($isUnitFee)
                            <th class="text-center">Units</th>
                            @endif
                            <th class="text-end">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($studentFees as $index => $studentFee)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $studentFee->student->student_number ?? 'N/A' }}</td>
                            <td>{{ $studentFee->student->last_name ?? '' }}, {{ $studentFee->student->first_name ?? '' }} {{ $studentFee->student->middle_name ?? '' }}</td>
                            <td>{{ $studentFee->student->program->code ?? 'N/A' }}</td>
                            <td>{{ $studentFee->student->level->description ?? 'N/A' }}</td>
                            @if($isUnitFee)
                            <td class="text-center">{{ $studentFee->total_units ?? 0 }}</td>
                            @endif
                            <td class="text-end">{{ number_format($studentFee->calculated_amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $isUnitFee ? 7 : 6 }}" class="text-center text-gray-500 py-8">No students have this fee assigned.</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($studentFees->count() > 0)
                    <tfoot>
                        <tr class="bg-gray-100 font-bold">
                            <td colspan="{{ $isUnitFee ? 6 : 5 }}" class="text-end">Grand Total ({{ $studentFees->count() }} students)</td>
                            <td class="text-end text-green-600">{{ number_format($grandTotal, 2) }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            </div>
        </div>
    </div>

    @include('partials.table-sort-search')

</x-accounting_sidebar>
