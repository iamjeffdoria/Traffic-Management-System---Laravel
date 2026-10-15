let kasabutanLostItemFilterDebounce = null;

function debouncedFetchKasabutanLostItemFilter() {
    clearTimeout(kasabutanLostItemFilterDebounce);
    kasabutanLostItemFilterDebounce = setTimeout(() => {
        fetchKasabutanLostItemResults();
    }, 300);
}
window.debouncedFetchKasabutanLostItemFilter = debouncedFetchKasabutanLostItemFilter;

let kasabutanLostItemAbortController = null;

function fetchKasabutanLostItemResults(url) {
    const form = document.getElementById('kasabutan-lost-item-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (kasabutanLostItemAbortController) {
        kasabutanLostItemAbortController.abort();
    }
    kasabutanLostItemAbortController = new AbortController();
    const { signal } = kasabutanLostItemAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            // <tbody> only parses inside a <table>, so wrap just that snippet.
            const tbodyMatch = html.match(/<tbody[^>]*id="kasabutan-lost-item-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'kasabutan-lost-item-tbody-desktop',
                'kasabutan-lost-item-pagination-desktop',
                'kasabutan-lost-item-cards-mobile',
                'kasabutan-lost-item-pagination-mobile',
            ];

            targets.forEach((id) => {
                const doc = id === 'kasabutan-lost-item-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            // Don't overwrite the field the user is currently typing in.
            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('[form="kasabutan-lost-item-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachKasabutanLostItemPaginationLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('Kasabutan lost item filter fetch failed:', err);
            }
        });
}
window.fetchKasabutanLostItemResults = fetchKasabutanLostItemResults;

function attachKasabutanLostItemPaginationLinks() {
    document.querySelectorAll('#kasabutan-lost-item-pagination-desktop a, #kasabutan-lost-item-pagination-mobile a, [data-ajax-kasabutan-lost-item-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchKasabutanLostItemResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachKasabutanLostItemPaginationLinks);