// Selection lives in a Set so it survives AJAX search/pagination refreshes.
window.selectedTanodIdIds = window.selectedTanodIdIds || new Set();

function updateTanodIdBulkPrintButton() {
    const btn = document.getElementById('tanod-id-bulk-print-btn');
    if (!btn) return;

    const count = window.selectedTanodIdIds.size;
    btn.disabled = count === 0;
    btn.textContent = count > 0 ? `Print Selected (${count})` : 'Print Selected';
}
window.updateTanodIdBulkPrintButton = updateTanodIdBulkPrintButton;

function syncTanodIdCheckboxes() {
    document.querySelectorAll('.tanod-id-checkbox').forEach((cb) => {
        cb.checked = window.selectedTanodIdIds.has(cb.dataset.id);
    });
    updateTanodIdBulkPrintButton();
}
window.syncTanodIdCheckboxes = syncTanodIdCheckboxes;

function printSelectedTanodIds() {
    const btn = document.getElementById('tanod-id-bulk-print-btn');
    if (!btn) return;

    const ids = Array.from(window.selectedTanodIdIds).slice(0, 4);
    if (ids.length === 0) return;

    printFromUrl(`${btn.dataset.bulkPrintUrl}?ids=${ids.join(',')}`);
}
window.printSelectedTanodIds = printSelectedTanodIds;

function toggleAllTanodIdCheckboxes(source) {
    const boxes = Array.from(document.querySelectorAll('.tanod-id-checkbox'));

    if (!source.checked) {
        boxes.forEach((cb) => window.selectedTanodIdIds.delete(cb.dataset.id));
    } else {
        for (const cb of boxes) {
            if (window.selectedTanodIdIds.size >= 4) break;
            window.selectedTanodIdIds.add(cb.dataset.id);
        }
    }

    syncTanodIdCheckboxes();
}
window.toggleAllTanodIdCheckboxes = toggleAllTanodIdCheckboxes;

document.addEventListener('change', (event) => {
    if (!event.target.matches('.tanod-id-checkbox')) return;

    const id = event.target.dataset.id;

    if (event.target.checked) {
        if (window.selectedTanodIdIds.size >= 4) {
            event.target.checked = false;
            if (typeof showToast === 'function') {
                showToast('You can only print up to 4 Tanod IDs at once.', 'error');
            }
            return;
        }
        window.selectedTanodIdIds.add(id);
    } else {
        window.selectedTanodIdIds.delete(id);
    }

    updateTanodIdBulkPrintButton();
});

document.addEventListener('DOMContentLoaded', syncTanodIdCheckboxes);