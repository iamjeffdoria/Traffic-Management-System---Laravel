let potpotFilterDebounce = null;

function debouncedFetchPotpotFilter() {
    clearTimeout(potpotFilterDebounce);
    potpotFilterDebounce = setTimeout(() => {
        fetchPotpotResults();
    }, 300);
}
window.debouncedFetchPotpotFilter = debouncedFetchPotpotFilter;

let potpotAbortController = null;

function fetchPotpotResults(url) {
    const form = document.getElementById('potpot-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (potpotAbortController) {
        potpotAbortController.abort();
    }
    potpotAbortController = new AbortController();
    const { signal } = potpotAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            // <tbody> only parses inside a <table>, so wrap just that snippet.
            const tbodyMatch = html.match(/<tbody[^>]*id="potpot-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'potpot-tbody-desktop',
                'potpot-pagination-desktop',
                'potpot-cards-mobile',
                'potpot-pagination-mobile',
            ];

            targets.forEach((id) => {
                const doc = id === 'potpot-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            // Don't overwrite the field the user is currently typing in.
            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('[form="potpot-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachPotpotPaginationLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('Potpot filter fetch failed:', err);
            }
        });
}
window.fetchPotpotResults = fetchPotpotResults;

function attachPotpotPaginationLinks() {
    document.querySelectorAll('#potpot-pagination-desktop a, #potpot-pagination-mobile a, [data-ajax-potpot-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchPotpotResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachPotpotPaginationLinks);