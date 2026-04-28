<x-accounting_sidebar>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="m-4 font-bold text-4xl">
        <h2>Fee List</h2>
    </div>

    <div class="m-4 flex">
        <form action="{{ route('accounting.fee.search') }}" method="POST" class="grow flex gap-2 items-end">
            @csrf
            <select name="program" id="programSelect" class="select select-bordered" required>
                <option value="">--Select Program--</option>
                @foreach ($programs as $program)
                <option value="{{ $program->id }}" @if(isset($old_program) && $old_program == $program->id) selected @endif>{{ $program->description }}</option>
                @endforeach
            </select>
            <select name="academic_term_id" id="academicTermSelect" class="select select-bordered" required>
                <option value="">--Select Academic Term--</option>
            </select>
            <button type="submit" class="btn bg-white">Search</button>
        </form>
    </div>
    @if (isset($old_program))
        
    <div class="m-4 grid grid-cols-1 gap-8">
        <div class="mb-8 flex flex-col bg-white shadow p-4">
            <h3 class="text-lg font-bold mb-4">Major Fees</h3>
            <form action="{{ route('accounting.fee.create') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="program_id" value="{{ isset($old_program) ? $old_program : '' }}">
                <input type="hidden" name="academic_term_id" value="{{ isset($old_academic_term_id) ? $old_academic_term_id : '' }}">
                <input type="hidden" name="group" value="major">
                <input type="hidden" name="type" value="">
                <input type="hidden" name="months_to_pay" value="">

                <div class="form-control w-75 mb-3">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <input type="text" name="description" class="input input-bordered input-sm w-full" placeholder="Enter major fee description" required>
                </div>

                <div class="form-control w-90">
                    <label class="label">
                        <span class="label-text">Amount</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="amount" class="input input-bordered input-sm flex-1" placeholder="Enter major fee amount" required>
                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                    </div>
                </div>
            </form>
            
            <div data-table-wrapper>
            <div class="overflow-x-auto bg-white border shadow">
                <table class="table table-zebra table-compact" data-sortable-table>
                    <thead>
                        <tr><th>Description</th><th class="text-end">Amount</th><th class="w-24" data-no-sort>Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($major_fees as $fee)
                        <tr>
                            <td class="max-w-xs">{{ $fee->description }}</td>
                            <td class="text-end whitespace-nowrap">{{ $fee->amount }}</td>
                            <td class="whitespace-nowrap">
                                <button class="btn btn-sm btn-ghost" onclick="edit_major_modal_{{ $fee->id }}.showModal()">Edit</button>
                                <a href="{{ route('accounting.fee.ledger', $fee->id) }}" class="btn btn-sm btn-ghost text-blue-600">Ledger</a>
                                <form action="{{ route('accounting.fee.delete', $fee->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this fee?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-ghost text-red-500">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        <tr data-sort-ignore>
                            <th class="text-end">Total</th>    
                            <th class="text-end">{{ $major_fees->sum('amount') }}</th>
                            <th></th>    
                        </tr>   
                    </tbody>
                </table>
            </div>
            </div>
        </div>

        <div class="mb-8 flex flex-col bg-white shadow p-4">
            <h3 class="text-lg font-bold mb-4">Other Fees</h3>
            <form action="{{ route('accounting.fee.create') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="program_id" value="{{ isset($old_program) ? $old_program : '' }}">
                <input type="hidden" name="academic_term_id" value="{{ isset($old_academic_term_id) ? $old_academic_term_id : '' }}">
                <input type="hidden" name="group" value="other">
                <input type="hidden" name="months_to_pay" value="">

                <div class="form-control w-75 mb-3">
                    <label class="label">
                        <span class="label-text">Type</span>
                    </label>
                    <input type="text" name="type" class="input input-bordered input-sm w-full" placeholder="Enter fee type" required>
                </div>

                <div class="form-control w-75 mb-3">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <input type="text" name="description" class="input input-bordered input-sm w-full" placeholder="Enter fee description" required>
                </div>

                <div class="form-control w-90">
                    <label class="label">
                        <span class="label-text">Amount</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="amount" class="input input-bordered input-sm flex-1" placeholder="Enter fee amount" required>
                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                    </div>
                </div>
            </form>
            
            <div data-table-wrapper>
            <div class="overflow-x-auto bg-white border shadow">
                <table class="table table-zebra table-compact" data-sortable-table>
                    <thead>
                        <tr><th>Description</th><th class="w-20">Type</th><th class="text-end">Amount</th><th class="w-24" data-no-sort>Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($other_fees as $fee)
                        <tr>
                            <td class="max-w-xs">{{ $fee->description }}</td>
                            <td class="whitespace-nowrap">{{ $fee->type }}</td>
                            <td class="text-end whitespace-nowrap">{{ $fee->amount }}</td>
                            <td class="whitespace-nowrap">
                                <button class="btn btn-sm btn-ghost" onclick="edit_other_modal_{{ $fee->id }}.showModal()">Edit</button>
                                <a href="{{ route('accounting.fee.ledger', $fee->id) }}" class="btn btn-sm btn-ghost text-blue-600">Ledger</a>
                                <form action="{{ route('accounting.fee.delete', $fee->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this fee?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-ghost text-red-500">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        <tr data-sort-ignore>
                            <th class="text-end" colspan="2">Total</th>    
                            <th class="text-end">{{ $other_fees->sum('amount') }}</th>
                            <th></th>    
                        </tr>   
                    </tbody>
                </table>
            </div>
            </div>
        </div>

        <div class="mb-8 flex flex-col bg-white shadow p-4">
            <h3 class="text-lg font-bold mb-4">Additional Fees</h3>
            <form action="{{ route('accounting.fee.create') }}" method="POST" class="mb-4">
                @csrf
                <input type="hidden" name="program_id" value="{{ isset($old_program) ? $old_program : '' }}">
                <input type="hidden" name="academic_term_id" value="{{ isset($old_academic_term_id) ? $old_academic_term_id : '' }}">
                <input type="hidden" name="group" value="additional">

                <div class="form-control w-75 mb-3">
                    <label class="label">
                        <span class="label-text">Type</span>
                    </label>
                    <input type="text" name="type" class="input input-bordered input-sm w-full" placeholder="Enter fee type" required>
                </div>

                <div class="form-control w-75 mb-3">
                    <label class="label">
                        <span class="label-text">Months to Pay</span>
                    </label>
                    <input type="text" name="months_to_pay" class="input input-bordered input-sm w-full" placeholder="Enter months to pay" required>
                </div>

                <div class="form-control w-75 mb-3">
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <input type="text" name="description" class="input input-bordered input-sm w-full" placeholder="Enter fee description" required>
                </div>

                <div class="form-control w-90">
                    <label class="label">
                        <span class="label-text">Amount</span>
                    </label>
                    <div class="flex gap-2">
                        <input type="text" name="amount" class="input input-bordered input-sm flex-1" placeholder="Enter fee amount" required>
                        <button type="submit" class="btn btn-primary btn-sm">Add</button>
                    </div>
                </div>
            </form>
            
            <div data-table-wrapper>
            <div class="overflow-x-auto bg-white border shadow">
                <table class="table table-zebra table-compact" data-sortable-table>
                    <thead>
                        <tr><th>Description</th><th class="w-20">Type</th><th class="w-24">Months to Pay</th><th class="text-end">Amount</th><th class="w-24" data-no-sort>Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($additional_fees as $fee)
                        <tr>
                            <td class="max-w-xs">{{ $fee->description }}</td>
                            <td class="whitespace-nowrap">{{ $fee->type }}</td>
                            <td class="whitespace-nowrap text-center">{{ $fee->month_to_pay }}</td>
                            <td class="text-end whitespace-nowrap">{{ $fee->amount }}</td>
                            <td class="whitespace-nowrap">
                                <button class="btn btn-sm btn-ghost" onclick="edit_additional_modal_{{ $fee->id }}.showModal()">Edit</button>
                                <a href="{{ route('accounting.fee.ledger', $fee->id) }}" class="btn btn-sm btn-ghost text-blue-600">Ledger</a>
                                <form action="{{ route('accounting.fee.delete', $fee->id) }}" method="POST" style="display: inline;" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this fee?')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-ghost text-red-500">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                        <tr data-sort-ignore>
                            <th class="text-end" colspan="3">Total</th>    
                            <th class="text-end">{{ $additional_fees->sum('amount') }}</th>
                            <th></th>    
                        </tr>   
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
    @endif

    @include('partials.table-sort-search')
    @include('partials.delete-confirm-modal')

    <!-- Edit Modals for Major Fees -->
    @if(isset($major_fees))
        @foreach ($major_fees as $fee)
        <dialog id="edit_major_modal_{{ $fee->id }}" class="modal">
            <div class="modal-box w-11/12 max-w-2xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="text-lg font-bold mb-4">Edit Major Fee</h3>
                <form action="{{ route('accounting.fee.update', $fee->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="program" value="{{ $old_program }}">
                    <input type="hidden" name="academic_term_id" value="{{ $old_academic_term_id }}">
                    <input type="hidden" name="group" value="major">
                    <input type="hidden" name="type" value="">
                    <input type="hidden" name="months_to_pay" value="">
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Description</span>
                        </label>
                        <input type="text" name="description" value="{{ $fee->description }}" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Amount</span>
                        </label>
                        <input type="text" name="amount" value="{{ $fee->amount }}" class="input input-bordered w-full" required>
                    </div>

                    <div class="modal-action">
                        <button type="button" class="btn" onclick="edit_major_modal_{{ $fee->id }}.close()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Fee</button>
                    </div>
                </form>
            </div>
        </dialog>
        @endforeach
    @endif

    <!-- Edit Modals for Other Fees -->
    @if(isset($other_fees))
        @foreach ($other_fees as $fee)
        <dialog id="edit_other_modal_{{ $fee->id }}" class="modal">
            <div class="modal-box w-11/12 max-w-2xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="text-lg font-bold mb-4">Edit Other Fee</h3>
                <form action="{{ route('accounting.fee.update', $fee->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="program" value="{{ $old_program }}">
                    <input type="hidden" name="academic_term_id" value="{{ $old_academic_term_id }}">
                    <input type="hidden" name="group" value="other">
                    <input type="hidden" name="months_to_pay" value="">
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Type</span>
                        </label>
                        <input type="text" name="type" value="{{ $fee->type }}" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Description</span>
                        </label>
                        <input type="text" name="description" value="{{ $fee->description }}" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Amount</span>
                        </label>
                        <input type="text" name="amount" value="{{ $fee->amount }}" class="input input-bordered w-full" required>
                    </div>

                    <div class="modal-action">
                        <button type="button" class="btn" onclick="edit_other_modal_{{ $fee->id }}.close()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Fee</button>
                    </div>
                </form>
            </div>
        </dialog>
        @endforeach
    @endif

    <!-- Edit Modals for Additional Fees -->
    @if(isset($additional_fees))
        @foreach ($additional_fees as $fee)
        <dialog id="edit_additional_modal_{{ $fee->id }}" class="modal">
            <div class="modal-box w-11/12 max-w-2xl">
                <form method="dialog">
                    <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
                </form>
                <h3 class="text-lg font-bold mb-4">Edit Additional Fee</h3>
                <form action="{{ route('accounting.fee.update', $fee->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="program" value="{{ $old_program }}">
                    <input type="hidden" name="academic_term_id" value="{{ $old_academic_term_id }}">
                    <input type="hidden" name="group" value="additional">
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Type</span>
                        </label>
                        <input type="text" name="type" value="{{ $fee->type }}" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Months to Pay</span>
                        </label>
                        <input type="text" name="months_to_pay" value="{{ $fee->month_to_pay }}" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Description</span>
                        </label>
                        <input type="text" name="description" value="{{ $fee->description }}" class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Amount</span>
                        </label>
                        <input type="text" name="amount" value="{{ $fee->amount }}" class="input input-bordered w-full" required>
                    </div>

                    <div class="modal-action">
                        <button type="button" class="btn" onclick="edit_additional_modal_{{ $fee->id }}.close()">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Fee</button>
                    </div>
                </form>
            </div>
        </dialog>
        @endforeach
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const programSelect = document.getElementById('programSelect');
            const academicTermSelect = document.getElementById('academicTermSelect');
            const oldAcademicTermId = "{{ isset($old_academic_term_id) ? $old_academic_term_id : '' }}";

            function loadAcademicTerms(programId) {
                academicTermSelect.innerHTML = '<option value="">--Select Academic Term--</option>';
                if (!programId) return;

                fetch(`{{ route('accounting.api.academic-terms') }}?program_id=${programId}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(terms => {
                    terms.forEach(term => {
                        const option = document.createElement('option');
                        option.value = term.id;
                        option.textContent = `${term.description}`;
                        if (oldAcademicTermId && oldAcademicTermId == term.id) {
                            option.selected = true;
                        }
                        academicTermSelect.appendChild(option);
                    });
                });
            }

            programSelect.addEventListener('change', function() {
                loadAcademicTerms(this.value);
            });

            // Load on page load if program is already selected
            if (programSelect.value) {
                loadAcademicTerms(programSelect.value);
            }
        });
    </script>
</x-accounting_sidebar>