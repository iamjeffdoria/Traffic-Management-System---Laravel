function filterAdminTable() {
    const nameInput = document.getElementById('filter-name') || document.getElementById('filter-name-mobile');
    const emailInput = document.getElementById('filter-email') || document.getElementById('filter-email-mobile');
    const roleInput = document.getElementById('filter-role') || document.getElementById('filter-role-mobile');

    const nameFilter = (nameInput?.value || '').toLowerCase().trim();
    const emailFilter = (emailInput?.value || '').toLowerCase().trim();
    const roleFilter = roleInput?.value || '';

    const rows = document.querySelectorAll('[data-admin-row]');

    rows.forEach((row) => {
        const name = (row.dataset.name || '').toLowerCase();
        const email = (row.dataset.email || '').toLowerCase();
        const role = row.dataset.role || '';

        const matchesName = name.includes(nameFilter);
        const matchesEmail = email.includes(emailFilter);
        const matchesRole = roleFilter === '' || role === roleFilter;

        const isMatch = matchesName && matchesEmail && matchesRole;

        row.classList.toggle('hidden', !isMatch);
    });
}
window.filterAdminTable = filterAdminTable;