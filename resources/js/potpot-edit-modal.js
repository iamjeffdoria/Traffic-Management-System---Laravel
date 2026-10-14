function openPotpotEditModal(potpot) {
    const form = document.getElementById('potpot-edit-form');
    if (!form) return;

    form.action = form.dataset.updateUrlTemplate.replace('__ID__', potpot.id);

    form.querySelector('[name="body_number"]').value = potpot.body_number ?? '';
    form.querySelector('[name="name"]').value = potpot.name ?? '';
    form.querySelector('[name="address"]').value = potpot.address ?? '';
    form.querySelector('[name="contact_number"]').value = potpot.contact_number ?? '';
    form.querySelector('[name="or_number"]').value = potpot.or_number ?? '';
    form.querySelector('[name="status"]').value = potpot.status ?? 'active';
    form.querySelector('[name="date_registered"]').value = potpot.date_registered ?? '';
    form.querySelector('[name="date_expired"]').value = potpot.date_expired ?? '';
    form.querySelector('[name="remarks"]').value = potpot.remarks ?? '';

    openModal('edit-potpot-modal');
}

window.openPotpotEditModal = openPotpotEditModal;