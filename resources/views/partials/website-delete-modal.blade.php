<!-- Website Delete Confirmation Modal -->
<dialog id="websiteDeleteModal" class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg text-red-600">Confirm Delete</h3>
        <p class="py-4" id="websiteDeleteMessage">Are you sure you want to delete this content?</p>
        <p class="text-sm text-gray-500">This action cannot be undone.</p>
        <form id="websiteDeleteForm" method="POST" action="">
            @csrf
            <div class="modal-action">
                <button type="button" class="btn" onclick="document.getElementById('websiteDeleteModal').close()">Cancel</button>
                <button type="submit" class="btn btn-error">Delete</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
    function confirmWebsiteDelete(id, title) {
        const displayTitle = title ? `"${title}"` : 'this content';
        document.getElementById('websiteDeleteMessage').textContent = `Are you sure you want to delete ${displayTitle}?`;
        document.getElementById('websiteDeleteForm').action = "{{ url('admin/website') }}/" + id + "/delete";
        document.getElementById('websiteDeleteModal').showModal();
    }
</script>
