<!-- Delete Confirmation Modal -->
<dialog id="deleteConfirmModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Confirm Delete</h3>
        <p class="py-4">Are you sure you want to delete the selected applicant(s)?</p>
        <p class="text-sm text-gray-500">Note: Only applicants who are not yet admitted will be deleted.</p>
        <div class="modal-action">
            <button type="button" class="btn" onclick="document.getElementById('deleteConfirmModal').close()">Cancel</button>
            <button type="button" class="btn btn-error" onclick="submitDelete()">Delete</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Single Delete Confirmation Modal -->
<dialog id="singleDeleteConfirmModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Confirm Delete</h3>
        <p class="py-4">Are you sure you want to delete <span id="singleDeleteName" class="font-semibold"></span>?</p>
        <p class="text-sm text-gray-500">This action cannot be undone.</p>
        <div class="modal-action">
            <button type="button" class="btn" onclick="document.getElementById('singleDeleteConfirmModal').close()">Cancel</button>
            <button type="button" class="btn btn-error" onclick="submitSingleDelete()">Delete</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<!-- Single Delete Form -->
<form id="singleDeleteForm" action="{{ route('admission.applicant.delete') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="applicant_ids[]" id="singleDeleteId">
</form>
