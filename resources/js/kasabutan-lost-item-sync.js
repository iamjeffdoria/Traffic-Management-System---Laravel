// Fills Driver, Body Number and Driver Address from a potpot option.
function fillKasabutanLostItemFromPotpot(container, optionEl) {
    if (!container || !optionEl) return;

    const driver = container.querySelector('[name="driver"]');
    const body = container.querySelector('[name="body_number"]');
    const address = container.querySelector('[name="driver_address"]');

    // Use the potpot's driver if it has one, otherwise the operator is the driver.
    if (driver) driver.value = optionEl.dataset.driver || optionEl.dataset.name || '';
    if (body) body.value = optionEl.dataset.body || '';
    if (address && optionEl.dataset.address) address.value = optionEl.dataset.address;
}

// Called by the searchable select when a potpot is picked from the dropdown.
function onKasabutanLostItemSearchSelect(optionEl, root) {
    fillKasabutanLostItemFromPotpot(root.closest('[data-kasabutan-lost-item-form]'), optionEl);
}
window.onKasabutanLostItemSearchSelect = onKasabutanLostItemSearchSelect;

// Also sync when a body number typed by hand exactly matches a potpot.
// Delegated, so it works for both the create modal and the edit modal.
document.addEventListener('input', (event) => {
    if (!event.target.matches('[data-kasabutan-lost-item-form] [name="body_number"]')) return;

    const container = event.target.closest('[data-kasabutan-lost-item-form]');
    const typed = event.target.value.trim().toLowerCase();
    if (!typed) return;

    const match = Array.from(container.querySelectorAll('[data-option]'))
        .find((opt) => (opt.dataset.body || '').toLowerCase() === typed);

    if (match) fillKasabutanLostItemFromPotpot(container, match);
});