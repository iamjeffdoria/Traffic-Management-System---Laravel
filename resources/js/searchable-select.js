function initSearchableSelect(root) {
    const input = root.querySelector('[data-search-input]');
    const hidden = root.querySelector('[data-search-hidden]');
    const dropdown = root.querySelector('[data-search-dropdown]');
    const noResults = dropdown.querySelector('[data-no-results]');
    const onSelectFn = root.dataset.onSelect;
    const remoteUrl = root.dataset.remoteUrl; // set => options come from the server
    let suppressNextFocus = false;
    let remoteTimer = null;
    let remoteController = null;

    const optionClass = 'w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-red-50 transition-colors';

    function openDropdown() {
        dropdown.classList.remove('hidden');
    }

    function closeDropdown() {
        dropdown.classList.add('hidden');
    }

    function selectOption(opt) {
        hidden.value = opt.dataset.id;
        input.value = opt.dataset.label;
        input.classList.remove('border-red-600', 'ring-2', 'ring-red-600');
        suppressNextFocus = true;
        closeDropdown();
        input.blur();

        if (onSelectFn && typeof window[onSelectFn] === 'function') {
            window[onSelectFn](opt, root);
        }
    }

    // mousedown (not click) + preventDefault stops the input from blurring
    // mid-selection, which would reopen and re-filter the dropdown.
    function bindOption(opt) {
        opt.addEventListener('mousedown', (event) => {
            event.preventDefault();
            selectOption(opt);
        });
    }

    // ---------- Local mode (options already in the page) ----------
    const localOptions = Array.from(dropdown.querySelectorAll('[data-option]'));
    localOptions.forEach(bindOption);

    function filterOptions(query = input.value) {
        const normalizedQuery = query.trim().toLowerCase();
        let anyVisible = false;

        localOptions.forEach((opt) => {
            const match = opt.dataset.search.includes(normalizedQuery);
            opt.classList.toggle('hidden', !match);
            if (match) anyVisible = true;
        });

        if (noResults) {
            noResults.classList.toggle('hidden', anyVisible);
        }
    }

    // ---------- Remote mode (options fetched from the server) ----------
    function renderRemoteOptions(items) {
        dropdown.querySelectorAll('[data-option]').forEach((el) => el.remove());

        items.forEach((item) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.setAttribute('data-option', '');
            btn.className = optionClass;
            btn.dataset.id = item.id;
            btn.dataset.label = item.label;

            // Extra fields (name, plate, address...) become data-* attributes,
            // so the existing onSelect callbacks keep working unchanged.
            Object.entries(item.data || {}).forEach(([key, value]) => {
                btn.dataset[key] = value ?? '';
            });

            btn.textContent = item.label;
            bindOption(btn);
            dropdown.insertBefore(btn, noResults);
        });

        if (noResults) {
            noResults.classList.toggle('hidden', items.length > 0);
        }
    }

    function loadRemote(query) {
        if (remoteController) remoteController.abort();
        remoteController = new AbortController();

        const separator = remoteUrl.includes('?') ? '&' : '?';
        const url = remoteUrl + separator + 'q=' + encodeURIComponent(query.trim());

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
            signal: remoteController.signal,
        })
            .then((res) => res.json())
            .then(renderRemoteOptions)
            .catch((err) => {
                if (err.name !== 'AbortError') console.error('Search failed:', err);
            });
    }

    // ---------- Events ----------
    input.addEventListener('focus', () => {
        if (suppressNextFocus) {
            suppressNextFocus = false;
            return;
        }

        if (remoteUrl) {
            loadRemote('');
        } else {
            // Show the full list on focus rather than filtering against the
            // selected option's full label (which would show "No results").
            filterOptions('');
        }
        openDropdown();
    });

    input.addEventListener('input', () => {
        hidden.value = '';

        if (remoteUrl) {
            clearTimeout(remoteTimer);
            remoteTimer = setTimeout(() => loadRemote(input.value), 250);
        } else {
            filterOptions();
        }
        openDropdown();
    });

    document.addEventListener('click', (event) => {
        if (!root.contains(event.target)) {
            closeDropdown();
        }
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

document.addEventListener('DOMContentLoaded', () => {
    initAllSearchableSelects();

    // The AJAX table refresh swaps the edit modals for fresh copies,
    // so wire up any new dropdowns automatically.
    new MutationObserver(() => initAllSearchableSelects())
        .observe(document.body, { childList: true, subtree: true });
});
window.initAllSearchableSelects = initAllSearchableSelects;