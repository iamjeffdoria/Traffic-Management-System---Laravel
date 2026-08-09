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