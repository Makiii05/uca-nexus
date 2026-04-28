<x-student_portal_sidebar>

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl">Account Ledger</h2>
        <select id="academicTermSelect" class="select select-bordered select-sm">
            <option value="">-- Select Academic Term --</option>
            @foreach ($academicTerms as $term)
                <option value="{{ $term->id }}">{{ $term->description }}</option>
            @endforeach
        </select>
    </div>

    <div class="flex flex-col gap-5">
        <!-- LEFT SECTION: Student Info -->
        <div class="flex flex-col gap-5">
            <!-- Student Info Box -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Student Information</h3>
                <div class="grid grid-cols-1 gap-4 text-sm">
                    <div class="flex justify-between">
                        <div>
                            <span class="font-semibold text-gray-500">Student No.</span>
                            <p class="text-base">{{ $student->student_number }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-500">Name</span>
                            <p class="text-base">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</p>
                        </div>
                    </div>
                    <div class="flex justify-between">
                        <div>
                            <span class="font-semibold text-gray-500">Program</span>
                            <p class="text-base">{{ $student->program->code ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-500">Department</span>
                            <p class="text-base">{{ $student->department->code ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-500">Year Level</span>
                            <p class="text-base">{{ $student->level->description ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SECTION: Ledger Table -->
        <div class="flex flex-col gap-5">
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Account Ledger</h3>
                <div class="overflow-x-auto">
                    <table class="table table-zebra table-sm">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th class="text-end">Amount</th>
                                <th class="text-end">Payment</th>
                            </tr>
                        </thead>
                        <tbody id="ledgerTableBody">
                            <tr><td colspan="3" class="text-center text-gray-500 py-4">Select an academic term to view your ledger.</td></tr>
                        </tbody>
                        <tfoot id="ledgerTableFoot" class="hidden">
                            <tr class="font-semibold bg-gray-100">
                                <td>Balance</td>
                                <td class="text-end" id="ledgerTotalFees">0.00</td>
                                <td class="text-end" id="ledgerTotalPayments">0.00</td>
                            </tr>
                            <tr class="font-bold text-lg">
                                <td colspan="2">Outstanding Balance</td>
                                <td class="text-end" id="ledgerBalance">0.00</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ledgerApiUrl = '{{ url("/student-portal/api/ledger") }}';
        const csrfToken = '{{ csrf_token() }}';

        function getSelectedTermId() {
            return document.getElementById('academicTermSelect').value;
        }

        // Number Formatting
        function formatMoney(value) {
            return Number(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // Academic Term Change
        document.getElementById('academicTermSelect').addEventListener('change', function () {
            const termId = this.value;
            loadLedger(termId);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('academicTermSelect');
            if (select.value) {
                loadLedger(select.value);
            }
        });

        async function loadLedger(academicTermId) {
            const tbody = document.getElementById('ledgerTableBody');
            const tfoot = document.getElementById('ledgerTableFoot');

            if (!academicTermId) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-4">Select an academic term to view your ledger.</td></tr>';
                tfoot.classList.add('hidden');
                return;
            }

            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-4"><span class="loading loading-spinner loading-sm"></span> Loading...</td></tr>';

            try {
                const response = await fetch(`${ledgerApiUrl}/${academicTermId}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                });
                
                if (!response.ok) throw new Error('Failed to fetch');
                const result = await response.json();

                tbody.innerHTML = '';
                
                if (result.success && result.data) {
                    const { fees, transactions, total_fees, total_payments, balance } = result.data;

                    // Add fee rows
                    if (fees && fees.length > 0) {
                        fees.forEach(fee => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${fee.description}</td>
                                <td class="text-end">${formatMoney(fee.amount)}</td>
                                <td class="text-end">-</td>
                            `;
                            tbody.appendChild(row);
                        });
                    }

                    // Add transaction rows (payments)
                    if (transactions && transactions.length > 0) {
                        transactions.forEach(t => {
                            const row = document.createElement('tr');
                            row.classList.add('bg-green-50');
                            row.innerHTML = `
                                <td>
                                    <span class="text-green-600">Payment</span>
                                    <span class="text-xs text-gray-500"> - ${t.date}${t.or_number ? ' (OR#: ' + t.or_number + ')' : ''}</span>
                                </td>
                                <td class="text-end">-</td>
                                <td class="text-end text-green-600">${formatMoney(t.amount)}</td>
                            `;
                            tbody.appendChild(row);
                        });
                    }

                    if ((!fees || fees.length === 0) && (!transactions || transactions.length === 0)) {
                        tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-4">No records found for this academic term.</td></tr>';
                        tfoot.classList.add('hidden');
                    } else {
                        // Update totals
                        document.getElementById('ledgerTotalFees').textContent = formatMoney(total_fees);
                        document.getElementById('ledgerTotalPayments').textContent = formatMoney(total_payments);
                        document.getElementById('ledgerBalance').textContent = formatMoney(balance);
                        
                        // Add balance color
                        const balanceEl = document.getElementById('ledgerBalance');
                        if (balance > 0) {
                            balanceEl.classList.add('text-red-600');
                            balanceEl.classList.remove('text-green-600');
                        } else {
                            balanceEl.classList.add('text-green-600');
                            balanceEl.classList.remove('text-red-600');
                        }
                        
                        tfoot.classList.remove('hidden');
                    }
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-4">No records found.</td></tr>';
                    tfoot.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error loading ledger:', error);
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-red-500 py-4">Error loading ledger data.</td></tr>';
                tfoot.classList.add('hidden');
            }
        }
    </script>

</x-student_portal_sidebar>
