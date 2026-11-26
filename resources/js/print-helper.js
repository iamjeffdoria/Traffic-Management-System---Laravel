// Prints a document from a URL using the browser's native print dialog.
// Loads the print page in a hidden iframe, waits for fonts and images,
// then opens the print dialog directly (no custom preview).
// Reusable for any print route — mtop, mayors-permit, franchise, etc.
function printFromUrl(url) {
    // Remove any leftover frame from a previous print.
    const existing = document.getElementById('print-frame');
    if (existing) existing.remove();

    const iframe = document.createElement('iframe');
    iframe.id = 'print-frame';
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = '0';

    iframe.onload = function () {
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
    };

    iframe.src = url;
    document.body.appendChild(iframe);
}
window.printFromUrl = printFromUrl;