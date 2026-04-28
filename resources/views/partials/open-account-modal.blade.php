<!-- Open Student Account Modal -->
<dialog id="openAccountModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Open Student Account</h3>
        <p class="text-gray-600 mb-4">This student's account is currently closed. Do you want to open the account to proceed with the downpayment transaction?</p>
        
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="closeOpenAccountModal()">Close</button>
            <button type="button" class="btn btn-success" onclick="openAccountAndSubmit()">Open Account</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>
