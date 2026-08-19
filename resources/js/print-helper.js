// Loads a print URL into a hidden iframe on the current page and triggers
// the browser's print dialog scoped to that iframe's content only.
// Reusable for any print route — mtop, mayors-permit, franchise, etc.
function printFromUrl(url) {
    const existing = document.getElementById('print-helper-frame');
    if (existing) existing.remove();

    const iframe = document.createElement('iframe');
    iframe.id = 'print-helper-frame';
    iframe.style.position = 'fixed';
    iframe.style.right = '0';
    iframe.style.bottom = '0';
    iframe.style.width = '0';
    iframe.style.height = '0';
    iframe.style.border = '0';
    iframe.src = url;

    iframe.onload = function () {
        const frameWindow = iframe.contentWindow;
        frameWindow.focus();
        frameWindow.print();

        frameWindow.onafterprint = function () {
            iframe.remove();
        };
    };

    document.body.appendChild(iframe);
}
window.printFromUrl = printFromUrl;