let documentSubmissionFilterDebounce = null;

function debouncedFetchDocumentSubmissionFilter() {
    clearTimeout(documentSubmissionFilterDebounce);
    documentSubmissionFilterDebounce = setTimeout(() => {
        fetchDocumentSubmissionResults();
    }, 300);
}
window.debouncedFetchDocumentSubmissionFilter = debouncedFetchDocumentSubmissionFilter;

let documentSubmissionAbortController = null;

function fetchDocumentSubmissionResults(url) {
    const form = document.getElementById('document-submission-filter-form');
    if (!form) return;

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (documentSubmissionAbortController) {
        documentSubmissionAbortController.abort();
    }
    documentSubmissionAbortController = new AbortController();
    const { signal } = documentSubmissionAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            const wrapper = document.getElementById('document-submission-results');
            if (wrapper) {
                wrapper.innerHTML = html;
            }

            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('#document-submission-filter-form [name]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachDocumentSubmissionLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('Document submission filter fetch failed:', err);
            }
        });
}
window.fetchDocumentSubmissionResults = fetchDocumentSubmissionResults;

function attachDocumentSubmissionLinks() {
    document.querySelectorAll('#document-submission-results a[href]').forEach((link) => {
        if (link.target === '_blank' || link.hasAttribute('download')) return;
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchDocumentSubmissionResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachDocumentSubmissionLinks);