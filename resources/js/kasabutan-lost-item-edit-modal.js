function openKasabutanLostItemEditModal(item) {
    const form = document.getElementById('kasabutan-lost-item-edit-form');
    if (!form) return;

    form.action = form.dataset.updateUrlTemplate.replace('__ID__', item.id);

    // The picker only helps fill fields, so start it empty for every record.
    const searchRoot = form.querySelector('[data-searchable-select]');
    if (searchRoot) {
        searchRoot.querySelector('[data-search-hidden]').value = '';
        searchRoot.querySelector('[data-search-input]').value = '';
    }

    const fields = [
        'driver', 'body_number', 'driver_age', 'driver_address', 'amount',
        'passenger', 'passenger_age', 'passenger_address',
        'date', 'time', 'issued_at',
        'witness_1', 'witness_2', 'witness_type', 'it_section', 'security_officer',
    ];

    fields.forEach((name) => {
        const input = form.querySelector(`[name="${name}"]`);
        if (input) input.value = item[name] ?? '';
    });

    openModal('edit-kasabutan-lost-item-modal');
}

window.openKasabutanLostItemEditModal = openKasabutanLostItemEditModal;