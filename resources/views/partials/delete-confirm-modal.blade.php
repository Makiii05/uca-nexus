<!-- Universal Delete Confirmation Modal -->
<dialog id="deleteConfirmModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg text-red-600">Confirm Delete</h3>
        <p class="py-4" id="deleteConfirmMessage">Are you sure you want to delete this item?</p>
        <p class="text-sm text-gray-500">This action cannot be undone.</p>
        <div class="modal-action">
            <button type="button" class="btn" onclick="document.getElementById('deleteConfirmModal').close()">Cancel</button>
            <button type="button" class="btn btn-error" id="deleteConfirmBtn">Delete</button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    let pendingDeleteForm = null;

    function confirmDelete(form, message = 'Are you sure you want to delete this item?') {
        pendingDeleteForm = form;
        document.getElementById('deleteConfirmMessage').textContent = message;
        document.getElementById('deleteConfirmModal').showModal();
        return false;
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('deleteConfirmBtn').addEventListener('click', function() {
            if (pendingDeleteForm) {
                pendingDeleteForm.submit();
            }
        });
    });
</script>
