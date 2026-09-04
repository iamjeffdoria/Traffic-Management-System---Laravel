function openMtopEditModal(mtop) {
    const form = document.getElementById('mtop-edit-form');
    if (!form) return;

    const urlTemplate = form.dataset.updateUrlTemplate;
    form.action = urlTemplate.replace('__ID__', mtop.id);

    const searchRoot = form.querySelector('[data-searchable-select]');
    if (searchRoot) {
        const hidden = searchRoot.querySelector('[data-search-hidden]');
        const input = searchRoot.querySelector('[data-search-input]');
        const option = searchRoot.querySelector(`[data-option][data-id="${mtop.tricycle_id}"]`);

        hidden.value = mtop.tricycle_id ?? '';
        input.value = option ? option.dataset.label : (mtop.tricycle_name ?? '');
        input.classList.remove('border-red-600', 'ring-2', 'ring-red-600');
    }

    form.querySelector('[data-mtop-name-display]').value = mtop.tricycle_name ?? '';
    form.querySelector('[data-mtop-address-display]').value = mtop.tricycle_address ?? '';
    form.querySelector('[data-mtop-make-display]').value = mtop.tricycle_make ?? '';
    form.querySelector('[data-mtop-motor-display]').value = mtop.tricycle_motor ?? '';
    form.querySelector('[data-mtop-chassis-display]').value = mtop.tricycle_chassis ?? '';
    form.querySelector('[data-mtop-plate-display]').value = mtop.tricycle_plate ?? '';

    form.querySelector('[name="case_no"]').value = mtop.case_no ?? '';
    form.querySelector('[name="no_of_units"]').value = mtop.no_of_units ?? '';
    form.querySelector('[name="route_operation"]').value = mtop.route_operation ?? '';
    form.querySelector('[name="date"]').value = mtop.date ?? '';
    form.querySelector('[name="municipal_treasurer"]').value = mtop.municipal_treasurer ?? '';
    form.querySelector('[name="officer_in_charge"]').value = mtop.officer_in_charge ?? '';
    form.querySelector('[name="mayor"]').value = mtop.mayor ?? '';

    openModal('edit-mtop-modal');
}

window.openMtopEditModal = openMtopEditModal;