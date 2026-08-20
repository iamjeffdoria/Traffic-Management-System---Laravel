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

let pendingMtopDeleteForm = null;

function confirmMtopDelete(formId, name) {
    pendingMtopDeleteForm = document.getElementById(formId);

    const nameEl = document.getElementById('delete-mtop-confirm-name');
    if (nameEl) nameEl.textContent = name;

    openModal('delete-mtop-confirm-modal');
}
window.confirmMtopDelete = confirmMtopDelete;

function submitPendingMtopDelete() {
    if (pendingMtopDeleteForm) {
        pendingMtopDeleteForm.submit();
        pendingMtopDeleteForm = null;
    }
}
window.submitPendingMtopDelete = submitPendingMtopDelete;

let pendingPermitDeleteForm = null;

function confirmPermitDelete(formId, name) {
    pendingPermitDeleteForm = document.getElementById(formId);

    const nameEl = document.getElementById('delete-permit-confirm-name');
    if (nameEl) nameEl.textContent = name;

    openModal('delete-permit-confirm-modal');
}
window.confirmPermitDelete = confirmPermitDelete;

function submitPendingPermitDelete() {
    if (pendingPermitDeleteForm) {
        pendingPermitDeleteForm.submit();
        pendingPermitDeleteForm = null;
    }
}
window.submitPendingPermitDelete = submitPendingPermitDelete;

let pendingFranchiseDeleteForm = null;

function confirmFranchiseDelete(formId, name) {
    pendingFranchiseDeleteForm = document.getElementById(formId);

    const nameEl = document.getElementById('delete-franchise-confirm-name');
    if (nameEl) nameEl.textContent = name;

    openModal('delete-franchise-confirm-modal');
}
window.confirmFranchiseDelete = confirmFranchiseDelete;

function submitPendingFranchiseDelete() {
    if (pendingFranchiseDeleteForm) {
        pendingFranchiseDeleteForm.submit();
        pendingFranchiseDeleteForm = null;
    }
}
window.submitPendingFranchiseDelete = submitPendingFranchiseDelete;

let pendingPotpotPermitDeleteForm = null;

function confirmPotpotPermitDelete(formId, name) {
    pendingPotpotPermitDeleteForm = document.getElementById(formId);

    const nameEl = document.getElementById('delete-potpot-permit-confirm-name');
    if (nameEl) nameEl.textContent = name;

    openModal('delete-potpot-permit-confirm-modal');
}
window.confirmPotpotPermitDelete = confirmPotpotPermitDelete;

function submitPendingPotpotPermitDelete() {
    if (pendingPotpotPermitDeleteForm) {
        pendingPotpotPermitDeleteForm.submit();
        pendingPotpotPermitDeleteForm = null;
    }
}
window.submitPendingPotpotPermitDelete = submitPendingPotpotPermitDelete;