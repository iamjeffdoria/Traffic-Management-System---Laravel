let adminFilterDebounce = null;

function debouncedFetchAdminFilter() {
    clearTimeout(adminFilterDebounce);
    adminFilterDebounce = setTimeout(() => {
        fetchAdminResults();
    }, 300);
}
window.debouncedFetchAdminFilter = debouncedFetchAdminFilter;

let adminAbortController = null;

function fetchAdminResults(url) {
    const form = document.getElementById('admin-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024;

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    if (adminAbortController) {
        adminAbortController.abort();
    }
    adminAbortController = new AbortController();
    const { signal } = adminAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            const tbodyMatch = html.match(/<tbody[^>]*id="admin-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'admin-tbody-desktop',
                'admin-pagination-desktop',
                'admin-cards-mobile',
                'admin-pagination-mobile',
                'admin-edit-modals',
            ];

            targets.forEach((id) => {
                const doc = id === 'admin-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('#admin-filter-form [name], [form="admin-filter-form"]').forEach((el) => {
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachAdminPaginationLinks();
        })
        .catch((err) => {
            if (err.name !== 'AbortError') {
                console.error('Admin filter fetch failed:', err);
            }
        });
}
window.fetchAdminResults = fetchAdminResults;

function attachAdminPaginationLinks() {
    document.querySelectorAll('#admin-pagination-desktop a, #admin-pagination-mobile a, [data-ajax-admin-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchAdminResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachAdminPaginationLinks);