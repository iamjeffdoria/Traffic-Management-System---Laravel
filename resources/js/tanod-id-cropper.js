import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

// Matches the photo frame on the printed card: 31% x 30% of a 3.4in x 4.75in card.
const RATIO = (31 * 3.4) / (30 * 4.75); // ~0.7396
const OUTPUT_WIDTH = 600;

let cropper = null;
let activeInput = null;
let objectUrl = null;

function cleanupCropper() {
    if (cropper) {
        cropper.destroy();
        cropper = null;
    }
    if (objectUrl) {
        URL.revokeObjectURL(objectUrl);
        objectUrl = null;
    }
    const img = document.getElementById('tanod-id-crop-image');
    if (img) img.removeAttribute('src');
}

function openTanodIdCropper(input) {
    const file = input.files[0];
    if (!file) return;

    activeInput = input;
    objectUrl = URL.createObjectURL(file);

    // Open the modal first so the cropper can measure its container.
    openModal('tanod-id-crop-modal');

    const img = document.getElementById('tanod-id-crop-image');
    img.onload = () => {
        if (cropper) cropper.destroy();
        cropper = new Cropper(img, {
            aspectRatio: RATIO,
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 1,
            cropBoxMovable: false,
            cropBoxResizable: false,
            toggleDragModeOnDblclick: false,
            background: false,
        });
    };
    img.src = objectUrl;
}
window.openTanodIdCropper = openTanodIdCropper;

function cancelTanodIdCrop() {
    if (activeInput) activeInput.value = '';
    activeInput = null;
    cleanupCropper();
    closeModal('tanod-id-crop-modal');
}
window.cancelTanodIdCrop = cancelTanodIdCrop;

function applyTanodIdCrop() {
    if (!cropper || !activeInput) return;

    const input = activeInput;
    const originalName = (input.files[0]?.name || 'photo').replace(/\.[^.]+$/, '');

    cropper.getCroppedCanvas({
        width: OUTPUT_WIDTH,
        height: Math.round(OUTPUT_WIDTH / RATIO),
        fillColor: '#ffffff',
        imageSmoothingQuality: 'high',
    }).toBlob((blob) => {
        const cropped = new File([blob], originalName + '.jpg', { type: 'image/jpeg' });
        const transfer = new DataTransfer();
        transfer.items.add(cropped);
        input.files = transfer.files;

        if (typeof window.previewAvatar === 'function') window.previewAvatar(input);

        activeInput = null;
        cleanupCropper();
        closeModal('tanod-id-crop-modal');
    }, 'image/jpeg', 0.92);
}
window.applyTanodIdCrop = applyTanodIdCrop;

window.zoomTanodIdCrop = (amount) => cropper && cropper.zoom(amount);
window.rotateTanodIdCrop = (degrees) => cropper && cropper.rotate(degrees);