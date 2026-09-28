@extends('layouts.app')

@section('title', 'Submit Your Documents')

@section('content')
{{-- $heading, $reviewer, $formAction and $requiredDocs are passed in by the controller --}}

<section class="min-h-screen flex items-center justify-center bg-gray-100 px-3 py-4 sm:px-6 sm:py-10 overflow-x-hidden">
    <div class="w-full min-w-0 max-w-5xl bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden grid grid-cols-[minmax(0,1fr)] md:grid-cols-[minmax(0,2fr)_minmax(0,3fr)]">

        {{-- Left panel: branding + checklist (on mobile it becomes a compact header) --}}
        <aside class="min-w-0 bg-red-600 text-white px-5 py-5 sm:px-6 sm:py-8 md:px-8 md:py-10 flex flex-col gap-4 md:gap-6 break-words">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 sm:w-14 sm:h-14 rounded-full bg-white flex items-center justify-center overflow-hidden shrink-0">
                    <img src="{{ asset('images/lgu-logo.png') }}" alt="Logo" class="w-8 h-8 sm:w-10 sm:h-10 object-contain">
                </div>
                <div class="min-w-0">
                    <p class="text-[11px] sm:text-xs uppercase tracking-wide text-red-100">Municipality of Palompon</p>
                    <h1 class="text-lg sm:text-2xl font-bold leading-tight">{{ $heading }}</h1>
                </div>
            </div>

            <p class="text-sm text-red-100 leading-relaxed">
                Upload the required documents. The {{ $reviewer }} will review your submission.
            </p>

            {{-- Checklist: hidden on small phones to keep the form near the top --}}
            <div class="hidden sm:block">
                <p class="text-xs font-semibold uppercase tracking-wide text-red-100 mb-2">Required documents</p>
                <ul class="space-y-2">
                    @foreach ($requiredDocs as $label)
                        <li class="flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4 shrink-0 text-red-200" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $label }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <p class="hidden sm:block mt-auto text-xs text-red-100">
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

            <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" class="space-y-5">
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
                        <button type="submit"
                            class="h-16 w-full min-w-0 rounded-xl bg-red-600 text-white px-6 text-sm font-semibold hover:bg-red-700 active:bg-red-800 transition-colors">
                            Submit Documents
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

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