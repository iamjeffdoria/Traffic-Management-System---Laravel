function openAdminEditModal(admin) {
    const form = document.getElementById('admin-edit-form');
    if (!form) return;

    form.action = form.dataset.updateUrlTemplate.replace('__ID__', admin.id);

    form.querySelector('[name="name"]').value = admin.name ?? '';
    form.querySelector('[name="email"]').value = admin.email ?? '';
    form.querySelector('[name="role"]').value = admin.role ?? 'tricycle_admin';

    // Reset password + hint so nothing carries over from a previous admin.
    const password = form.querySelector('[name="password"]');
    password.value = '';
    password.classList.remove('border-red-500', 'border-green-500');
    password.classList.add('border-gray-300');
    const hint = document.getElementById('edit-password-hint');
    if (hint) {
        hint.textContent = 'Leave blank to keep current, or enter at least 8 characters';
        hint.className = 'text-xs mt-1 text-gray-400';
    }

    // Reset the file input so a stale selected file never carries over.
    const fileInput = form.querySelector('[name="photo"]');
    if (fileInput) fileInput.value = '';

    const img = form.querySelector('[data-avatar-img]');
    const placeholder = form.querySelector('[data-avatar-placeholder]');
    const label = form.querySelector('[data-avatar-filename]');

    if (admin.photo_url) {
        img.src = admin.photo_url;
        img.classList.remove('hidden');
        placeholder.classList.add('hidden');
    } else {
        img.src = '';
        img.classList.add('hidden');
        placeholder.textContent = (admin.name || '?').charAt(0).toUpperCase();
        placeholder.classList.remove('hidden');
    }

    if (label) label.textContent = 'Click to change photo · max 2 MB';

    openModal('edit-admin-modal');
}

window.openAdminEditModal = openAdminEditModal;