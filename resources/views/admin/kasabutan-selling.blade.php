@extends('layouts.app')

@section('title', 'Kasabutan - Selling')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="potpot-kasabutan-selling" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Kasabutan - Selling" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showToast(@json(session('success')));
                    });
                </script>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3 max-h-64 overflow-y-auto">
                    <ul class="space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- Reopen the modal so the user can fix the fields (old values are kept) --}}
                <script>
                    document.addEventListener('DOMContentLoaded', () => openModal('create-kasabutan-selling-modal'));
                </script>
            @endif

            <x-kasabutan-selling-toolbar />

            <x-kasabutan-selling-create-modal />

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto isolate" style="max-height: 600px; overflow-y: auto;">
                <table class="w-full text-sm border-separate border-spacing-0 table-fixed">
                    <colgroup>
                        <col class="w-[22%]">
                        <col class="w-[12%]">
                        <col class="w-[24%]">
                        <col class="w-[13%]">
                        <col class="w-[16%]">
                        <col class="w-[13%]">
                    </colgroup>
                    <thead class="text-left text-gray-900 bg-gray-50 sticky top-0 z-30">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold">Owner / Address</th>
                            <th class="px-6 py-3 font-bold">Body No.</th>
                            <th class="px-6 py-3 font-bold">Buyer / Address</th>
                            <th class="px-6 py-3 font-bold">Amount</th>
                            <th class="px-6 py-3 font-bold">Witness</th>
                            <th class="px-6 py-3 font-bold">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @forelse ($sellings as $selling)
                            <x-kasabutan-selling-table-row :selling="$selling" />
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm">No Kasabutan - Selling records yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="hidden lg:block mt-4">
                @unless ($sellings->isEmpty())
                    {{ $sellings->links() }}
                @endunless
            </div>

            <!-- Mobile cards -->
            <div class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
                @forelse ($sellings as $selling)
                    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
                        <p class="text-gray-900 font-medium truncate">{{ $selling->owner }}</p>
                        <p class="text-gray-500 text-sm truncate">{{ $selling->barangay }}, {{ $selling->municipality }}</p>
                        <p class="text-gray-700 text-sm mt-1.5">Sold to <span class="font-medium">{{ $selling->buyer }}</span> — {{ $selling->buyer_address }}</p>
                        <div class="flex flex-wrap gap-1 mt-2">
                            <span class="inline-block rounded-full bg-cyan-600 text-white text-xs font-mono px-2.5 py-1">{{ $selling->body_number }}</span>
                            <span class="inline-block rounded-full bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1">₱{{ number_format($selling->amount, 2) }}</span>
                            <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $selling->date->format('M-d-y') }}</span>
                        </div>
                        <p class="text-gray-500 text-xs mt-1.5">Witness: {{ $selling->witness }}</p>
                    </div>
                @empty
                    <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                        No Kasabutan - Selling records yet.
                    </div>
                @endforelse
            </div>

            <div class="lg:hidden mt-4">
                @unless ($sellings->isEmpty())
                    {{ $sellings->links() }}
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection