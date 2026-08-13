let permitFilterDebounce = null;

function debouncedFetchPermitFilter() {
    clearTimeout(permitFilterDebounce);
    permitFilterDebounce = setTimeout(() => {
        fetchPermitResults();
    }, 300);
}
window.debouncedFetchPermitFilter = debouncedFetchPermitFilter;

let permitAbortController = null;

function fetchPermitResults(url) {
    const form = document.getElementById('tricycle-mayors-permit-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (permitAbortController) {
        permitAbortController.abort();
    }
    permitAbortController = new AbortController();
    const { signal } = permitAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            const tbodyMatch = html.match(/<tbody[^>]*id="tricycle-mayors-permit-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'tricycle-mayors-permit-tbody-desktop',
                'tricycle-mayors-permit-pagination-desktop',
                'tricycle-mayors-permit-cards-mobile',
                'tricycle-mayors-permit-pagination-mobile',
            ];

            targets.forEach((id) => {
                const doc = id === 'tricycle-mayors-permit-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('#tricycle-mayors-permit-filter-form [name], [form="tricycle-mayors-permit-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachPermitPaginationLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('Permit filter fetch failed:', err);
            }
        });
}
window.fetchPermitResults = fetchPermitResults;

function attachPermitPaginationLinks() {
    document.querySelectorAll('#tricycle-mayors-permit-pagination-desktop a, #tricycle-mayors-permit-pagination-mobile a, [data-ajax-permit-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchPermitResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachPermitPaginationLinks);