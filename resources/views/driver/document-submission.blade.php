@extends('layouts.app')

@section('title', 'Submit Your Documents')

@section('content')
{{-- $heading, $reviewer, $formAction and $requiredDocs are passed in by the controller --}}

<section class="min-h-screen flex items-center justify-center bg-gray-100 px-3 py-4 sm:px-6 sm:py-10 overflow-x-hidden">
    <div class="w-full min-w-0 max-w-5xl bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden grid grid-cols-[minmax(0,1fr)] md:grid-cols-[minmax(0,2fr)_minmax(0,3fr)]">

        {{-- Left panel: branding + checklist (on mobile it becomes a compact header) --}}
        <aside class="min-w-0 bg-gray-50 text-gray-900 border-b md:border-b-0 md:border-r border-gray-200 px-5 py-5 sm:px-6 sm:py-8 md:px-8 md:py-10 flex flex-col gap-4 md:gap-6 break-words">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-white ring-1 ring-gray-200 shadow-sm flex items-center justify-center overflow-hidden shrink-0">
                    <img src="{{ asset('images/lgu-logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] sm:text-xs uppercase tracking-wide text-red-600 font-semibold">Municipality of Palompon</p>
                    <h1 class="text-lg sm:text-2xl font-bold leading-tight text-gray-900">{{ $heading }}</h1>
                </div>
            </div>

            <p class="text-sm text-gray-600 leading-relaxed">
                Upload the required documents. The {{ $reviewer }} will review your submission.
            </p>

            {{-- Checklist: hidden on small phones to keep the form near the top --}}
            <div class="hidden sm:block">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Required documents</p>
                <ul class="space-y-2.5">
                    @foreach ($requiredDocs as $label)
                        <li class="flex items-center gap-2.5 text-sm text-gray-700">
                            <span class="w-5 h-5 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            {{ $label }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <p class="hidden sm:block mt-auto text-xs text-gray-500">
                Accepted files: PDF, JPG, PNG &middot; Max 5&nbsp;MB each
            </p>
        </aside>

        {{-- Right panel: form --}}
        <div class="min-w-0 px-4 py-5 sm:px-6 sm:py-8 md:px-8 md:py-10">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-50 text-green-700 text-sm px-4 py-3">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="document-submission-form" method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div>
                    <p class="text-sm font-semibold text-gray-900 mb-3">Driver Information</p>
                    <div class="grid grid-cols-[minmax(0,1fr)] sm:grid-cols-[repeat(2,minmax(0,1fr))] gap-3">
                        <div class="min-w-0">
                            <label class="block text-xs font-medium text-gray-600 mb-1">Driver Name</label>
                            <input type="text" name="driver_name" value="{{ old('driver_name') }}" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Contact Number</label>
                            <input type="tel" inputmode="tel" name="contact_number" value="{{ old('contact_number') }}" required
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                        </div>
                        <div @class(['sm:col-span-2' => ! ($showPlateNo ?? true)])>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Body Number <span class="text-gray-400">(if known)</span></label>
                            <input type="text" name="body_number" value="{{ old('body_number') }}"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                        </div>
                        @if ($showPlateNo ?? true)
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Plate No. <span class="text-gray-400">(if known)</span></label>
                                <input type="text" name="plate_no" value="{{ old('plate_no') }}"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-base sm:text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <div class="flex items-baseline justify-between mb-3">
                        <p class="text-sm font-semibold text-gray-900">Documents</p>
                        <p class="text-[11px] text-gray-400 sm:hidden">PDF, JPG, PNG &middot; max 5 MB</p>
                    </div>

                     <div class="grid grid-cols-[minmax(0,1fr)] sm:grid-cols-[repeat(2,minmax(0,1fr))] gap-3">
                        @foreach ($requiredDocs as $field => $label)
                            <label data-upload-tile
                                class="relative flex items-center gap-3 h-16 w-full min-w-0 overflow-hidden rounded-xl border-2 border-dashed border-gray-300 px-3 cursor-pointer hover:border-red-400 hover:bg-red-50/30 focus-within:border-red-500 transition-colors">
                                <span data-upload-icon class="w-10 h-10 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M17 8l-5-5-5 5M12 3v12" />
                                    </svg>
                                </span>
                                <span class="block min-w-0 flex-1 overflow-hidden">
                                    <span class="block w-full text-sm font-medium text-gray-800 truncate">{{ $label }}</span>
                                    <span data-upload-name class="block w-full text-xs text-gray-400 truncate">Tap to choose file</span>
                                </span>
                                <input type="file" name="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png" required
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            </label>
                        @endforeach

                        {{-- Submit button fills the last empty grid cell on desktop --}}
                        <button type="button" onclick="openReviewModal()"
                            class="h-16 w-full min-w-0 rounded-xl bg-red-600 text-white px-6 text-sm font-semibold hover:bg-red-700 active:bg-red-800 transition-colors">
                            Review &amp; Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- Review modal: shown before the form is actually submitted --}}
<div id="review-modal" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6">
    <div class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 shrink-0">
            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-full bg-red-50 text-red-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </span>
                <div>
                    <h3 class="font-semibold text-gray-900 text-lg">Review your submission</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Please check everything before submitting.</p>
                </div>
            </div>
            <button type="button" onclick="closeReviewModal()" class="text-gray-400 hover:text-gray-600" title="Close">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-5 py-4 space-y-5">
            <div>
                <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Driver Information
                </p>
                <div id="review-info" class="rounded-xl border border-gray-200 divide-y divide-gray-100"></div>
            </div>

            <div>
                <p class="flex items-center gap-1.5 text-xs font-semibold uppercase tracking-wide text-gray-500 mb-2">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                    </svg>
                    Documents
                </p>
                <div id="review-docs" class="space-y-2"></div>
            </div>
        </div>

        <div class="flex gap-3 px-5 py-4 border-t border-gray-100 shrink-0">
            <button type="button" onclick="closeReviewModal()"
                class="flex-1 inline-flex items-center justify-center gap-2 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Go Back &amp; Edit
            </button>
            <button type="button" id="review-confirm-btn" onclick="confirmReviewSubmit()"
                class="flex-1 inline-flex items-center justify-center gap-2 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                <svg id="review-confirm-icon" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span id="review-confirm-label">Confirm &amp; Submit</span>
            </button>
        </div>
    </div>
</div>

<script>
    let reviewObjectUrls = [];

    function reviewIcon(path, classes = 'w-4 h-4') {
        return '<svg class="' + classes + '" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">'
            + '<path stroke-linecap="round" stroke-linejoin="round" d="' + path + '"/></svg>';
    }

    function openReviewModal() {
        const form = document.getElementById('document-submission-form');

        // Run the browser's normal required-field / file checks first.
        if (!form.reportValidity()) return;

        // Driver information
        const infoFields = [
            ['Driver Name', 'driver_name', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
            ['Contact Number', 'contact_number', 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z'],
            ['Body Number', 'body_number', 'M7 20l4-16m2 16l4-16M6 9h14M4 15h14'],
            ['Plate No.', 'plate_no', 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'], // only exists on the tricycle form
        ];

        const infoWrap = document.getElementById('review-info');
        infoWrap.innerHTML = '';

        infoFields.forEach(([label, name, iconPath]) => {
            const input = form.elements[name];
            if (!input) return;

            const row = document.createElement('div');
            row.className = 'flex items-center justify-between gap-3 px-4 py-2.5 text-sm';

            const l = document.createElement('span');
            l.className = 'flex items-center gap-2 text-gray-500 shrink-0';
            l.innerHTML = '<span class="w-7 h-7 rounded-lg bg-red-50 text-red-600 flex items-center justify-center shrink-0">'
                + reviewIcon(iconPath) + '</span>';
            const lt = document.createElement('span');
            lt.textContent = label;
            l.appendChild(lt);

            const v = document.createElement('span');
            v.className = 'font-semibold text-gray-900 text-right break-words min-w-0';
            v.textContent = input.value.trim() || '—';

            row.append(l, v);
            infoWrap.appendChild(row);
        });

        // Documents
        const docsWrap = document.getElementById('review-docs');
        docsWrap.innerHTML = '';
        reviewObjectUrls.forEach((u) => URL.revokeObjectURL(u));
        reviewObjectUrls = [];

        document.querySelectorAll('[data-upload-tile]').forEach((tile) => {
            const input = tile.querySelector('input[type="file"]');
            const label = tile.querySelector('.text-gray-800').textContent.trim();
            const file = input.files[0];
            if (!file) return;

            const row = document.createElement('div');
            row.className = 'flex items-center gap-3 rounded-xl border border-gray-200 p-2.5';

            const thumb = document.createElement('div');
            thumb.className = 'w-12 h-12 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden shrink-0';

            if (file.type.startsWith('image/')) {
                const url = URL.createObjectURL(file);
                reviewObjectUrls.push(url);
                const img = document.createElement('img');
                img.src = url;
                img.alt = label;
                img.className = 'w-full h-full object-cover';
                thumb.appendChild(img);
            } else {
                thumb.className = 'w-12 h-12 rounded-lg bg-red-50 border border-red-100 text-red-600 flex flex-col items-center justify-center overflow-hidden shrink-0';
                thumb.innerHTML = reviewIcon('M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'w-5 h-5')
                    + '<span class="text-[9px] font-bold leading-none mt-0.5">PDF</span>';
            }

            const text = document.createElement('div');
            text.className = 'min-w-0 flex-1';

            const t1 = document.createElement('p');
            t1.className = 'text-sm font-medium text-gray-800 truncate';
            t1.textContent = label;

            const t2 = document.createElement('p');
            t2.className = 'text-xs text-gray-500 truncate';
            t2.textContent = file.name + ' · ' + (file.size / 1024 / 1024).toFixed(2) + ' MB';

            text.append(t1, t2);

            const check = document.createElement('span');
            check.className = 'w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center shrink-0';
            check.innerHTML = reviewIcon('M5 13l4 4L19 7', 'w-3.5 h-3.5');

            row.append(thumb, text, check);
            docsWrap.appendChild(row);
        });

        const modal = document.getElementById('review-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeReviewModal() {
        const modal = document.getElementById('review-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');

        reviewObjectUrls.forEach((u) => URL.revokeObjectURL(u));
        reviewObjectUrls = [];
    }

    function confirmReviewSubmit() {
        const btn = document.getElementById('review-confirm-btn');
        btn.disabled = true;
        document.getElementById('review-confirm-label').textContent = 'Submitting...';
        document.getElementById('review-confirm-icon').classList.add('animate-pulse');

        // Only now does the form actually post and get saved in the database.
        document.getElementById('document-submission-form').submit();
    }

    // Reset the button if the user comes back via the browser's back button
    window.addEventListener('pageshow', () => {
        const btn = document.getElementById('review-confirm-btn');
        if (btn) {
            btn.disabled = false;
            document.getElementById('review-confirm-label').textContent = 'Confirm & Submit';
            document.getElementById('review-confirm-icon').classList.remove('animate-pulse');
        }
    });
</script>

<script>
    document.querySelectorAll('[data-upload-tile]').forEach((tile) => {
        const input = tile.querySelector('input[type="file"]');
        const name = tile.querySelector('[data-upload-name]');
        const icon = tile.querySelector('[data-upload-icon]');

        input.addEventListener('change', () => {
            const file = input.files[0];

            if (file) {
                name.textContent = file.name;
                name.classList.remove('text-gray-400');
                name.classList.add('text-green-700', 'font-medium');
                tile.classList.remove('border-gray-300', 'border-dashed');
                tile.classList.add('border-green-500', 'bg-green-50/40');
                icon.classList.remove('bg-red-50', 'text-red-600');
                icon.classList.add('bg-green-100', 'text-green-600');
            } else {
                name.textContent = 'Tap to choose file';
                name.classList.add('text-gray-400');
                name.classList.remove('text-green-700', 'font-medium');
                tile.classList.add('border-gray-300', 'border-dashed');
                tile.classList.remove('border-green-500', 'bg-green-50/40');
                icon.classList.add('bg-red-50', 'text-red-600');
                icon.classList.remove('bg-green-100', 'text-green-600');
            }
        });
    });
</script>
@endsection