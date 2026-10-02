// Works for the create modal AND every per-permit edit modal at once —
// called directly via onchange="syncPermitTricycleFields(this)" on each
// <select>, so no fixed IDs or per-modal wiring is needed.
function syncPermitTricycleFields(selectEl) {
    const container = selectEl.closest('[data-permit-form]');
    if (!container) return;

    const selected = selectEl.options[selectEl.selectedIndex];
    const nameDisplay = container.querySelector('[data-permit-name-display]');
    const addressDisplay = container.querySelector('[data-permit-address-display]');

    if (nameDisplay) nameDisplay.value = selected.dataset.name || '';
    if (addressDisplay) addressDisplay.value = selected.dataset.address || '';
}
window.syncPermitTricycleFields = syncPermitTricycleFields;

function onTricycleSearchSelect(optionEl, root) {
    const container = root.closest('[data-permit-form]');
    if (!container) return;

    const nameDisplay = container.querySelector('[data-permit-name-display]');
    const addressDisplay = container.querySelector('[data-permit-address-display]');

    if (nameDisplay) nameDisplay.value = optionEl.dataset.name || '';
    if (addressDisplay) addressDisplay.value = optionEl.dataset.address || '';
}
window.onTricycleSearchSelect = onTricycleSearchSelect;

// Makes "Renewed By" required (native browser message) only while status = renewed.
function syncPermitRenewedByRequired(container) {
    if (!container) return;

    const status = container.querySelector('[name="status"]');
    const renewedBy = container.querySelector('[name="renewed_by"]');
    const star = container.querySelector('[data-renewed-by-required]');
    if (!status || !renewedBy) return;

    const isRenewed = status.value === 'renewed';
    renewedBy.required = isRenewed;
    if (star) star.classList.toggle('hidden', !isRenewed);
}
window.syncPermitRenewedByRequired = syncPermitRenewedByRequired;

// Delegated, so it keeps working after the AJAX refresh replaces the edit modal.
document.addEventListener('change', (event) => {
    if (!event.target.matches('[data-permit-form] [name="status"]')) return;
    syncPermitRenewedByRequired(event.target.closest('[data-permit-form]'));
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-permit-form]').forEach(syncPermitRenewedByRequired);
});

function openTricycleMayorsPermitEditModal(permit) {
    const form = document.getElementById('tricycle-mayors-permit-edit-form');
    if (!form) return;

    const urlTemplate = form.dataset.updateUrlTemplate;
    form.action = urlTemplate.replace('__ID__', permit.id);

    const searchRoot = form.querySelector('[data-searchable-select]');
    if (searchRoot) {
        const hidden = searchRoot.querySelector('[data-search-hidden]');
        const input = searchRoot.querySelector('[data-search-input]');
        const option = searchRoot.querySelector(`[data-option][data-id="${permit.tricycle_id}"]`);

        hidden.value = permit.tricycle_id ?? '';
        input.value = option ? option.dataset.label : (permit.tricycle_name ?? '');
        input.classList.remove('border-red-600', 'ring-2', 'ring-red-600');
    }

    form.querySelector('[data-permit-name-display]').value = permit.tricycle_name ?? '';
    form.querySelector('[data-permit-address-display]').value = permit.tricycle_address ?? '';

    form.querySelector('[name="control_no"]').value = permit.control_no ?? '';
    form.querySelector('[name="status"]').value = permit.status ?? 'active';
    form.querySelector('[name="business_name"]').value = permit.business_name ?? '';
    form.querySelector('[name="motorized_operation"]').value = permit.motorized_operation ?? '';
    form.querySelector('[name="or_no"]').value = permit.or_no ?? '';
    form.querySelector('[name="amount_paid"]').value = permit.amount_paid ?? '';
    form.querySelector('[name="issue_date"]').value = permit.issue_date ?? '';
    form.querySelector('[name="expiry_date"]').value = permit.expiry_date ?? '';
    form.querySelector('[name="issued_at"]').value = permit.issued_at ?? '';
    form.querySelector('[name="mayor"]').value = permit.mayor ?? '';
    form.querySelector('[name="quarter"]').value = permit.quarter ?? '';
    form.querySelector('[name="renewed_by"]').value = permit.renewed_by ?? '';
    syncPermitRenewedByRequired(form);

    openModal('edit-tricycle-mayors-permit-modal');
}
window.openTricycleMayorsPermitEditModal = openTricycleMayorsPermitEditModal;