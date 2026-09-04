document.addEventListener('DOMContentLoaded', () => {
    const input = document.getElementById('tricycle-mayors-permit-import-input');
    const filenameLabel = document.getElementById('tricycle-mayors-permit-import-filename');
    const submitBtn = document.getElementById('tricycle-mayors-permit-import-submit');
    const dropzone = document.getElementById('tricycle-mayors-permit-import-dropzone');

    if (!input || !filenameLabel || !submitBtn || !dropzone) return;

    function setFile(file) {
        if (!file) {
            filenameLabel.textContent = 'Click to choose a file, or drag it here';
            submitBtn.disabled = true;
            return;
        }
        filenameLabel.textContent = file.name;
        submitBtn.disabled = false;
    }

    input.addEventListener('change', () => setFile(input.files[0]));

    dropzone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropzone.classList.add('border-red-400', 'bg-red-50/30');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('border-red-400', 'bg-red-50/30');
    });

    dropzone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropzone.classList.remove('border-red-400', 'bg-red-50/30');
        if (event.dataTransfer.files.length) {
            input.files = event.dataTransfer.files;
            setFile(input.files[0]);
        }
    });
});