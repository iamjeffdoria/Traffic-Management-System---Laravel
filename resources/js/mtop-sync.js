// Same pattern as tricycle-mayors-permit-sync.js — data-attribute based
// so it works for the create modal now and any future edit modals later
// without needing fixed per-modal IDs.
function syncMtopTricycleFields(selectEl) {
    const container = selectEl.closest('[data-mtop-form]');
    if (!container) return;

    const selected = selectEl.options[selectEl.selectedIndex];

    const fieldMap = {
        name: 'data-mtop-name-display',
        address: 'data-mtop-address-display',
        make: 'data-mtop-make-display',
        motor: 'data-mtop-motor-display',
        chassis: 'data-mtop-chassis-display',
        plate: 'data-mtop-plate-display',
    };

    Object.entries(fieldMap).forEach(([dataKey, attr]) => {
        const field = container.querySelector(`[${attr}]`);
        if (field) field.value = selected.dataset[dataKey] || '';
    });
}
window.syncMtopTricycleFields = syncMtopTricycleFields;

function onMtopSearchSelect(optionEl, root) {
    const container = root.closest('[data-mtop-form]');
    if (!container) return;

    const fieldMap = {
        name: 'data-mtop-name-display',
        address: 'data-mtop-address-display',
        make: 'data-mtop-make-display',
        motor: 'data-mtop-motor-display',
        chassis: 'data-mtop-chassis-display',
        plate: 'data-mtop-plate-display',
    };

    Object.entries(fieldMap).forEach(([dataKey, attr]) => {
        const field = container.querySelector(`[${attr}]`);
        if (field) field.value = optionEl.dataset[dataKey] || '';
    });
}
window.onMtopSearchSelect = onMtopSearchSelect;