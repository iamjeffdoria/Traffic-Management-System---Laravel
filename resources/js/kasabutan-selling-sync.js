// Fills Owner, Barangay and Body Number from a potpot option.
function fillKasabutanFromPotpot(container, optionEl) {
    if (!container || !optionEl) return;

    const owner = container.querySelector('[name="owner"]');
    const barangay = container.querySelector('[name="barangay"]');
    const body = container.querySelector('[name="body_number"]');

    if (owner) owner.value = optionEl.dataset.name || '';
    if (barangay && optionEl.dataset.address) barangay.value = optionEl.dataset.address;
    if (body) body.value = optionEl.dataset.body || '';
}

// Called by the searchable select when a potpot is picked from the dropdown.
function onKasabutanSearchSelect(optionEl, root) {
    fillKasabutanFromPotpot(root.closest('[data-kasabutan-form]'), optionEl);
}
window.onKasabutanSearchSelect = onKasabutanSearchSelect;

// Also sync when a body number typed by hand exactly matches a potpot.
// Delegated, so it works for the create modal and the edit modal.
document.addEventListener('input', (event) => {
    if (!event.target.matches('[data-kasabutan-form] [name="body_number"]')) return;

    const container = event.target.closest('[data-kasabutan-form]');
    const typed = event.target.value.trim().toLowerCase();
    if (!typed) return;

    const match = Array.from(container.querySelectorAll('[data-option]'))
        .find((opt) => (opt.dataset.body || '').toLowerCase() === typed);

    if (match) fillKasabutanFromPotpot(container, match);
});