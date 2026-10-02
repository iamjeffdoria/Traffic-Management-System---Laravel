// Works for the create modal AND every per-franchise edit modal at once —
// called directly via onchange="syncFranchiseTricycleFields(this)" on each
// <select>, so no fixed IDs or per-modal wiring is needed.
function syncFranchiseTricycleFields(selectEl) {
    const container = selectEl.closest('[data-franchise-form]');
    if (!container) return;

    const selected = selectEl.options[selectEl.selectedIndex];

    const nameDisplay = container.querySelector('[data-franchise-name-display]');
    const plateDisplay = container.querySelector('[data-franchise-plate-display]');
    const motorDisplay = container.querySelector('[data-franchise-motor-display]');
    const chassisDisplay = container.querySelector('[data-franchise-chassis-display]');

    if (nameDisplay) nameDisplay.value = selected.dataset.name || '';
    if (plateDisplay) plateDisplay.value = selected.dataset.plate || '';
    if (motorDisplay) motorDisplay.value = selected.dataset.motor || '';
    if (chassisDisplay) chassisDisplay.value = selected.dataset.chassis || '';
}
window.syncFranchiseTricycleFields = syncFranchiseTricycleFields;

function onFranchiseSearchSelect(optionEl, root) {
    const container = root.closest('[data-franchise-form]');
    if (!container) return;

    const nameDisplay = container.querySelector('[data-franchise-name-display]');
    const plateDisplay = container.querySelector('[data-franchise-plate-display]');
    const motorDisplay = container.querySelector('[data-franchise-motor-display]');
    const chassisDisplay = container.querySelector('[data-franchise-chassis-display]');

    if (nameDisplay) nameDisplay.value = optionEl.dataset.name || '';
    if (plateDisplay) plateDisplay.value = optionEl.dataset.plate || '';
    if (motorDisplay) motorDisplay.value = optionEl.dataset.motor || '';
    if (chassisDisplay) chassisDisplay.value = optionEl.dataset.chassis || '';
}
window.onFranchiseSearchSelect = onFranchiseSearchSelect;


// Makes "Renewed By" required (native browser message) only while status = Renewed.
function syncRenewedByRequired(container) {
    if (!container) return;

    const status = container.querySelector('[name="status"]');
    const renewedBy = container.querySelector('[name="renewed_by"]');
    const star = container.querySelector('[data-renewed-by-required]');
    if (!status || !renewedBy) return;

    const isRenewed = status.value === 'Renewed';
    renewedBy.required = isRenewed;
    if (star) star.classList.toggle('hidden', !isRenewed);
}
window.syncRenewedByRequired = syncRenewedByRequired;

// Delegated, so it keeps working after the AJAX table refresh replaces the edit modal.
document.addEventListener('change', (event) => {
    if (!event.target.matches('[data-franchise-form] [name="status"]')) return;
    syncRenewedByRequired(event.target.closest('[data-franchise-form]'));
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-franchise-form]').forEach(syncRenewedByRequired);
});