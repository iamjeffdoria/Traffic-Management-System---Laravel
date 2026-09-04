function initSearchableSelect(root) {
    const input = root.querySelector('[data-search-input]');
    const hidden = root.querySelector('[data-search-hidden]');
    const dropdown = root.querySelector('[data-search-dropdown]');
    const noResults = dropdown.querySelector('[data-no-results]');
    const options = Array.from(dropdown.querySelectorAll('[data-option]'));
    const onSelectFn = root.dataset.onSelect;
    let suppressNextFocus = false;

    function openDropdown() {
        dropdown.classList.remove('hidden');
    }

    function closeDropdown() {
        dropdown.classList.add('hidden');
    }

    function filterOptions(query = input.value) {
        const normalizedQuery = query.trim().toLowerCase();
        let anyVisible = false;

        options.forEach((opt) => {
            const match = opt.dataset.search.includes(normalizedQuery);
            opt.classList.toggle('hidden', !match);
            if (match) anyVisible = true;
        });

        if (noResults) {
            noResults.classList.toggle('hidden', anyVisible);
        }
    }

    input.addEventListener('focus', () => {
        if (suppressNextFocus) {
            suppressNextFocus = false;
            return;
        }
        // Show the full list on focus rather than re-filtering against
        // whatever's already displayed (e.g. the selected option's full
        // label on edit modals) — that text won't match the plain
        // data-search string and would wrongly show "No results".
        filterOptions('');
        openDropdown();
    });

    input.addEventListener('input', () => {
        hidden.value = '';
        filterOptions();
        openDropdown();
    });

    document.addEventListener('click', (event) => {
        if (!root.contains(event.target)) {
            closeDropdown();
        }
    });

    options.forEach((opt) => {
        // mousedown (not click) + preventDefault stops the input from ever
        // properly blurring mid-selection, which is what was causing the
        // dropdown to silently reopen and re-filter against the full label.
        opt.addEventListener('mousedown', (event) => {
            event.preventDefault();

            hidden.value = opt.dataset.id;
            input.value = opt.dataset.label;
            input.classList.remove('border-red-600', 'ring-2', 'ring-red-600');
            suppressNextFocus = true;
            closeDropdown();
            input.blur();

            if (onSelectFn && typeof window[onSelectFn] === 'function') {
                window[onSelectFn](opt, root);
            }
        });
    });
}

function initAllSearchableSelects(scope = document) {
    scope.querySelectorAll('[data-searchable-select]').forEach((root) => {
        if (!root.dataset.searchableInitialized) {
            initSearchableSelect(root);
            root.dataset.searchableInitialized = 'true';
        }
    });
}

// Required-field validation, since native `required` doesn't apply to hidden inputs.
document.addEventListener('submit', (event) => {
    const form = event.target;
    const selects = form.querySelectorAll ? form.querySelectorAll('[data-searchable-select]') : [];
    let valid = true;

    selects.forEach((root) => {
        const hidden = root.querySelector('[data-search-hidden]');
        const input = root.querySelector('[data-search-input]');
        if (hidden && hidden.hasAttribute('required') && !hidden.value) {
            valid = false;
            input.classList.add('border-red-600', 'ring-2', 'ring-red-600');
            input.focus();
        }
    });

    if (!valid) {
        event.preventDefault();
    }
});

document.addEventListener('DOMContentLoaded', () => initAllSearchableSelects());
window.initAllSearchableSelects = initAllSearchableSelects;