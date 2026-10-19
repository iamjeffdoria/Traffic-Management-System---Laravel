function openTanodIdEditModal(tanodId) {
    const form = document.getElementById('tanod-id-edit-form');
    if (!form) return;

    form.action = form.dataset.updateUrlTemplate.replace('__ID__', tanodId.id);

    [
        'id_number', 'full_name', 'address', 'date_of_birth',
        'emergency_contact_name', 'emergency_contact_relationship',
        'emergency_contact_number', 'emergency_contact_address',
    ].forEach((name) => {
        const input = form.querySelector(`[name="${name}"]`);
        if (input) input.value = tanodId[name] ?? '';
    });

    // Reset the file input so a stale selected file never carries over.
    const fileInput = form.querySelector('[name="photo"]');
    if (fileInput) fileInput.value = '';

    const preview = form.querySelector('[data-avatar-img]');
    const placeholder = form.querySelector('[data-avatar-placeholder]');
    const photoLabel = form.querySelector('[data-avatar-filename]');

    if (tanodId.photo_url) {
        preview.src = tanodId.photo_url;
        preview.classList.remove('hidden');
        placeholder.classList.add('hidden');
    } else {
        preview.src = '';
        preview.classList.add('hidden');
        placeholder.classList.remove('hidden');
    }
    if (photoLabel) photoLabel.textContent = 'Click the photo to change it · max 5 MB';

    openModal('edit-tanod-id-modal');
}

window.openTanodIdEditModal = openTanodIdEditModal;