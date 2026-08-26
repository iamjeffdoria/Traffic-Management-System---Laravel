function showPageLoading() {
    const overlay = document.getElementById('page-loading-overlay');
    if (overlay) {
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
    }
}
window.showPageLoading = showPageLoading;

document.addEventListener('DOMContentLoaded', () => {
    // Show spinner on any sidebar nav link click (not modals/buttons, just real page links)
    document.querySelectorAll('aside nav a[href]').forEach((link) => {
        link.addEventListener('click', () => {
            // Skip if it's just the mobile hamburger toggle or opens in new tab
            if (link.target === '_blank') return;
            showPageLoading();
        });
    });

    // Also show it on any full-page form submit (e.g. logout)
    document.querySelectorAll('aside form').forEach((form) => {
        form.addEventListener('submit', () => {
            showPageLoading();
        });
    });
});