@props(['permit'])

@php
    $statusColors = [
        'active' => 'bg-teal-500',
        'expired' => 'bg-red-600',
    ];
@endphp

<div class="rounded-2xl border border-gray-200 p-4">
    <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
            <p class="text-gray-900 font-medium truncate">{{ $permit->control_no }}</p>
            @if ($permit->tricycle)
                <p class="text-gray-500 text-sm truncate">{{ $permit->tricycle->body_number }} — {{ $permit->tricycle->name }}</p>
            @endif
            <div class="flex flex-wrap gap-1 mt-2">
                <span class="inline-block rounded-full {{ $statusColors[$permit->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
                    {{ ucfirst($permit->status) }}
                </span>
                <span class="inline-block rounded-full bg-blue-600 text-white text-xs font-semibold px-2.5 py-1">
                    {{ $permit->motorized_operation }}
                </span>
                <span class="inline-block rounded-full bg-amber-500 text-white text-xs font-semibold px-2.5 py-1">
                    {{ $permit->quarter }}
                </span>
            </div>
            <div class="flex flex-wrap gap-1 mt-1.5">
                <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1">
                    {{ $permit->or_no }}
                </span>
                <span class="inline-block rounded-full bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1">
                    ₱{{ number_format($permit->amount_paid, 2) }}
                </span>
            </div>
            <div class="flex items-center gap-1 mt-1.5">
                <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                    {{ $permit->issue_date->format('M-d-y') }}
                </span>
                <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">
                    {{ $permit->expiry_date->format('M-d-y') }}
                </span>
            </div>
            <p class="text-gray-500 text-xs mt-1.5">{{ $permit->mayor }} · {{ $permit->issued_at }}</p>
        </div>
    </div>
</div>