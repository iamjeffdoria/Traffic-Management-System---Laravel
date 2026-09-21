// Selection survives AJAX search/filter/pagination refreshes, since those
// swap out the table/card DOM entirely and would otherwise wipe any
// checked boxes. The set of chosen IDs is the source of truth; checkboxes
// are just a reflection of it, re-applied every time fresh rows render.
window.selectedIdCardIds = window.selectedIdCardIds || new Set();

function updateIdCardBulkPrintButton() {
    const btn = document.getElementById('id-card-bulk-print-btn');
    if (!btn) return;

    const count = window.selectedIdCardIds.size;
    btn.disabled = count === 0;
    btn.textContent = count > 0 ? `Print Selected (${count})` : 'Print Selected';
}
window.updateIdCardBulkPrintButton = updateIdCardBulkPrintButton;

// Re-checks any checkbox in the current DOM whose id is in the selection
// set. Call this after any AJAX refresh (search, pagination, etc.).
function syncIdCardCheckboxes() {
    document.querySelectorAll('.id-card-checkbox').forEach((cb) => {
        cb.checked = window.selectedIdCardIds.has(cb.dataset.id);
    });
    updateIdCardBulkPrintButton();
}
window.syncIdCardCheckboxes = syncIdCardCheckboxes;

function printSelectedIdCards() {
    const btn = document.getElementById('id-card-bulk-print-btn');
    if (!btn) return;

    const ids = Array.from(window.selectedIdCardIds).slice(0, 4);
    if (ids.length === 0) return;

    printFromUrl(`${btn.dataset.bulkPrintUrl}?ids=${ids.join(',')}`);
}
window.printSelectedIdCards = printSelectedIdCards;

// Only affects checkboxes currently visible on screen (the current
// page/search results), same as before.
function toggleAllIdCardCheckboxes(source) {
    const boxes = Array.from(document.querySelectorAll('.id-card-checkbox'));

    if (!source.checked) {
        boxes.forEach((cb) => window.selectedIdCardIds.delete(cb.dataset.id));
    } else {
        for (const cb of boxes) {
            if (window.selectedIdCardIds.size >= 4) break;
            window.selectedIdCardIds.add(cb.dataset.id);
        }
    }

    syncIdCardCheckboxes();
}
window.toggleAllIdCardCheckboxes = toggleAllIdCardCheckboxes;

document.addEventListener('change', (event) => {
    if (!event.target.matches('.id-card-checkbox')) return;

    const id = event.target.dataset.id;

    if (event.target.checked) {
        if (window.selectedIdCardIds.size >= 4) {
            event.target.checked = false;
            if (typeof showToast === 'function') {
                showToast('You can only print up to 4 ID cards at once.', 'error');
            }
            return;
        }
        window.selectedIdCardIds.add(id);
    } else {
        window.selectedIdCardIds.delete(id);
    }

    updateIdCardBulkPrintButton();
});

document.addEventListener('DOMContentLoaded', syncIdCardCheckboxes);