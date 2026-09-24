@extends('layouts.app')

@section('title', 'Scanner')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="scanner" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Scanner" />

        <div class="max-w-2xl mx-auto px-6 py-8">
            <div class="text-center mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Document Scanner</h2>
                <p class="text-sm text-gray-500 mt-1">Point the camera at a QR code, or upload a photo of one.</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
                <div id="reader" class="rounded-xl overflow-hidden bg-black aspect-square w-full"></div>
                <div id="file-reader" class="hidden"></div>

                <div id="scanner-status" class="flex items-center justify-center gap-2 text-sm text-gray-500 mt-4">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Waiting for a QR code...
                </div>

                <div class="flex items-center gap-3 my-4">
                    <div class="flex-1 h-px bg-gray-100"></div>
                    <span class="text-xs text-gray-400">OR</span>
                    <div class="flex-1 h-px bg-gray-100"></div>
                </div>

                <label for="file-input"
                    class="flex items-center justify-center gap-2 w-full rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors cursor-pointer">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14M14 8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span id="file-label" class="truncate">Upload a Photo</span>
                </label>
                <input type="file" id="file-input" class="hidden">

                <button type="button" id="rescan-btn" onclick="restartScanner()"
                    class="hidden w-full mt-4 rounded-full bg-gray-900 text-white px-6 py-2.5 text-sm font-semibold hover:bg-gray-800 transition-colors">
                    Scan Another
                </button>
            </div>

        </div>
    </div>
</div>

<!-- Result modal -->
<div id="result-card" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div onclick="closeResultModal()" class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm max-h-[90vh] overflow-y-auto">
        <button type="button" onclick="closeResultModal()" class="absolute right-4 top-4 text-gray-400 hover:text-gray-600 z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div id="result-header" class="px-6 py-5 text-center border-b border-gray-100">
            <div id="result-icon" class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3"></div>
            <h3 id="result-title" class="font-semibold text-gray-900 text-lg"></h3>
            <p id="result-subtitle" class="text-sm text-gray-500 mt-1"></p>
        </div>
        <div id="result-fields" class="px-6 py-2"></div>

        <div class="px-6 py-4">
            <button type="button" onclick="closeResultModal(); restartScanner();"
                class="w-full rounded-full bg-gray-900 text-white px-6 py-2.5 text-sm font-semibold hover:bg-gray-800 transition-colors">
                Scan Another
            </button>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script>
    let html5QrCode;
    let isProcessing = false;
    let cameraRunning = false;

    const scannerConfig = {
        fps: 15,
        qrbox: (viewfinderWidth, viewfinderHeight) => {
            const size = Math.floor(Math.min(viewfinderWidth, viewfinderHeight) * 0.8);
            return { width: size, height: size };
        },
        aspectRatio: 1.0,
        videoConstraints: {
            facingMode: "environment",
            width: { ideal: 1920 },
            height: { ideal: 1080 },
        },
    };

    function startScanner() {
        html5QrCode = new Html5Qrcode("reader", { formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE], verbose: false });

        html5QrCode.start({ facingMode: "environment" }, scannerConfig, onScanSuccess)
            .then(() => { cameraRunning = true; })
            .catch(() => startWithAnyCamera());
    }

    function startWithAnyCamera() {
        Html5Qrcode.getCameras().then((cameras) => {
            if (!cameras || cameras.length === 0) {
                throw new Error('No camera found');
            }
            return html5QrCode.start(cameras[0].id, scannerConfig, onScanSuccess);
        }).then(() => { cameraRunning = true; })
        .catch(() => {
            document.getElementById('scanner-status').innerHTML =
                '<span class="text-amber-600">No usable camera found — use "Upload a Photo" below instead.</span>';
        });
    }

    function onScanSuccess(decodedText) {
        if (isProcessing) return;
        isProcessing = true;

        if (cameraRunning) {
            html5QrCode.pause(true);
        }

        verifyToken(decodedText);
    }

    function verifyToken(token) {
        document.getElementById('scanner-status').innerHTML =
            '<span class="text-gray-500">Verifying...</span>';

        window.axios.post('{{ route('admin.scanner.verify') }}', { token: token })
            .then((res) => showResult(res.data))
            .catch((err) => showResult(err.response?.data ?? { ok: false, message: 'Something went wrong.' }));
    }

    // Fallback path: decode a QR from an uploaded image file instead of the
    // live camera feed. This sidesteps webcam focus/resolution limitations
    // entirely and is also genuinely useful for verifying a scanned or
    // photographed copy of a document sent in some other way.
    //
    // html5-qrcode refuses to run scanFile() while a live camera scan is
    // still active, so the running camera must be stopped first.
    document.getElementById('file-input').addEventListener('change', function (event) {
        const file = event.target.files[0];
        if (!file || isProcessing) return;
        isProcessing = true;

        document.getElementById('file-label').textContent = file.name;

        document.getElementById('scanner-status').innerHTML =
            '<span class="text-gray-500">Reading image...</span>';

        const runFileScan = () => {
            const fileScanner = new Html5Qrcode("file-reader", { formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE], verbose: false });

            fileScanner.scanFile(file, true)
                .then((decodedText) => verifyToken(decodedText))
                .catch(() => {
                    showResult({ ok: false, message: 'No QR code could be found in that image.' });
                })
                .finally(() => fileScanner.clear());
        };

        if (cameraRunning) {
            html5QrCode.stop().then(() => {
                cameraRunning = false;
                html5QrCode.clear();
                runFileScan();
            }).catch(runFileScan);
        } else {
            runFileScan();
        }
    });

    function showResult(data) {
        const card = document.getElementById('result-card');
        const icon = document.getElementById('result-icon');
        const title = document.getElementById('result-title');
        const subtitle = document.getElementById('result-subtitle');
        const fieldsWrap = document.getElementById('result-fields');

        fieldsWrap.innerHTML = '';

        if (data.ok) {
            icon.className = 'w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 bg-emerald-50';
            icon.innerHTML = '<svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
            title.textContent = data.label;
            subtitle.textContent = data.status ? ('Status: ' + data.status.charAt(0).toUpperCase() + data.status.slice(1)) : 'Verified document';

            Object.entries(data.fields).forEach(([label, value]) => {
                const row = document.createElement('div');
                row.className = 'flex justify-between gap-3 py-2.5 border-b border-gray-100 last:border-b-0 text-sm';
                row.innerHTML = `<span class="text-gray-500">${label}</span><span class="font-semibold text-gray-900 text-right">${value}</span>`;
                fieldsWrap.appendChild(row);
            });

            if (typeof showToast === 'function') {
                showToast('Document verified successfully.', 'success');
            }
        } else {
            icon.className = 'w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 bg-red-50';
            icon.innerHTML = '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
            title.textContent = 'Not Verified';
            subtitle.textContent = data.message || 'This QR code could not be verified.';

            if (typeof showToast === 'function') {
                showToast(data.message || 'This QR code could not be verified.', 'error');
            }
        }

        card.classList.remove('hidden');
        card.classList.add('flex');
        document.getElementById('rescan-btn').classList.remove('hidden');
        document.getElementById('scanner-status').classList.add('hidden');
    }

    function closeResultModal() {
        const card = document.getElementById('result-card');
        card.classList.add('hidden');
        card.classList.remove('flex');
    }

    function restartScanner() {
        isProcessing = false;
        document.getElementById('file-input').value = '';
        document.getElementById('file-label').textContent = 'Upload a Photo';
        closeResultModal();
        document.getElementById('rescan-btn').classList.add('hidden');
        document.getElementById('scanner-status').classList.remove('hidden');
        document.getElementById('scanner-status').innerHTML =
            '<span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Waiting for a QR code...';

        if (cameraRunning) {
            html5QrCode.resume();
        } else {
            startScanner();
        }
    }

    document.addEventListener('DOMContentLoaded', startScanner);
</script>
@endsection