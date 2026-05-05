<x-accounting_sidebar>

    @include('partials.notifications')

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="m-4 font-bold text-4xl">
        <h2>Dashboard</h2>
    </div>

    <div class="m-4 grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch">
        <!-- Student Portal Status Toggle -->
        <div class="card bg-white shadow-lg h-full">
            <div class="card-body h-full">
                <div class="flex flex-col gap-3 justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Student Portal Status</h2>
                        <p class="text-sm text-gray-500">Toggle to enable or disable student portal access</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span id="student-portal-status-label" class="badge {{ $studentPortalStatus === 'on' ? 'badge-success' : 'badge-error' }} badge-lg">
                            {{ ucfirst($studentPortalStatus) }}
                        </span>
                        <input type="checkbox" 
                               id="student-portal-toggle" 
                               class="toggle toggle-success toggle-lg" 
                               {{ $studentPortalStatus === 'on' ? 'checked' : '' }}
                               onchange="toggleStudentPortalStatus()" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Deactivate Students Accounts Widget -->
        <div class="card bg-white shadow-lg h-full">
            <div class="card-body h-full">
                <div class="flex flex-col gap-3 justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Deactivate All Student Accounts</h2>
                        <p class="text-sm text-gray-500">Deactivate all student accounts at once. This action requires password verification.</p>
                    </div>
                    <div>
                        <button class="btn btn-error" onclick="document.getElementById('deactivateAccountsModal').showModal()">
                            Deactivate All Accounts
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clear All Examination Permits Widget -->
        <div class="card bg-white shadow-lg h-full">
            <div class="card-body h-full">
                <div class="flex flex-col gap-3 justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Clear All Examination Permits</h2>
                        <p class="text-sm text-gray-500">Clear all examination permits at once. This action requires password verification.</p>
                    </div>
                    <div>
                        <button class="btn btn-warning" onclick="document.getElementById('clearExamPermitsModal').showModal()">
                            Clear All Permits
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        async function toggleStudentPortalStatus() {
            const toggle = document.getElementById('student-portal-toggle');
            const label = document.getElementById('student-portal-status-label');
            
            try {
                const response = await fetch('{{ route("accounting.api.student-portal-status.toggle") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                });
                
                const data = await response.json();
                
                // Update the label
                label.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                label.className = data.is_on 
                    ? 'badge badge-success badge-lg' 
                    : 'badge badge-error badge-lg';
                    
            } catch (error) {
                console.error('Error toggling student portal status:', error);
                // Revert toggle if error
                toggle.checked = !toggle.checked;
            }
        }

        async function deactivateAllAccounts() {
            const passwordInput = document.getElementById('deactivatePassword');
            const submitBtn = document.getElementById('deactivateSubmitBtn');
            const submitText = document.getElementById('deactivateSubmitText');
            const submitLoading = document.getElementById('deactivateSubmitLoading');
            const errorDiv = document.getElementById('deactivateError');

            const password = passwordInput.value;
            if (!password) {
                errorDiv.textContent = 'Please enter your password.';
                errorDiv.classList.remove('hidden');
                return;
            }

            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            errorDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route("accounting.api.student-accounts.deactivate-all") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ password: password }),
                });

                const data = await response.json();

                if (!response.ok) {
                    errorDiv.textContent = data.message || 'An error occurred.';
                    errorDiv.classList.remove('hidden');
                } else {
                    document.getElementById('deactivateAccountsModal').close();
                    passwordInput.value = '';
                    showToast(data.message, 'success');
                }
            } catch (error) {
                console.error('Error deactivating accounts:', error);
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        }

        async function clearAllExamPermits() {
            const passwordInput = document.getElementById('clearPermitsPassword');
            const submitBtn = document.getElementById('clearPermitsSubmitBtn');
            const submitText = document.getElementById('clearPermitsSubmitText');
            const submitLoading = document.getElementById('clearPermitsSubmitLoading');
            const errorDiv = document.getElementById('clearPermitsError');

            const password = passwordInput.value;
            if (!password) {
                errorDiv.textContent = 'Please enter your password.';
                errorDiv.classList.remove('hidden');
                return;
            }

            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoading.classList.remove('hidden');
            errorDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route("accounting.api.examination-permits.clear-all") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ password: password }),
                });

                const data = await response.json();

                if (!response.ok) {
                    errorDiv.textContent = data.message || 'An error occurred.';
                    errorDiv.classList.remove('hidden');
                } else {
                    document.getElementById('clearExamPermitsModal').close();
                    passwordInput.value = '';
                    showToast(data.message, 'success');
                }
            } catch (error) {
                console.error('Error clearing examination permits:', error);
                errorDiv.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitLoading.classList.add('hidden');
            }
        }
    </script>

    @include('partials.deactivate-accounts-modal')
    @include('partials.clear-exam-permits-modal')

</x-accounting_sidebar>
