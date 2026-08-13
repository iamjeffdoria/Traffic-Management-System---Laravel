function syncTricycleMayorsPermitFields() {
    const select = document.getElementById('tricycle-mayors-permit-select');
    const nameDisplay = document.getElementById('tricycle-mayors-permit-name-display');
    const addressDisplay = document.getElementById('tricycle-mayors-permit-address-display');

    if (!select || !nameDisplay || !addressDisplay) return;

    select.addEventListener('change', () => {
        const selected = select.options[select.selectedIndex];
        nameDisplay.value = selected.dataset.name || '';
        addressDisplay.value = selected.dataset.address || '';
    });
}

document.addEventListener('DOMContentLoaded', syncTricycleMayorsPermitFields);
window.syncTricycleMayorsPermitFields = syncTricycleMayorsPermitFields;