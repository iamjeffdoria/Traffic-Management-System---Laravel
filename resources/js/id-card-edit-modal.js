function openIdCardEditModal(idCard) {
    const form = document.getElementById('id-card-edit-form');
    if (!form) return;

    const urlTemplate = form.dataset.updateUrlTemplate;
    form.action = urlTemplate.replace('__ID__', idCard.id);

    form.querySelector('[name="full_name"]').value = idCard.full_name ?? '';
    form.querySelector('[name="id_number"]').value = idCard.id_number ?? '';
    form.querySelector('[name="gender"]').value = idCard.gender ?? 'Male';
    form.querySelector('[name="date_of_birth"]').value = idCard.date_of_birth ?? '';
    form.querySelector('[name="address"]').value = idCard.address ?? '';
    form.querySelector('[name="height"]').value = idCard.height ?? '';
    form.querySelector('[name="weight"]').value = idCard.weight ?? '';
    form.querySelector('[name="or_number"]').value = idCard.or_number ?? '';
    form.querySelector('[name="date_issued"]').value = idCard.date_issued ?? '';
    form.querySelector('[name="expiry_date"]').value = idCard.expiry_date ?? '';

    // Reset the file input every time the modal opens, since a stale
    // selected file must never silently carry over to a different record.
    const fileInput = form.querySelector('[name="photo"]');
    if (fileInput) fileInput.value = '';

    const preview = form.querySelector('[data-id-card-photo-preview]');
    const placeholder = form.querySelector('[data-id-card-photo-placeholder]');
    if (idCard.photo_url) {
        preview.src = idCard.photo_url;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
    } else {
        preview.src = '';
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
    }

    openModal('edit-id-card-modal');
}

window.openIdCardEditModal = openIdCardEditModal;