// Selection lives in a Set so it survives AJAX search/pagination refreshes.
window.selectedTanodIdIds = window.selectedTanodIdIds || new Set();

function updateTanodIdBulkPrintButton() {
    const btn = document.getElementById('tanod-id-bulk-print-btn');
    if (!btn) return;

    const count = window.selectedTanodIdIds.size;
    btn.disabled = count === 0;

    const label = document.getElementById('tanod-id-bulk-print-label');
    if (label) label.textContent = count > 0 ? `Print Selected (${count})` : 'Print Selected';

    // Close the menu if everything gets unchecked
    if (count === 0) {
        const menu = document.getElementById('tanod-id-print-dropdown');
        if (menu) menu.classList.add('hidden');
    }
}
window.updateTanodIdBulkPrintButton = updateTanodIdBulkPrintButton;

function syncTanodIdCheckboxes() {
    document.querySelectorAll('.tanod-id-checkbox').forEach((cb) => {
        cb.checked = window.selectedTanodIdIds.has(cb.dataset.id);
    });
    updateTanodIdBulkPrintButton();
}
window.syncTanodIdCheckboxes = syncTanodIdCheckboxes;

function printSelectedTanodIds(side = '') {
    const btn = document.getElementById('tanod-id-bulk-print-btn');
    if (!btn) return;

    const ids = Array.from(window.selectedTanodIdIds).slice(0, 4);
    if (ids.length === 0) return;

    const sideParam = side ? `&side=${side}` : '';

    const menu = document.getElementById('tanod-id-print-dropdown');
    if (menu) menu.classList.add('hidden');

    printFromUrl(`${btn.dataset.bulkPrintUrl}?ids=${ids.join(',')}${sideParam}`);
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