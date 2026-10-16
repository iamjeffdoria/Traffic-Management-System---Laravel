let tanodFilterDebounce = null;

function debouncedFetchTanodFilter() {
    clearTimeout(tanodFilterDebounce);
    tanodFilterDebounce = setTimeout(() => {
        fetchTanodResults();
    }, 300);
}
window.debouncedFetchTanodFilter = debouncedFetchTanodFilter;

let tanodAbortController = null;

function fetchTanodResults(url) {
    const form = document.getElementById('tanod-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (tanodAbortController) {
        tanodAbortController.abort();
    }
    tanodAbortController = new AbortController();
    const { signal } = tanodAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            // <tbody> only parses inside a <table>, so wrap just that snippet.
            const tbodyMatch = html.match(/<tbody[^>]*id="tanod-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'tanod-tbody-desktop',
                'tanod-pagination-desktop',
                'tanod-cards-mobile',
                'tanod-pagination-mobile',
            ];

            targets.forEach((id) => {
                const doc = id === 'tanod-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            // Don't overwrite the field the user is currently typing in.
            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('[form="tanod-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachTanodPaginationLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('Tanod filter fetch failed:', err);
            }
        });
}
window.fetchTanodResults = fetchTanodResults;

function attachTanodPaginationLinks() {
    document.querySelectorAll('#tanod-pagination-desktop a, #tanod-pagination-mobile a, [data-ajax-tanod-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchTanodResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachTanodPaginationLinks);