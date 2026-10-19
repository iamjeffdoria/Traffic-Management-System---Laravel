let tanodIdFilterDebounce = null;

function debouncedFetchTanodIdFilter() {
    clearTimeout(tanodIdFilterDebounce);
    tanodIdFilterDebounce = setTimeout(() => {
        fetchTanodIdResults();
    }, 300);
}
window.debouncedFetchTanodIdFilter = debouncedFetchTanodIdFilter;

let tanodIdAbortController = null;

function fetchTanodIdResults(url) {
    const form = document.getElementById('tanod-id-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (tanodIdAbortController) {
        tanodIdAbortController.abort();
    }
    tanodIdAbortController = new AbortController();
    const { signal } = tanodIdAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            // <tbody> only parses inside a <table>, so wrap just that snippet.
            const tbodyMatch = html.match(/<tbody[^>]*id="tanod-id-tbody"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            [
                'tanod-id-tbody',
                'tanod-id-pagination-desktop',
                'tanod-id-cards-mobile',
                'tanod-id-pagination-mobile',
            ].forEach((id) => {
                const doc = id === 'tanod-id-tbody' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            // Don't overwrite the field the user is currently typing in.
            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('[form="tanod-id-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachTanodIdPaginationLinks();
            if (typeof syncTanodIdCheckboxes === 'function') {
                syncTanodIdCheckboxes();
            }
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('Tanod ID filter fetch failed:', err);
            }
        });
}
window.fetchTanodIdResults = fetchTanodIdResults;

function attachTanodIdPaginationLinks() {
    document.querySelectorAll('#tanod-id-pagination-desktop a, #tanod-id-pagination-mobile a, [data-ajax-tanod-id-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchTanodIdResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachTanodIdPaginationLinks);