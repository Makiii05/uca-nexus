<!-- Clear All Examination Permits Modal -->
<dialog id="clearExamPermitsModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4 text-warning">Clear All Examination Permits</h3>
        <p class="text-gray-600 mb-4">This action will clear all examination permits. Please enter your password to confirm.</p>
        
        <div class="form-control mb-4">
            <label class="label"><span class="label-text">Password</span></label>
            <input type="password" id="clearPermitsPassword" class="input input-bordered w-full" placeholder="Enter your password" required>
        </div>

        <div id="clearPermitsError" class="text-error text-sm mb-4 hidden"></div>

        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('clearExamPermitsModal').close(); document.getElementById('clearPermitsPassword').value = ''; document.getElementById('clearPermitsError').classList.add('hidden');">Cancel</button>
            <button type="button" id="clearPermitsSubmitBtn" class="btn btn-warning" onclick="clearAllExamPermits()">
                <span id="clearPermitsSubmitText">Clear All</span>
                <span id="clearPermitsSubmitLoading" class="loading loading-spinner loading-xs hidden"></span>
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>
