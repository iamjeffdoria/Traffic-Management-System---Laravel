function openPotpotPermitEditModal(permit) {
    const form = document.getElementById('potpot-mayors-permit-edit-form');
    if (!form) return;

    const urlTemplate = form.dataset.updateUrlTemplate;
    form.action = urlTemplate.replace('__ID__', permit.id);

    form.querySelector('[name="name"]').value = permit.name ?? '';
    form.querySelector('[name="address"]').value = permit.address ?? '';
    form.querySelector('[name="control_no"]').value = permit.control_no ?? '';
    form.querySelector('[name="status"]').value = permit.status ?? 'active';
    form.querySelector('[name="business_name"]').value = permit.business_name ?? '';
    form.querySelector('[name="motorized_operation"]').value = permit.motorized_operation ?? '';
    form.querySelector('[name="or_no"]').value = permit.or_no ?? '';
    form.querySelector('[name="amount_paid"]').value = permit.amount_paid ?? '';
    form.querySelector('[name="issue_date"]').value = permit.issue_date ?? '';
    form.querySelector('[name="expiry_date"]').value = permit.expiry_date ?? '';
    form.querySelector('[name="issued_at"]').value = permit.issued_at ?? '';
    form.querySelector('[name="mayor"]').value = permit.mayor ?? '';
    form.querySelector('[name="quarter"]').value = permit.quarter ?? '';

    openModal('edit-potpot-mayors-permit-modal');
}

window.openPotpotPermitEditModal = openPotpotPermitEditModal;