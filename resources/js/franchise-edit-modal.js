function openFranchiseEditModal(franchise) {
    const form = document.getElementById('franchise-edit-form');
    if (!form) return;

    const urlTemplate = form.dataset.updateUrlTemplate;
    form.action = urlTemplate.replace('__ID__', franchise.id);

    const searchRoot = form.querySelector('[data-searchable-select]');
    if (searchRoot) {
        const hidden = searchRoot.querySelector('[data-search-hidden]');
        const input = searchRoot.querySelector('[data-search-input]');
        const option = searchRoot.querySelector(`[data-option][data-id="${franchise.tricycle_id}"]`);

        hidden.value = franchise.tricycle_id ?? '';
        input.value = option ? option.dataset.label : (franchise.tricycle_name ?? '');
        input.classList.remove('border-red-600', 'ring-2', 'ring-red-600');
    }

    form.querySelector('[data-franchise-name-display]').value = franchise.tricycle_name ?? '';
    form.querySelector('[data-franchise-plate-display]').value = franchise.tricycle_plate ?? '';
    form.querySelector('[data-franchise-motor-display]').value = franchise.tricycle_motor ?? '';
    form.querySelector('[data-franchise-chassis-display]').value = franchise.tricycle_chassis ?? '';

    form.querySelector('[name="valid_until"]').value = franchise.valid_until ?? '';
    form.querySelector('[name="denomination"]').value = franchise.denomination ?? '';
    form.querySelector('[name="status"]').value = franchise.status ?? 'New';
    form.querySelector('[name="authorized_no"]').value = franchise.authorized_no ?? '';
    form.querySelector('[name="authorized_route"]').value = franchise.authorized_route ?? '';
    form.querySelector('[name="purpose"]').value = franchise.purpose ?? '';
    form.querySelector('[name="official_receipt_no"]').value = franchise.official_receipt_no ?? '';
    form.querySelector('[name="amount_paid"]').value = franchise.amount_paid ?? '';
    form.querySelector('[name="date"]').value = franchise.date ?? '';
    form.querySelector('[name="municipal_treasurer"]').value = franchise.municipal_treasurer ?? '';

    openModal('edit-franchise-modal');
}

window.openFranchiseEditModal = openFranchiseEditModal;