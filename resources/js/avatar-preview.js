function previewAvatar(input) {
    const wrap = input.closest('[data-avatar-upload]');
    const file = input.files[0];
    if (!wrap || !file) return;

    const img = wrap.querySelector('[data-avatar-img]');
    const placeholder = wrap.querySelector('[data-avatar-placeholder]');
    const label = wrap.querySelector('[data-avatar-filename]');

    img.src = URL.createObjectURL(file);
    img.classList.remove('hidden');
    if (placeholder) placeholder.classList.add('hidden');
    if (label) label.textContent = file.name;
}
window.previewAvatar = previewAvatar;