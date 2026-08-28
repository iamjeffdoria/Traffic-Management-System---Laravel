let idCardFilterDebounce = null;

function debouncedFetchIdCardFilter() {
    clearTimeout(idCardFilterDebounce);
    idCardFilterDebounce = setTimeout(() => {
        fetchIdCardResults();
    }, 300);
}
window.debouncedFetchIdCardFilter = debouncedFetchIdCardFilter;

let idCardAbortController = null;

function fetchIdCardResults(url) {
    const form = document.getElementById('id-card-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (idCardAbortController) {
        idCardAbortController.abort();
    }
    idCardAbortController = new AbortController();
    const { signal } = idCardAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            const tbodyMatch = html.match(/<tbody[^>]*id="id-card-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'id-card-tbody-desktop',
                'id-card-pagination-desktop',
                'id-card-cards-mobile',
                'id-card-pagination-mobile',
                'id-card-edit-modals',
            ];

            targets.forEach((id) => {
                const doc = id === 'id-card-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('#id-card-filter-form [name], [form="id-card-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachIdCardPaginationLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('ID card filter fetch failed:', err);
            }
        });
}
window.fetchIdCardResults = fetchIdCardResults;

function attachIdCardPaginationLinks() {
    document.querySelectorAll('#id-card-pagination-desktop a, #id-card-pagination-mobile a, [data-ajax-id-card-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchIdCardResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachIdCardPaginationLinks);