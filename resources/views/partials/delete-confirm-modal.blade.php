@once
    <dialog id="appConfirmModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg" id="appConfirmTitle">Confirm Action</h3>
            <p class="py-4" id="appConfirmMessage">Are you sure you want to continue?</p>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" id="appConfirmCancel">Cancel</button>
                <button type="button" class="btn btn-primary" id="appConfirmOk">Confirm</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <script>
        (function () {
            if (window.__confirmModalReady) return;
            window.__confirmModalReady = true;

            const modal = document.getElementById('appConfirmModal');
            const titleEl = document.getElementById('appConfirmTitle');
            const messageEl = document.getElementById('appConfirmMessage');
            const okBtn = document.getElementById('appConfirmOk');
            const cancelBtn = document.getElementById('appConfirmCancel');

            let resolvePromise = null;

            function closeModal(result) {
                if (resolvePromise) {
                    const resolve = resolvePromise;
                    resolvePromise = null;
                    resolve(result);
                }
                if (modal && modal.open) {
                    modal.close();
                }
            }

            function confirmDialog(message, options = {}) {
                if (!modal || !titleEl || !messageEl || !okBtn || !cancelBtn) {
                    return Promise.resolve(window.confirm(String(message ?? '')));
                }

                const title = options.title || 'Confirm Action';
                const confirmText = options.confirmText || 'Confirm';
                const confirmClass = options.confirmClass || 'btn-primary';

                titleEl.textContent = title;
                messageEl.textContent = String(message ?? '');
                okBtn.textContent = confirmText;
                okBtn.className = `btn ${confirmClass}`;

                return new Promise((resolve) => {
                    resolvePromise = resolve;
                    modal.showModal();
                });
            }

            function confirmAction(message, onConfirm, options = {}) {
                confirmDialog(message, options).then((confirmed) => {
                    if (confirmed && typeof onConfirm === 'function') {
                        onConfirm();
                    }
                });
            }

            function confirmSubmit(form, message, options = {}) {
                confirmAction(message, () => form?.submit(), options);
                return false;
            }

            function confirmDelete(form, message = 'Are you sure you want to delete this item?') {
                return confirmSubmit(form, message, {
                    title: 'Confirm Delete',
                    confirmText: 'Delete',
                    confirmClass: 'btn-error'
                });
            }

            okBtn.addEventListener('click', () => closeModal(true));
            cancelBtn.addEventListener('click', () => closeModal(false));
            modal.addEventListener('close', () => closeModal(false));

            window.confirmDialog = confirmDialog;
            window.confirmAction = confirmAction;
            window.confirmSubmit = confirmSubmit;
            window.confirmDelete = confirmDelete;
        })();
    </script>
@endonce
