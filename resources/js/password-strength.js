function checkPasswordLength(input, hintId) {
    const hint = document.getElementById(hintId);
    if (!hint) return;

    const length = input.value.length;

    if (length === 0) {
        hint.textContent = hint.dataset.default || hint.textContent;
        hint.className = 'text-xs mt-1 text-gray-400';
        input.classList.remove('border-red-500', 'border-green-500');
        input.classList.add('border-gray-300');
        return;
    }

    if (length < 8) {
        hint.textContent = `${8 - length} more character${8 - length === 1 ? '' : 's'} needed`;
        hint.className = 'text-xs mt-1 text-red-500';
        input.classList.remove('border-gray-300', 'border-green-500');
        input.classList.add('border-red-500');
    } else {
        hint.textContent = 'Looks good';
        hint.className = 'text-xs mt-1 text-green-600';
        input.classList.remove('border-gray-300', 'border-red-500');
        input.classList.add('border-green-500');
    }
}
window.checkPasswordLength = checkPasswordLength;