function togglePassword(inputId, openIconId, closedIconId) {
    const input = document.getElementById(inputId);
    const eyeOpen = document.getElementById(openIconId);
    const eyeClosed = document.getElementById(closedIconId);

    const isPassword = input.type === 'password';
    input.type = isPassword ? 'text' : 'password';
    eyeOpen.classList.toggle('hidden', isPassword);
    eyeClosed.classList.toggle('hidden', !isPassword);
}

// Expose globally so it can be called from onclick="" in Blade views
window.togglePassword = togglePassword;