// Makes "Renewed By" required (native browser message) only while status = renewed.
function syncPotpotRenewedByRequired(container) {
    if (!container) return;

    const status = container.querySelector('[name="status"]');
    const renewedBy = container.querySelector('[name="renewed_by"]');
    const star = container.querySelector('[data-renewed-by-required]');
    if (!status || !renewedBy) return;

    const isRenewed = status.value === 'renewed';
    renewedBy.required = isRenewed;
    if (star) star.classList.toggle('hidden', !isRenewed);
}
window.syncPotpotRenewedByRequired = syncPotpotRenewedByRequired;

// Delegated, so it keeps working after the AJAX refresh replaces the edit modal.
document.addEventListener('change', (event) => {
    if (!event.target.matches('[data-potpot-permit-form] [name="status"]')) return;
    syncPotpotRenewedByRequired(event.target.closest('[data-potpot-permit-form]'));
});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-potpot-permit-form]').forEach(syncPotpotRenewedByRequired);
});