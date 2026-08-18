let mtopFilterDebounce = null;

function debouncedFetchMtopFilter() {
    clearTimeout(mtopFilterDebounce);
    mtopFilterDebounce = setTimeout(() => {
        fetchMtopResults();
    }, 300);
}
window.debouncedFetchMtopFilter = debouncedFetchMtopFilter;

let mtopAbortController = null;

function fetchMtopResults(url) {
    const form = document.getElementById('mtop-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (mtopAbortController) {
        mtopAbortController.abort();
    }
    mtopAbortController = new AbortController();
    const { signal } = mtopAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            const tbodyMatch = html.match(/<tbody[^>]*id="mtop-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'mtop-tbody-desktop',
                'mtop-pagination-desktop',
                'mtop-cards-mobile',
                'mtop-pagination-mobile',
            ];

            targets.forEach((id) => {
                const doc = id === 'mtop-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('#mtop-filter-form [name], [form="mtop-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachMtopPaginationLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('MTOP filter fetch failed:', err);
            }
        });
}
window.fetchMtopResults = fetchMtopResults;

function attachMtopPaginationLinks() {
    document.querySelectorAll('#mtop-pagination-desktop a, #mtop-pagination-mobile a, [data-ajax-mtop-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchMtopResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachMtopPaginationLinks);