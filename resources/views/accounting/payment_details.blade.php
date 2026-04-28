<x-accounting_sidebar>

    @include('partials.notifications')

    <div class="flex items-center gap-4 mb-4">
        <h2 class="font-bold text-4xl">Payment Details</h2>
    </div>

    <div class="flex gap-5">
        <!-- LEFT: Payment Accounts -->
        <div class="w-1/2">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg">Payment Accounts</h3>
                    <button class="btn btn-primary btn-sm" onclick="document.getElementById('addAccountModal').showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add
                    </button>
                </div>
                <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                    <table class="table table-zebra table-sm">
                        <thead class="sticky top-0 bg-base-200">
                            <tr>
                                <th class="w-12">#</th>
                                <th>Description</th>
                                <th class="w-24">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paymentAccounts as $index => $account)
                                <tr>
                                    <td>{{ $paymentAccounts->firstItem() + $index }}</td>
                                    <td>{{ $account->description }}</td>
                                    <td>
                                        <div class="flex gap-1">
                                            <button class="btn btn-ghost btn-xs" onclick="openEditAccountModal({{ $account->id }}, '{{ addslashes($account->description) }}')">Edit</button>
                                            <form action="{{ route('accounting.payment_accounts.delete', $account->id) }}" method="POST" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this payment account?')">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-xs text-red-500">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-gray-500 py-4">No payment accounts found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $paymentAccounts->withQueryString()->links() }}
                </div>
            </div>
        </div>

        <!-- RIGHT: Payment Types -->
        <div class="w-1/2">
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-semibold text-lg">Payment Types</h3>
                    <button class="btn btn-primary btn-sm" onclick="document.getElementById('addTypeModal').showModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        Add
                    </button>
                </div>
                <div class="overflow-x-auto max-h-[600px] overflow-y-auto">
                    <table class="table table-zebra table-sm">
                        <thead class="sticky top-0 bg-base-200">
                            <tr>
                                <th class="w-12">#</th>
                                <th>Description</th>
                                <th class="w-24">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($paymentTypes as $index => $type)
                                <tr>
                                    <td>{{ $paymentTypes->firstItem() + $index }}</td>
                                    <td>{{ $type->description }}</td>
                                    <td>
                                        <div class="flex gap-1">
                                            <button class="btn btn-ghost btn-xs" onclick="openEditTypeModal({{ $type->id }}, '{{ addslashes($type->description) }}')">Edit</button>
                                            <form action="{{ route('accounting.payment_types.delete', $type->id) }}" method="POST" onsubmit="return confirmDelete(this, 'Are you sure you want to delete this payment type?')">
                                                @csrf
                                                <button type="submit" class="btn btn-ghost btn-xs text-red-500">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-gray-500 py-4">No payment types found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $paymentTypes->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Account Modal -->
    <dialog id="addAccountModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add Payment Account</h3>
            <form action="{{ route('accounting.payment_accounts.store') }}" method="POST">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <input type="text" name="description" class="input input-bordered w-full" required>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('addAccountModal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <!-- Edit Account Modal -->
    <dialog id="editAccountModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Payment Account</h3>
            <form id="editAccountForm" method="POST">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <input type="text" name="description" id="editAccountDescription" class="input input-bordered w-full" required>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('editAccountModal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <!-- Add Type Modal -->
    <dialog id="addTypeModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add Payment Type</h3>
            <form action="{{ route('accounting.payment_types.store') }}" method="POST">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <input type="text" name="description" class="input input-bordered w-full" required>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('addTypeModal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <!-- Edit Type Modal -->
    <dialog id="editTypeModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Payment Type</h3>
            <form id="editTypeForm" method="POST">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Description</span></label>
                    <input type="text" name="description" id="editTypeDescription" class="input input-bordered w-full" required>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('editTypeModal').close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    @include('partials.delete-confirm-modal')

    <script>
        function openEditAccountModal(id, description) {
            document.getElementById('editAccountDescription').value = description;
            document.getElementById('editAccountForm').action = '{{ url("/accounting/payment-accounts") }}/' + id + '/update';
            document.getElementById('editAccountModal').showModal();
        }

        function openEditTypeModal(id, description) {
            document.getElementById('editTypeDescription').value = description;
            document.getElementById('editTypeForm').action = '{{ url("/accounting/payment-types") }}/' + id + '/update';
            document.getElementById('editTypeModal').showModal();
        }
    </script>

</x-accounting_sidebar>
