<x-registrar_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <a href="{{ route('registrar.student') }}" class="btn btn-ghost btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
            Back
        </a>
        <h2 class="font-bold text-4xl">Student Assessment</h2>
        <select id="academicTermSelect" class="select select-bordered select-sm">
            <option value="">-- Select Academic Term --</option>
            @foreach ($academicTerms as $term)
                <option value="{{ $term->id }}">{{ $term->description }}</option>
            @endforeach
        </select>
        <div class="ml-auto">
            <a id="printAssessmentLink" href="#" target="_blank" class="btn btn-neutral btn-sm" onclick="return handlePrintClick()">
                Print Assessment
            </a>
        </div>
    </div>

    <div class="flex gap-5">
        <!-- LEFT SECTION: Student Info + Enlistment Table -->
        <div class="w-1/2 flex flex-col gap-5">
            <!-- Student Info Box -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Student Information</h3>
                <div class="flex gap-6">
                    <!-- Left: Academic Info -->
                    <div class="flex-1 flex flex-col gap-3 text-sm">
                        <div>
                            <span class="font-semibold text-gray-500">Student No.</span>
                            <p class="text-base">{{ $student->student_number }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-500">Name</span>
                            <p class="text-base">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</p>
                        </div>
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
                            <div class="flex items-center gap-2 mt-1">
                                <select id="levelSelect" class="select select-bordered select-sm">
                                    @foreach ($levels as $level)
                                        <option value="{{ $level->id }}" {{ $level->id == $student->level_id ? 'selected' : '' }}>{{ $level->description }}</option>
                                    @endforeach
                                </select>
                                <button id="updateLevelBtn" class="btn btn-neutral btn-sm" onclick="updateLevel()">Update</button>
                            </div>
                            <div id="levelUpdateMessage" class="text-xs mt-1"></div>
                        </div>
                    </div>
                    <!-- Right: Contact Info -->
                    <div class="flex-1 flex flex-col gap-3 text-sm">
                        <div>
                            <span class="font-semibold text-gray-500">Contact No.</span>
                            <p class="text-base">{{ $student->contact->mobile_number ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-500">Email</span>
                            <p class="text-base">{{ $student->contact->email ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-500">Address</span>
                            <p class="text-base">{{ $student->contact->present_address ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enlistment Table -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Enlisted Subjects</h3>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Units</th>
                            </tr>
                        </thead>
                        <tbody id="enlistmentTableBody">
                            <tr>
                                <td colspan="3" class="text-center text-gray-500 py-8">Select an academic term to view subjects.</td>
                            </tr>
                        </tbody>
                        <tfoot id="enlistmentTableFoot" class="hidden">
                            <tr>
                                <td colspan="2" class="text-right font-semibold">Total Units:</td>
                                <td class="font-semibold" id="totalUnits">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Fee Tables -->
            <div id="feeSection" class="flex flex-col gap-5">
                <!-- Major Fees -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">Major Fees</h3>
                        <div class="flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="openAddNewFeeModal('major')">Add New Fee</button>
                            <button class="btn btn-outline btn-sm" onclick="openExistingFeesModal('major')">Add Existing Fee</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra table-sm">
                            <thead>
                                <tr><th>Description</th><th class="text-end">Amount</th><th class="w-20">Actions</th></tr>
                            </thead>
                            <tbody id="majorFeesBody">
                                <tr><td colspan="3" class="text-center text-gray-500 py-4">Select an academic term.</td></tr>
                            </tbody>
                            <tfoot id="majorFeesFoot" class="hidden">
                                <tr><td class="text-end font-semibold">Total</td><td class="text-end font-semibold" id="majorFeesTotal">0</td><td></td></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Other Fees -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">Other Fees</h3>
                        <div class="flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="openAddNewFeeModal('other')">Add New Fee</button>
                            <button class="btn btn-outline btn-sm" onclick="openExistingFeesModal('other')">Add Existing Fee</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra table-sm">
                            <thead>
                                <tr><th>Description</th><th class="w-20">Type</th><th class="text-end">Amount</th><th class="w-20">Actions</th></tr>
                            </thead>
                            <tbody id="otherFeesBody">
                                <tr><td colspan="4" class="text-center text-gray-500 py-4">Select an academic term.</td></tr>
                            </tbody>
                            <tfoot id="otherFeesFoot" class="hidden">
                                <tr><td colspan="2" class="text-end font-semibold">Total</td><td class="text-end font-semibold" id="otherFeesTotal">0</td><td></td></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- Additional Fees -->
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">Additional Fees</h3>
                        <div class="flex gap-2">
                            <button class="btn btn-primary btn-sm" onclick="openAddNewFeeModal('additional')">Add New Fee</button>
                            <button class="btn btn-outline btn-sm" onclick="openExistingFeesModal('additional')">Add Existing Fee</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra table-sm">
                            <thead>
                                <tr><th>Description</th><th class="w-20">Type</th><th class="w-24">Months to Pay</th><th class="text-end">Amount</th><th class="w-20">Actions</th></tr>
                            </thead>
                            <tbody id="additionalFeesBody">
                                <tr><td colspan="5" class="text-center text-gray-500 py-4">Select an academic term.</td></tr>
                            </tbody>
                            <tfoot id="additionalFeesFoot" class="hidden">
                                <tr><td colspan="3" class="text-end font-semibold">Total</td><td class="text-end font-semibold" id="additionalFeesTotal">0</td><td></td></tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT SECTION: Fee Details -->
        <div class="w-1/2 flex flex-col gap-5">
            <div class="bg-white shadow rounded-lg p-6" id="feeDetailsSection">
                <div class="flex gap-6">
                    <!-- Left: Schedule of Fees -->
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm mb-2 text-gray-600">Schedule of Fees</h4>
                        <div class="overflow-x-auto">
                            <table class="table table-zebra table-sm">
                                <thead>
                                    <tr><th>Schedule</th><th class="text-end">Amount</th></tr>
                                </thead>
                                <tbody id="scheduleFeesBody">
                                    <tr><td>Down Payment</td><td class="text-end" id="schedDownPayment">0.00</td></tr>
                                    <tr><td>1st Month Pay</td><td class="text-end" id="sched1stMonth">0.00</td></tr>
                                    <tr><td>2nd Month Pay</td><td class="text-end" id="sched2ndMonth">0.00</td></tr>
                                    <tr><td>3rd Month Pay</td><td class="text-end" id="sched3rdMonth">0.00</td></tr>
                                    <tr><td>4th Month Pay</td><td class="text-end" id="sched4thMonth">0.00</td></tr>
                                </tbody>
                                <tfoot>
                                    <tr class="font-semibold"><td>Total</td><td class="text-end" id="schedTotal">0.00</td></tr>
                                </tfoot>
                            </table>
                        </div>
                        <button class="btn btn-outline btn-sm mt-3 w-full" onclick="recomputeSchedule()">Recompute Schedule of Fees</button>
                    </div>
                    <!-- Right: Fee Summary -->
                    <div class="flex-1">
                        <h4 class="font-semibold text-sm mb-2 text-gray-600">Fee Summary</h4>
                        <div class="overflow-x-auto">
                            <table class="table table-sm">
                                <thead>
                                    <tr><th>Type</th><th class="text-end">Amount</th></tr>
                                </thead>
                                <tbody>
                                    <tr><td>Major Fees</td><td class="text-end" id="summaryMajor">0.00</td></tr>
                                    <tr><td>Other Fees</td><td class="text-end" id="summaryOther">0.00</td></tr>
                                    <tr><td>Additional Fees</td><td class="text-end" id="summaryAdditional">0.00</td></tr>
                                </tbody>
                            </table>
                            <div class="divider my-1"></div>
                            <table class="table table-sm">
                                <tbody>
                                    <tr class="font-semibold"><td>Gross Total</td><td class="text-end" id="summaryGross">0.00</td></tr>
                                </tbody>
                            </table>
                            <div class="divider my-1"></div>
                            <table class="table table-sm">
                                <tbody>
                                    <tr class="font-bold text-lg"><td>Amount to Pay</td><td class="text-end" id="summaryAmountToPay">0.00</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assessment History Table -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="font-semibold text-lg mb-4">Printing of Assessment History</h3>
                <div class="overflow-x-auto">
                    <table class="table table-zebra table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Term</th>
                                <th class="w-32">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="assessmentHistoryBody">
                            <tr>
                                <td colspan="3" class="text-center text-gray-500 py-4">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add New Fee Modal -->
    <dialog id="addNewFeeModal" class="modal">
        <div class="modal-box w-11/12 max-w-md">
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button></form>
            <h3 class="text-lg font-bold mb-4" id="addNewFeeModalTitle">Add New Fee</h3>
            <form id="addNewFeeForm" class="space-y-3">
                <input type="hidden" id="newFeeGroup" name="group" value="">

                <div id="newFeeTypeField" class="form-control hidden">
                    <label class="label"><span class="label-text">Type</span></label>
                    <input type="text" id="newFeeType" name="type" class="input input-bordered input-sm w-full" placeholder="Enter fee type">
                </div>

                <div id="newFeeMonthsField" class="form-control hidden">
                    <label class="label"><span class="label-text">Months to Pay</span></label>
                    <input type="text" id="newFeeMonths" name="months_to_pay" class="input input-bordered input-sm w-full" placeholder="Enter months to pay">
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <input type="text" id="newFeeDescription" name="description" class="input input-bordered input-sm w-full" placeholder="Enter fee description" required>
                </div>

                <div class="form-control">
                    <label class="label"><span class="label-text">Amount</span></label>
                    <input type="text" id="newFeeAmount" name="amount" class="input input-bordered input-sm w-full" placeholder="Enter amount" required>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-sm" onclick="addNewFeeModal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm" id="addNewFeeBtn">
                        <span id="addNewFeeText">Add</span>
                        <span id="addNewFeeLoading" class="loading loading-spinner loading-xs hidden"></span>
                    </button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Add Existing Fee Modal -->
    <dialog id="existingFeesModal" class="modal">
        <div class="modal-box w-11/12 max-w-lg">
            <form method="dialog"><button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button></form>
            <h3 class="text-lg font-bold mb-4" id="existingFeesModalTitle">Add Existing Fee</h3>
            <div id="existingFeesList" class="space-y-2">
                <div class="text-center text-gray-500 py-4">Loading...</div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-primary btn-sm hidden" id="addAllFeesBtn" onclick="assignAllFees()">
                    <span id="addAllFeesText">Add All</span>
                    <span id="addAllFeesLoading" class="loading loading-spinner loading-xs hidden"></span>
                </button>
                <button type="button" class="btn btn-sm" onclick="existingFeesModal.close()">Close</button>
            </div>
        </div>
    </dialog>

    <script>
        const studentId = {{ $student->id }};
        const enlistmentsApiUrl = '{{ url("/registrar/api/enlistments") }}';
        const studentFeesApiUrl = '{{ url("/registrar/api/student-fees") }}';
        const existingFeesApiUrl = '{{ url("/registrar/api/existing-fees") }}';
        const createFeeApiUrl = '{{ url("/registrar/api/student-fees") }}/' + studentId + '/create';
        const assignFeeApiUrl = '{{ url("/registrar/api/student-fees") }}/' + studentId + '/assign';
        const removeFeeApiUrl = '{{ url("/registrar/api/student-fees") }}';
        const assessmentHistoriesApiUrl = '{{ url("/registrar/api/assessment-histories") }}';
        const csrfToken = '{{ csrf_token() }}';
        const printBaseUrl = '{{ route('registrar.student.print-assessment', $student->id) }}';

        function getSelectedTermId() {
            return document.getElementById('academicTermSelect').value;
        }

        // ── Number Formatting ─────────────────────────────────
        function formatMoney(value) {
            return Number(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        // ── Print Assessment ──────────────────────────────────
        function handlePrintClick() {
            const termId = getSelectedTermId();
            if (!termId) {
                alert('Select an academic term first.');
                return false;
            }
            const link = document.getElementById('printAssessmentLink');
            link.href = printBaseUrl + '?academic_term_id=' + termId;
            // Reload assessment history after a short delay (to allow the PDF to be generated)
            setTimeout(() => loadAssessmentHistories(), 1000);
            return true;
        }

        // ── Enlistments ──────────────────────────────────────
        document.getElementById('academicTermSelect').addEventListener('change', function () {
            const termId = this.value;
            loadEnlistments(termId);
            loadStudentFees(termId);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const select = document.getElementById('academicTermSelect');
            if (select.value) {
                loadEnlistments(select.value);
                loadStudentFees(select.value);
            }
        });

        async function loadEnlistments(academicTermId) {
            const tbody = document.getElementById('enlistmentTableBody');
            const tfoot = document.getElementById('enlistmentTableFoot');
            const totalUnitsEl = document.getElementById('totalUnits');

            if (!academicTermId) {
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-8">Select an academic term to view subjects.</td></tr>';
                tfoot.classList.add('hidden');
                return;
            }

            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-4"><span class="loading loading-spinner loading-sm"></span> Loading...</td></tr>';

            try {
                const response = await fetch(`${enlistmentsApiUrl}/${studentId}/${academicTermId}`);
                if (!response.ok) throw new Error('Failed to fetch');
                const result = await response.json();

                tbody.innerHTML = '';
                let totalUnits = 0;

                if (result.data && result.data.length > 0) {
                    currentEnlistmentCount = result.data.length;
                    result.data.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.code}</td>
                            <td>${item.description}</td>
                            <td>${item.unit}</td>
                        `;
                        tbody.appendChild(row);
                        totalUnits += Number(item.unit) || 0;
                    });
                    totalUnitsEl.textContent = totalUnits;
                    tfoot.classList.remove('hidden');
                } else {
                    currentEnlistmentCount = 0;
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-8">No enlisted subjects.</td></tr>';
                    tfoot.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error loading enlistments:', error);
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-red-500 py-8">Error loading subjects.</td></tr>';
                tfoot.classList.add('hidden');
            }
        }

        // ── Student Fees ─────────────────────────────────────
        async function loadStudentFees(academicTermId) {
            const section = document.getElementById('feeSection');

            if (!academicTermId) {
                section.classList.add('hidden');
                return;
            }

            section.classList.remove('hidden');

            // Show loading in all 3 tables
            ['major', 'other', 'additional'].forEach(g => {
                const cols = g === 'major' ? 3 : g === 'other' ? 4 : 5;
                document.getElementById(`${g}FeesBody`).innerHTML = `<tr><td colspan="${cols}" class="text-center text-gray-500 py-4"><span class="loading loading-spinner loading-sm"></span> Loading...</td></tr>`;
                document.getElementById(`${g}FeesFoot`).classList.add('hidden');
            });

            try {
                const response = await fetch(`${studentFeesApiUrl}/${studentId}/${academicTermId}`);
                if (!response.ok) throw new Error('Failed to fetch');
                const result = await response.json();
                const data = result.data;

                renderFeeTable('major', data.major);
                renderFeeTable('other', data.other);
                renderFeeTable('additional', data.additional);
            } catch (error) {
                console.error('Error loading fees:', error);
                ['major', 'other', 'additional'].forEach(g => {
                    const cols = g === 'major' ? 3 : g === 'other' ? 4 : 5;
                    document.getElementById(`${g}FeesBody`).innerHTML = `<tr><td colspan="${cols}" class="text-center text-red-500 py-4">Error loading fees.</td></tr>`;
                });
            }
        }

        // Track enlistment count for Unit Fee calculation
        let currentEnlistmentCount = 0;

        // Track fee totals for right panel
        let feeTotals = { major: 0, other: 0, additional: 0 };

        function renderFeeTable(group, fees) {
            const tbody = document.getElementById(`${group}FeesBody`);
            const tfoot = document.getElementById(`${group}FeesFoot`);
            const totalEl = document.getElementById(`${group}FeesTotal`);

            tbody.innerHTML = '';
            let total = 0;

            if (fees && fees.length > 0) {
                fees.forEach(f => {
                    let displayDesc = f.description;
                    let displayAmount = parseFloat(f.amount) || 0;

                    // Unit Fee: show "Unit Fee (origValue)" and amount = origValue * enlistment count
                    if (f.description && f.description.toLowerCase() === 'unit fee') {
                        const origAmount = parseFloat(f.amount) || 0;
                        displayDesc = `Unit Fee (${formatMoney(origAmount)})`;
                        displayAmount = origAmount * currentEnlistmentCount;
                    }

                    total += displayAmount;
                    const row = document.createElement('tr');

                    if (group === 'major') {
                        row.innerHTML = `
                            <td>${displayDesc}</td>
                            <td class="text-end">${formatMoney(displayAmount)}</td>
                            <td><button class="btn btn-ghost btn-xs text-red-500" onclick="removeFee(${f.student_fee_id})">Remove</button></td>
                        `;
                    } else if (group === 'other') {
                        row.innerHTML = `
                            <td>${displayDesc}</td>
                            <td>${f.type || ''}</td>
                            <td class="text-end">${formatMoney(displayAmount)}</td>
                            <td><button class="btn btn-ghost btn-xs text-red-500" onclick="removeFee(${f.student_fee_id})">Remove</button></td>
                        `;
                    } else {
                        row.innerHTML = `
                            <td>${displayDesc}</td>
                            <td>${f.type || ''}</td>
                            <td class="text-center">${f.month_to_pay || ''}</td>
                            <td class="text-end">${formatMoney(displayAmount)}</td>
                            <td><button class="btn btn-ghost btn-xs text-red-500" onclick="removeFee(${f.student_fee_id})">Remove</button></td>
                        `;
                    }
                    tbody.appendChild(row);
                });
                totalEl.textContent = formatMoney(total);
                tfoot.classList.remove('hidden');
            } else {
                const cols = group === 'major' ? 3 : group === 'other' ? 4 : 5;
                tbody.innerHTML = `<tr><td colspan="${cols}" class="text-center text-gray-500 py-4">No fees assigned.</td></tr>`;
                tfoot.classList.add('hidden');
            }

            // Store total for summary panel
            feeTotals[group] = total;
            updateFeeSummary();
        }

        // ── Fee Summary & Schedule ───────────────────────────
        function updateFeeSummary() {
            const majorTotal = feeTotals.major || 0;
            const otherTotal = feeTotals.other || 0;
            const additionalTotal = feeTotals.additional || 0;
            const gross = majorTotal + otherTotal + additionalTotal;

            document.getElementById('summaryMajor').textContent = formatMoney(majorTotal);
            document.getElementById('summaryOther').textContent = formatMoney(otherTotal);
            document.getElementById('summaryAdditional').textContent = formatMoney(additionalTotal);
            document.getElementById('summaryGross').textContent = formatMoney(gross);
            document.getElementById('summaryAmountToPay').textContent = formatMoney(gross);

            computeSchedule(gross);
        }

        function computeSchedule(total) {
            const downPayment = total * 0.30;
            const monthlyPay = total * 0.175;

            document.getElementById('schedDownPayment').textContent = formatMoney(downPayment);
            document.getElementById('sched1stMonth').textContent = formatMoney(monthlyPay);
            document.getElementById('sched2ndMonth').textContent = formatMoney(monthlyPay);
            document.getElementById('sched3rdMonth').textContent = formatMoney(monthlyPay);
            document.getElementById('sched4thMonth').textContent = formatMoney(monthlyPay);
            document.getElementById('schedTotal').textContent = formatMoney(total);
        }

        function recomputeSchedule() {
            const gross = (feeTotals.major || 0) + (feeTotals.other || 0) + (feeTotals.additional || 0);
            computeSchedule(gross);
        }

        // ── Remove Fee ───────────────────────────────────────
        async function removeFee(studentFeeId) {
            if (!confirm('Remove this fee?')) return;

            try {
                const response = await fetch(`${removeFeeApiUrl}/${studentFeeId}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                });
                if (!response.ok) throw new Error('Failed');
                loadStudentFees(getSelectedTermId());
            } catch (error) {
                console.error('Error removing fee:', error);
                alert('Error removing fee.');
            }
        }

        // ── Add New Fee Modal ────────────────────────────────
        function openAddNewFeeModal(group) {
            const groupLabels = { major: 'Major', other: 'Other', additional: 'Additional' };
            document.getElementById('addNewFeeModalTitle').textContent = `Add New ${groupLabels[group]} Fee`;
            document.getElementById('newFeeGroup').value = group;

            // Reset form
            document.getElementById('addNewFeeForm').reset();
            document.getElementById('newFeeGroup').value = group;

            // Show/hide fields based on group
            document.getElementById('newFeeTypeField').classList.toggle('hidden', group === 'major');
            document.getElementById('newFeeMonthsField').classList.toggle('hidden', group !== 'additional');

            addNewFeeModal.showModal();
        }

        document.getElementById('addNewFeeForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const termId = getSelectedTermId();
            if (!termId) { alert('Select an academic term first.'); return; }

            const btn = document.getElementById('addNewFeeBtn');
            const textEl = document.getElementById('addNewFeeText');
            const loadingEl = document.getElementById('addNewFeeLoading');
            btn.disabled = true;
            textEl.textContent = '';
            loadingEl.classList.remove('hidden');

            const group = document.getElementById('newFeeGroup').value;
            const body = {
                description: document.getElementById('newFeeDescription').value,
                amount: document.getElementById('newFeeAmount').value,
                group: group,
                academic_term_id: termId,
            };
            if (group !== 'major') body.type = document.getElementById('newFeeType').value || null;
            if (group === 'additional') body.months_to_pay = document.getElementById('newFeeMonths').value || null;

            try {
                const response = await fetch(createFeeApiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify(body),
                });
                if (!response.ok) throw new Error('Failed');
                addNewFeeModal.close();
                loadStudentFees(termId);
            } catch (error) {
                console.error('Error creating fee:', error);
                alert('Error creating fee.');
            } finally {
                btn.disabled = false;
                textEl.textContent = 'Add';
                loadingEl.classList.add('hidden');
            }
        });

        // ── Add Existing Fee Modal ───────────────────────────
        let currentExistingGroup = '';

        function openExistingFeesModal(group) {
            currentExistingGroup = group;
            const termId = getSelectedTermId();
            if (!termId) { alert('Select an academic term first.'); return; }

            const groupLabels = { major: 'Major', other: 'Other', additional: 'Additional' };
            document.getElementById('existingFeesModalTitle').textContent = `Add Existing ${groupLabels[group]} Fee`;
            document.getElementById('existingFeesList').innerHTML = '<div class="text-center text-gray-500 py-4"><span class="loading loading-spinner loading-sm"></span> Loading...</div>';
            existingFeesModal.showModal();

            loadExistingFees(group, termId);
        }

        async function loadExistingFees(group, termId) {
            const list = document.getElementById('existingFeesList');
            try {
                const response = await fetch(`${existingFeesApiUrl}/${studentId}/${termId}/${group}`);
                if (!response.ok) throw new Error('Failed');
                const result = await response.json();

                if (result.data && result.data.length > 0) {
                    list.innerHTML = '';
                    document.getElementById('addAllFeesBtn').classList.remove('hidden');
                    result.data.forEach(fee => {
                        const item = document.createElement('div');
                        item.className = 'flex items-center justify-between p-3 bg-base-200 rounded-lg hover:bg-base-300 cursor-pointer transition-colors';
                        item.innerHTML = `
                            <div>
                                <p class="font-medium">${fee.description}</p>
                                <p class="text-sm text-gray-500">${fee.type ? fee.type + ' · ' : ''}₱${formatMoney(fee.amount)}</p>
                            </div>
                            <button class="btn btn-primary btn-xs assign-btn" data-fee-id="${fee.id}">
                                <span class="assign-text">Add</span>
                                <span class="assign-loading loading loading-spinner loading-xs hidden"></span>
                            </button>
                        `;
                        item.querySelector('.assign-btn').addEventListener('click', async function() {
                            await assignFee(fee.id, termId, this);
                        });
                        list.appendChild(item);
                    });
                } else {
                    list.innerHTML = '<div class="text-center text-gray-500 py-4">No available fees to add.</div>';
                    document.getElementById('addAllFeesBtn').classList.add('hidden');
                }
            } catch (error) {
                console.error('Error loading existing fees:', error);
                list.innerHTML = '<div class="text-center text-red-500 py-4">Error loading fees.</div>';
                document.getElementById('addAllFeesBtn').classList.add('hidden');
            }
        }

        async function assignFee(feeId, termId, btnEl) {
            const textEl = btnEl.querySelector('.assign-text');
            const loadingEl = btnEl.querySelector('.assign-loading');
            btnEl.disabled = true;
            textEl.textContent = '';
            loadingEl.classList.remove('hidden');

            try {
                const response = await fetch(assignFeeApiUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ fee_id: feeId, academic_term_id: termId }),
                });
                if (!response.ok) throw new Error('Failed');

                // Remove item from list and refresh table
                btnEl.closest('div.flex').remove();
                loadStudentFees(termId);

                // Check if list is empty now
                const list = document.getElementById('existingFeesList');
                if (!list.querySelector('.flex')) {
                    list.innerHTML = '<div class="text-center text-gray-500 py-4">No available fees to add.</div>';
                    document.getElementById('addAllFeesBtn').classList.add('hidden');
                }
            } catch (error) {
                console.error('Error assigning fee:', error);
                alert('Error assigning fee.');
                btnEl.disabled = false;
                textEl.textContent = 'Add';
                loadingEl.classList.add('hidden');
            }
        }

        // ── Assign All Fees ──────────────────────────────────
        async function assignAllFees() {
            const termId = getSelectedTermId();
            if (!termId) return;

            const btn = document.getElementById('addAllFeesBtn');
            const textEl = document.getElementById('addAllFeesText');
            const loadingEl = document.getElementById('addAllFeesLoading');
            btn.disabled = true;
            textEl.textContent = '';
            loadingEl.classList.remove('hidden');

            const buttons = document.querySelectorAll('#existingFeesList .assign-btn');
            const feeIds = Array.from(buttons).map(b => b.dataset.feeId);

            try {
                for (const feeId of feeIds) {
                    await fetch(assignFeeApiUrl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                        body: JSON.stringify({ fee_id: feeId, academic_term_id: termId }),
                    });
                }

                document.getElementById('existingFeesList').innerHTML = '<div class="text-center text-gray-500 py-4">No available fees to add.</div>';
                btn.classList.add('hidden');
                loadStudentFees(termId);
            } catch (error) {
                console.error('Error assigning all fees:', error);
                alert('Error assigning fees.');
            } finally {
                btn.disabled = false;
                textEl.textContent = 'Add All';
                loadingEl.classList.add('hidden');
            }
        }

        // ── Update Level ─────────────────────────────────────
        async function updateLevel() {
            const levelId = document.getElementById('levelSelect').value;
            const messageEl = document.getElementById('levelUpdateMessage');
            const btn = document.getElementById('updateLevelBtn');

            btn.disabled = true;
            btn.textContent = 'Updating...';
            messageEl.textContent = '';

            try {
                const response = await fetch(`{{ route('registrar.student.update-level', $student->id) }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ level_id: levelId }),
                });

                const data = await response.json();

                if (data.success) {
                    messageEl.innerHTML = '<span class="text-green-600">Year level updated.</span>';
                } else {
                    messageEl.innerHTML = '<span class="text-red-600">Failed to update.</span>';
                }
            } catch (error) {
                console.error('Error:', error);
                messageEl.innerHTML = '<span class="text-red-600">Error updating level.</span>';
            } finally {
                btn.disabled = false;
                btn.textContent = 'Update';
            }
        }

        // ── Assessment History ─────────────────────────────────
        async function loadAssessmentHistories() {
            const tbody = document.getElementById('assessmentHistoryBody');

            tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-4"><span class="loading loading-spinner loading-sm"></span> Loading...</td></tr>';

            try {
                const response = await fetch(`${assessmentHistoriesApiUrl}/${studentId}`);
                if (!response.ok) throw new Error('Failed to fetch');
                const result = await response.json();

                tbody.innerHTML = '';

                if (result.data && result.data.length > 0) {
                    result.data.forEach(history => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${history.date_printed}</td>
                            <td>${history.academic_term}</td>
                            <td>
                                <div class="flex gap-1">
                                    <button class="btn btn-ghost btn-sm" onclick="reprintAssessment(${history.academic_term_id})" title="Reprint">
                                        print
                                    </button>
                                    <button class="btn btn-ghost btn-sm text-error" onclick="deleteAssessmentHistory(${history.id})" title="Delete">
                                        delete
                                    </button>
                                </div>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                } else {
                    tbody.innerHTML = '<tr><td colspan="3" class="text-center text-gray-500 py-4">No assessment history records.</td></tr>';
                }
            } catch (error) {
                console.error('Error loading assessment histories:', error);
                tbody.innerHTML = '<tr><td colspan="3" class="text-center text-red-500 py-4">Error loading assessment history.</td></tr>';
            }
        }

        function reprintAssessment(academicTermId) {
            window.open(printBaseUrl + '?academic_term_id=' + academicTermId + '&reprint=1', '_blank');
        }

        async function deleteAssessmentHistory(historyId) {
            if (!confirm('Are you sure you want to delete this assessment history record?')) return;

            try {
                const response = await fetch(`${assessmentHistoriesApiUrl}/${historyId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                });

                if (!response.ok) throw new Error('Failed to delete');

                loadAssessmentHistories();
            } catch (error) {
                console.error('Error deleting assessment history:', error);
                alert('Failed to delete assessment history.');
            }
        }

        // Load assessment histories on page load
        document.addEventListener('DOMContentLoaded', function () {
            loadAssessmentHistories();
        });
    </script>

</x-registrar_sidebar>
