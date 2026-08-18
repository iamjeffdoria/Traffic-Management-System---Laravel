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