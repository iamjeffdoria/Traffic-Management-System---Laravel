// Shows an in-app print preview (Electron's native print dialog has no preview),
// then prints the previewed document when the user clicks Print.
// Reusable for any print route — mtop, mayors-permit, franchise, etc.
const PREVIEW_PAGE_W = 794;  // 210mm at 96dpi
const PREVIEW_PAGE_H = 1123; // 297mm at 96dpi

function onPrintPreviewKey(event) {
    if (event.key === 'Escape') closePrintPreview();
}

function closePrintPreview() {
    const modal = document.getElementById('print-preview-modal');
    if (modal) modal.remove();
    document.removeEventListener('keydown', onPrintPreviewKey);
}
window.closePrintPreview = closePrintPreview;

function printFromUrl(url) {
    closePrintPreview();

    const modal = document.createElement('div');
    modal.id = 'print-preview-modal';
    modal.className = 'fixed inset-0 z-[9000] flex flex-col bg-gray-800';
    modal.innerHTML = `
        <div class="flex items-center justify-between gap-3 px-5 py-3 bg-gray-900 border-b border-white/10 shrink-0">
            <h3 class="text-sm font-semibold text-white">Print Preview</h3>
            <div class="flex items-center gap-2">
                <button type="button" data-preview-print disabled
                    class="inline-flex items-center gap-1.5 rounded-full bg-red-600 text-white px-5 py-2 text-sm font-semibold hover:bg-red-700 disabled:opacity-40 disabled:cursor-not-allowed transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-12 0h12v6H6v-6z" />
                    </svg>
                    Print
                </button>
                <button type="button" data-preview-close
                    class="rounded-full border border-white/30 text-white px-5 py-2 text-sm font-semibold hover:bg-white/10 transition-colors">
                    Close
                </button>
            </div>
        </div>
        <div class="relative flex-1 overflow-auto p-6 flex justify-center items-start">
            <p data-preview-loading class="absolute inset-0 flex items-center justify-center text-sm text-gray-300">Loading preview...</p>
            <div data-preview-wrap class="invisible shrink-0 bg-white shadow-2xl overflow-hidden"></div>
        </div>
    `;

    const wrap = modal.querySelector('[data-preview-wrap]');
    const loading = modal.querySelector('[data-preview-loading]');
    const printBtn = modal.querySelector('[data-preview-print]');

    // Fit the whole page on screen (never scale above 100%).
    const scale = Math.min(
        1,
        (window.innerWidth - 48) / PREVIEW_PAGE_W,
        (window.innerHeight - 110) / PREVIEW_PAGE_H
    );
    wrap.style.width = Math.round(PREVIEW_PAGE_W * scale) + 'px';
    wrap.style.height = Math.round(PREVIEW_PAGE_H * scale) + 'px';

    const iframe = document.createElement('iframe');
    iframe.style.width = PREVIEW_PAGE_W + 'px';
    iframe.style.height = PREVIEW_PAGE_H + 'px';
    iframe.style.border = '0';
    iframe.style.transformOrigin = 'top left';
    iframe.style.transform = 'scale(' + scale + ')';

    iframe.onload = function () {
        loading.remove();
        wrap.classList.remove('invisible');
        printBtn.disabled = false;
    };

    printBtn.addEventListener('click', function () {
        const frameWindow = iframe.contentWindow;

        const doPrint = function () {
            frameWindow.focus();
            frameWindow.print();
        };

        // Wait for web fonts (e.g. Montserrat) before opening the print dialog
        if (frameWindow.document.fonts && frameWindow.document.fonts.ready) {
            frameWindow.document.fonts.ready.then(doPrint);
        } else {
            doPrint();
        }
    });

    modal.querySelector('[data-preview-close]').addEventListener('click', closePrintPreview);

    iframe.src = url;
    wrap.appendChild(iframe);
    document.body.appendChild(modal);
    document.addEventListener('keydown', onPrintPreviewKey);
}
window.printFromUrl = printFromUrl;