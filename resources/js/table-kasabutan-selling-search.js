let kasabutanSellingFilterDebounce = null;

function debouncedFetchKasabutanSellingFilter() {
    clearTimeout(kasabutanSellingFilterDebounce);
    kasabutanSellingFilterDebounce = setTimeout(() => {
        fetchKasabutanSellingResults();
    }, 300);
}
window.debouncedFetchKasabutanSellingFilter = debouncedFetchKasabutanSellingFilter;

let kasabutanSellingAbortController = null;

function fetchKasabutanSellingResults(url) {
    const form = document.getElementById('kasabutan-selling-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (kasabutanSellingAbortController) {
        kasabutanSellingAbortController.abort();
    }
    kasabutanSellingAbortController = new AbortController();
    const { signal } = kasabutanSellingAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            // <tbody> only parses inside a <table>, so wrap just that snippet.
            const tbodyMatch = html.match(/<tbody[^>]*id="kasabutan-selling-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'kasabutan-selling-tbody-desktop',
                'kasabutan-selling-pagination-desktop',
                'kasabutan-selling-cards-mobile',
                'kasabutan-selling-pagination-mobile',
            ];

            targets.forEach((id) => {
                const doc = id === 'kasabutan-selling-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            // Don't overwrite the field the user is currently typing in.
            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('[form="kasabutan-selling-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachKasabutanSellingPaginationLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('Kasabutan selling filter fetch failed:', err);
            }
        });
}
window.fetchKasabutanSellingResults = fetchKasabutanSellingResults;

function attachKasabutanSellingPaginationLinks() {
    document.querySelectorAll('#kasabutan-selling-pagination-desktop a, #kasabutan-selling-pagination-mobile a, [data-ajax-kasabutan-selling-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchKasabutanSellingResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachKasabutanSellingPaginationLinks);