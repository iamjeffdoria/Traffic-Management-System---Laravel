function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    if (!dropdown) return;

    const isOpen = !dropdown.classList.contains('hidden');

    // Close any other open dropdowns first
    document.querySelectorAll('[id$="-dropdown"]').forEach((el) => {
        el.classList.add('hidden');
    });

    if (!isOpen) {
        dropdown.classList.remove('hidden');
    }
}
window.toggleDropdown = toggleDropdown;

// Sidebar collapsible groups (Potpot / Tricycle, etc.) — plain JS toggle
// instead of a checkbox + group-has-[] CSS trick, since that combo is
// unreliable across Tailwind builds for dynamically-named nested groups.
function toggleSidebarGroup(id) {
    const panel = document.getElementById(id);
    const chevron = document.getElementById(id + '-chevron');
    if (!panel) return;

    panel.classList.toggle('hidden');
    if (chevron) chevron.classList.toggle('rotate-180');
}
window.toggleSidebarGroup = toggleSidebarGroup;

// Close dropdown when clicking outside of it
document.addEventListener('click', function (event) {
    document.querySelectorAll('[id$="-dropdown"]').forEach((dropdown) => {
        if (dropdown.classList.contains('hidden')) return;

        const trigger = dropdown.previousElementSibling;
        const clickedInside = dropdown.contains(event.target) || (trigger && trigger.contains(event.target));

        if (!clickedInside) {
            dropdown.classList.add('hidden');
        }
    });
});