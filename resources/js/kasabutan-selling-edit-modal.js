function openKasabutanSellingEditModal(selling) {
    const form = document.getElementById('kasabutan-selling-edit-form');
    if (!form) return;

    form.action = form.dataset.updateUrlTemplate.replace('__ID__', selling.id);

    // The picker only helps fill fields, so start it empty for every record.
    const searchRoot = form.querySelector('[data-searchable-select]');
    if (searchRoot) {
        searchRoot.querySelector('[data-search-hidden]').value = '';
        searchRoot.querySelector('[data-search-input]').value = '';
    }

    form.querySelector('[name="owner"]').value = selling.owner ?? '';
    form.querySelector('[name="barangay"]').value = selling.barangay ?? '';
    form.querySelector('[name="municipality"]').value = selling.municipality ?? '';
    form.querySelector('[name="body_number"]').value = selling.body_number ?? '';
    form.querySelector('[name="buyer"]').value = selling.buyer ?? '';
    form.querySelector('[name="buyer_address"]').value = selling.buyer_address ?? '';
    form.querySelector('[name="amount"]').value = selling.amount ?? '';
    form.querySelector('[name="witness"]').value = selling.witness ?? '';
    form.querySelector('[name="witness_type"]').value = selling.witness_type ?? '';
    form.querySelector('[name="secretariat"]').value = selling.secretariat ?? '';
    form.querySelector('[name="date"]').value = selling.date ?? '';

    openModal('edit-kasabutan-selling-modal');
}

window.openKasabutanSellingEditModal = openKasabutanSellingEditModal;