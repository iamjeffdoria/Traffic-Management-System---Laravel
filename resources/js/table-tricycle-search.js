let tricycleFilterDebounce = null;

function debouncedFetchFilter() {
    clearTimeout(tricycleFilterDebounce);
    tricycleFilterDebounce = setTimeout(() => {
        fetchTricycleResults();
    }, 300);
}
window.debouncedFetchFilter = debouncedFetchFilter;

let tricycleAbortController = null;

function fetchTricycleResults(url) {
    const form = document.getElementById('tricycle-filter-form');
    if (!form) return;

    const isMobile = window.innerWidth < 1024; // Tailwind's lg breakpoint

    document.querySelectorAll('[data-filter-scope="desktop"]').forEach((el) => {
        el.disabled = isMobile;
    });
    document.querySelectorAll('[data-filter-scope="mobile"]').forEach((el) => {
        el.disabled = !isMobile;
    });

    const targetUrl = url || (form.action + '?' + new URLSearchParams(new FormData(form)).toString());

    // Cancel any still-in-flight request — its response, if it ever arrives,
    // is guaranteed to be stale, so don't let it complete at all.
    if (tricycleAbortController) {
        tricycleAbortController.abort();
    }
    tricycleAbortController = new AbortController();
    const { signal } = tricycleAbortController;

    fetch(targetUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, signal })
        .then((res) => res.text())
        .then((html) => {
            // Pull the desktop <tbody> out as its own string FIRST. Table-related
            // tags (tbody/tr/td) only parse correctly when inside a <table>
            // context — but wrapping the WHOLE response in <table> foster-parents
            // everything else (modals, mobile cards) out of place and corrupts them.
            // So: table-wrap ONLY the tbody snippet, parse the remaining html normally.
            const tbodyMatch = html.match(/<tbody[^>]*id="tricycle-tbody-desktop"[^>]*>[\s\S]*?<\/tbody>/);
            const tbodyHtml = tbodyMatch ? tbodyMatch[0] : '';
            const restHtml = tbodyMatch ? html.replace(tbodyMatch[0], '') : html;

            const restDoc = new DOMParser().parseFromString(restHtml, 'text/html');
            const tbodyDoc = tbodyHtml
                ? new DOMParser().parseFromString('<table>' + tbodyHtml + '</table>', 'text/html')
                : null;

            const targets = [
                'tricycle-tbody-desktop',
                'tricycle-pagination-desktop',
                'tricycle-cards-mobile',
                'tricycle-pagination-mobile',
                'tricycle-edit-modals',
            ];

            targets.forEach((id) => {
                const doc = id === 'tricycle-tbody-desktop' ? tbodyDoc : restDoc;
                const fresh = doc ? doc.getElementById(id) : null;
                const current = document.getElementById(id);
                if (fresh && current) {
                    current.replaceWith(fresh);
                }
            });

            const params = new URL(targetUrl, window.location.origin).searchParams;
            document.querySelectorAll('[data-filter-scope]').forEach((el) => {
                // Never overwrite a field the user is actively typing in —
                // only sync fields that aren't currently focused.
                if (document.activeElement !== el) {
                    el.value = params.get(el.name) || '';
                }
            });

            window.history.replaceState({}, '', targetUrl);
            attachPaginationLinks();
        })
        .catch((err) => {
            // Aborted requests are expected and harmless — ignore them.
            if (err.name !== 'AbortError') {
                console.error('Tricycle filter fetch failed:', err);
            }
        });
}
window.fetchTricycleResults = fetchTricycleResults;

function attachPaginationLinks() {
    document.querySelectorAll('#tricycle-pagination-desktop a, #tricycle-pagination-mobile a, [data-ajax-link]').forEach((link) => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            fetchTricycleResults(link.href);
        });
    });
}

document.addEventListener('DOMContentLoaded', attachPaginationLinks);