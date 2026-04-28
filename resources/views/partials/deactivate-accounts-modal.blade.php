<!-- Deactivate All Accounts Modal -->
<dialog id="deactivateAccountsModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4 text-error">Deactivate All Student Accounts</h3>
        <p class="text-gray-600 mb-4">This action will deactivate all student accounts. Please enter your password to confirm.</p>
        
        <div class="form-control mb-4">
            <label class="label"><span class="label-text">Password</span></label>
            <input type="password" id="deactivatePassword" class="input input-bordered w-full" placeholder="Enter your password" required>
        </div>

        <div id="deactivateError" class="text-error text-sm mb-4 hidden"></div>

        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('deactivateAccountsModal').close(); document.getElementById('deactivatePassword').value = ''; document.getElementById('deactivateError').classList.add('hidden');">Cancel</button>
            <button type="button" id="deactivateSubmitBtn" class="btn btn-error" onclick="deactivateAllAccounts()">
                <span id="deactivateSubmitText">Deactivate</span>
                <span id="deactivateSubmitLoading" class="loading loading-spinner loading-xs hidden"></span>
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>
