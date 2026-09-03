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