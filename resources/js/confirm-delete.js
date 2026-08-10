let pendingDeleteForm = null;

function confirmDelete(formId, name) {
    pendingDeleteForm = document.getElementById(formId);

    const nameEl = document.getElementById('delete-confirm-name');
    if (nameEl) nameEl.textContent = name;

    openModal('delete-confirm-modal');
}
window.confirmDelete = confirmDelete;

function submitPendingDelete() {
    if (pendingDeleteForm) {
        pendingDeleteForm.submit();
        pendingDeleteForm = null;
    }
}
window.submitPendingDelete = submitPendingDelete;