let potpotPermitFilterDebounce = null;

function debouncedFetchPotpotPermitFilter() {
    clearTimeout(potpotPermitFilterDebounce);
    potpotPermitFilterDebounce = setTimeout(() => {
        fetchPotpotPermitResults();
    }, 300);
}
window.debouncedFetchPotpotPermitFilter = debouncedFetchPotpotPermitFilter;

let potpotPermitAbortController = null;

function fetchPotpotPermitResults(url) {
    const form = document.getElementById('potpot-mayors-permit-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (potpotPermitAbortController) {
        potpotPermitAbortController.abort();
    }
    potpotPermitAbortController = new AbortController();
    const { signal } = potpotPermitAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            const tbodyMatch = html.match(/<tbody[^>]*id="potpot-mayors-permit-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'potpot-mayors-permit-tbody-desktop',
                'potpot-mayors-permit-pagination-desktop',
                'potpot-mayors-permit-cards-mobile',
                'potpot-mayors-permit-pagination-mobile',
            ];

            targets.forEach((id) => {
                const doc = id === 'potpot-mayors-permit-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('#potpot-mayors-permit-filter-form [name], [form="potpot-mayors-permit-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachPotpotPermitPaginationLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('Potpot permit filter fetch failed:', err);
            }
        });
}
window.fetchPotpotPermitResults = fetchPotpotPermitResults;

function attachPotpotPermitPaginationLinks() {
    document.querySelectorAll('#potpot-mayors-permit-pagination-desktop a, #potpot-mayors-permit-pagination-mobile a, [data-ajax-potpot-permit-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchPotpotPermitResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachPotpotPermitPaginationLinks);