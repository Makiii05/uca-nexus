<x-accounting_sidebar>

    @include('partials.notifications')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="flex items-center justify-between mb-4 px-4">
        <h2 class="font-bold text-4xl">Cashier</h2>
        <button class="btn btn-primary btn-sm" onclick="document.getElementById('printDateModal').showModal()">
            Print Daily Report
        </button>
    </div>

    <!-- Search Section -->
    <div class="flex justify-end gap-2 mb-4 px-4">
        <input 
            type="text" 
            id="studentSearchInput" 
            placeholder="Search students by name, student number, program, etc..." 
            class="input input-bordered w-96"
        >
    </div>

    <!--TABLE-->
    <div data-table-wrapper>
    <!-- Hidden search input to prevent auto-injection -->
    <input type="hidden" data-table-search>
    <div class="overflow-x-auto bg-white shadow">
        <table class="table" data-sortable-table>
            <thead>
                <tr>
                    <th>Student No.</th>
                    <th>Full Name</th>
                    <th>Department</th>
                    <th>Program</th>
                    <th>Level</th>
                    <th>Account Status</th>
                    <th data-no-sort>Action</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <tr id="initialMessage">
                    <td colspan="7" class="text-center text-gray-500 py-8">Enter a search term to find students.</td>
                </tr>
            </tbody>
        </table>
    </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('studentSearchInput');
        const tableBody = document.getElementById('studentTableBody');
        let debounceTimer;

        function performSearch() {
            const searchTerm = searchInput.value.trim();
            
            if (!searchTerm) {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-500 py-8">Enter a search term to find students.</td></tr>';
                return;
            }

            tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-500 py-8"><span class="loading loading-spinner loading-sm"></span> Searching...</td></tr>';

            fetch(`{{ route('accounting.api.students.search') }}?search=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-gray-500 py-8">No students found.</td></tr>';
                        return;
                    }

                    tableBody.innerHTML = data.data.map(student => {
                        const accountStatus = student.account?.account_status || 'off';
                        const isAccountOn = accountStatus === 'on';
                        const buttonText = isAccountOn ? 'Close Account' : 'Open Account';
                        const buttonClass = isAccountOn ? 'btn-warning' : 'btn-success';
                        const statusBadge = isAccountOn 
                            ? '<span class="badge badge-success">On</span>' 
                            : '<span class="badge badge-error">Off</span>';
                        
                        return `
                            <tr data-student-id="${student.id}">
                                <td>${student.student_number}</td>
                                <td>${student.last_name}, ${student.first_name} ${student.middle_name || ''}</td>
                                <td>${student.department?.code || '-'}</td>
                                <td>${student.program?.code || '-'}</td>
                                <td>${student.level?.description || '-'}</td>
                                <td class="account-status-cell">${statusBadge}</td>
                                <td>
                                    <div class="flex gap-1">
                                        <a href="/accounting/payment/${student.id}" class="btn btn-sm btn-primary">Pay</a>
                                        <button class="btn btn-sm ${buttonClass} account-toggle-btn" onclick="toggleAccountStatus(${student.account?.id || 0}, ${student.id})" ${!student.account ? 'disabled' : ''}>
                                            ${buttonText}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }).join('');
                })
                .catch(error => {
                    console.error('Search error:', error);
                    tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-red-500 py-8">Error searching students. Please try again.</td></tr>';
                });
        }

        // Search on input change with debounce
        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(performSearch, 300);
        });
    });

    async function toggleAccountStatus(accountId, studentId) {
        if (!accountId) {
            showToast('No account found for this student.', 'error');
            return;
        }

        try {
            const response = await fetch(`/accounting/api/student-accounts/${accountId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (data.success) {
                // Update the UI
                const row = document.querySelector(`tr[data-student-id="${studentId}"]`);
                if (row) {
                    const statusCell = row.querySelector('.account-status-cell');
                    const toggleBtn = row.querySelector('.account-toggle-btn');
                    
                    if (data.status === 'on') {
                        statusCell.innerHTML = '<span class="badge badge-success">On</span>';
                        toggleBtn.textContent = 'Close Account';
                        toggleBtn.classList.remove('btn-success');
                        toggleBtn.classList.add('btn-warning');
                    } else {
                        statusCell.innerHTML = '<span class="badge badge-error">Off</span>';
                        toggleBtn.textContent = 'Open Account';
                        toggleBtn.classList.remove('btn-warning');
                        toggleBtn.classList.add('btn-success');
                    }
                }
            }
        } catch (error) {
            console.error('Error toggling account status:', error);
            showToast('Error toggling account status. Please try again.', 'error');
        }
    }
    </script>

    @include('partials.table-sort-search')

    @include('partials.cashier-print-modal')

</x-accounting_sidebar>
