function openTricycleEditModal(tricycle) {
    const form = document.getElementById('tricycle-edit-form');
    if (!form) return;

    const urlTemplate = form.dataset.updateUrlTemplate;
    form.action = urlTemplate.replace('__ID__', tricycle.id);

    form.querySelector('[name="body_number"]').value = tricycle.body_number ?? '';
    form.querySelector('[name="plate_no"]').value = tricycle.plate_no ?? '';
    form.querySelector('[name="name"]').value = tricycle.name ?? '';
    form.querySelector('[name="address"]').value = tricycle.address ?? '';
    form.querySelector('[name="make_kind"]').value = tricycle.make_kind ?? '';
    form.querySelector('[name="status"]').value = tricycle.status ?? 'active';
    form.querySelector('[name="engine_motor_no"]').value = tricycle.engine_motor_no ?? '';
    form.querySelector('[name="chassis_no"]').value = tricycle.chassis_no ?? '';
    form.querySelector('[name="date_registered"]').value = tricycle.date_registered ?? '';
    form.querySelector('[name="date_expired"]').value = tricycle.date_expired ?? '';
    form.querySelector('[name="toda"]').value = tricycle.toda ?? '';
    form.querySelector('[name="remarks"]').value = tricycle.remarks ?? '';

    openModal('edit-tricycle-modal');
}

window.openTricycleEditModal = openTricycleEditModal;