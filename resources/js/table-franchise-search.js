let franchiseFilterDebounce = null;

function debouncedFetchFranchiseFilter() {
    clearTimeout(franchiseFilterDebounce);
    franchiseFilterDebounce = setTimeout(() => {
        fetchFranchiseResults();
    }, 300);
}
window.debouncedFetchFranchiseFilter = debouncedFetchFranchiseFilter;

let franchiseAbortController = null;

function fetchFranchiseResults(url) {
    const form = document.getElementById('franchise-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (franchiseAbortController) {
        franchiseAbortController.abort();
    }
    franchiseAbortController = new AbortController();
    const { signal } = franchiseAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            const tbodyMatch = html.match(/<tbody[^>]*id="franchise-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'franchise-tbody-desktop',
                'franchise-pagination-desktop',
                'franchise-cards-mobile',
                'franchise-pagination-mobile',
                'franchise-edit-modals',
            ];

            targets.forEach((id) => {
                const doc = id === 'franchise-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('#franchise-filter-form [name], [form="franchise-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachFranchisePaginationLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('Franchise filter fetch failed:', err);
            }
        });
}
window.fetchFranchiseResults = fetchFranchiseResults;

function attachFranchisePaginationLinks() {
    document.querySelectorAll('#franchise-pagination-desktop a, #franchise-pagination-mobile a, [data-ajax-franchise-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchFranchiseResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachFranchisePaginationLinks);