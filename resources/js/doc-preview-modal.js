function openDocModal(url, label) {
    const modal = document.getElementById('doc-preview-modal');
    const title = document.getElementById('doc-preview-title');
    const img = document.getElementById('doc-preview-image');
    const pdf = document.getElementById('doc-preview-pdf');
    const download = document.getElementById('doc-preview-download');

    title.textContent = label;
    download.href = url;

    const isPdf = url.toLowerCase().endsWith('.pdf');

    if (isPdf) {
        pdf.src = url;
        pdf.classList.remove('hidden');
        img.classList.add('hidden');
        img.src = '';
    } else {
        img.src = url;
        img.classList.remove('hidden');
        pdf.classList.add('hidden');
        pdf.src = '';
    }

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}
window.openDocModal = openDocModal;

function closeDocModal() {
    const modal = document.getElementById('doc-preview-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.getElementById('doc-preview-pdf').src = '';
    document.getElementById('doc-preview-image').src = '';
}
window.closeDocModal = closeDocModal;