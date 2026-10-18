function openTanodEditModal(tanod) {
    const form = document.getElementById('tanod-edit-form');
    if (!form) return;

    form.action = form.dataset.updateUrlTemplate.replace('__ID__', tanod.id);

    const fields = [
        'id_number', 'full_name', 'position', 'barangay', 'barangay_chairman',
        'home_address', 'date_of_birth', 'place_of_birth', 'height', 'weight',
        'blood_type', 'mobile_no',
        'emergency_contact_name', 'emergency_contact_relationship',
        'emergency_contact_number', 'emergency_contact_address',
    ];

    fields.forEach((name) => {
        const input = form.querySelector(`[name="${name}"]`);
        if (input) input.value = tanod[name] ?? '';
    });

    openModal('edit-tanod-modal');
}

window.openTanodEditModal = openTanodEditModal;