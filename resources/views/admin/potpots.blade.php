@extends('layouts.app')

@section('title', 'Potpot List')

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="potpot-list" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Potpot List" />

        <div class="max-w-7xl mx-auto px-6 py-6">
            @if (session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        showToast(@json(session('success')));
                    });
                </script>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <x-potpot-toolbar />

            <x-potpot-create-modal />

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto isolate" style="max-height: 600px; overflow-y: auto;">
                <table class="w-full text-sm border-separate border-spacing-0 table-fixed">
                    <colgroup>
                        <col class="w-[12%]">
                        <col class="w-[22%]">
                        <col class="w-[18%]">
                        <col class="w-[10%]">
                        <col class="w-[14%]">
                        <col class="w-[24%]">
                    </colgroup>
                    <thead class="text-left text-gray-900 bg-gray-50 sticky top-0 z-30">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold">Body No.</th>
                            <th class="px-6 py-3 font-bold">Operator</th>
                            <th class="px-6 py-3 font-bold">Address / Contact</th>
                            <th class="px-6 py-3 font-bold">Status</th>
                            <th class="px-6 py-3 font-bold">Registration / Expiry</th>
                            <th class="px-6 py-3 font-bold">OR No. / Remarks</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-300">
                        @forelse ($potpots as $potpot)
                            <x-potpot-table-row :potpot="$potpot" />
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    No potpots registered yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="hidden lg:block mt-4">
                @unless ($potpots->isEmpty())
                    {{ $potpots->links() }}
                @endunless
            </div>

            <!-- Mobile cards -->
            @php
                $potpotStatusColors = ['active' => 'bg-teal-500', 'renewed' => 'bg-green-600', 'expired' => 'bg-red-600'];
            @endphp
            <div class="lg:hidden space-y-4 bg-gray-50 -mx-6 px-6 py-2">
                @forelse ($potpots as $potpot)
                    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm p-4">
                        <p class="text-gray-900 font-medium truncate">{{ $potpot->body_number }} — {{ $potpot->name }}</p>
                        @if ($potpot->address)
                            <p class="text-gray-500 text-sm truncate">{{ $potpot->address }}</p>
                        @endif
                        <div class="flex flex-wrap gap-1 mt-2">
                            <span class="inline-block rounded-full {{ $potpotStatusColors[$potpot->status] ?? 'bg-gray-500' }} text-white text-xs font-semibold px-3 py-1">
                                {{ ucfirst($potpot->status) }}
                            </span>
                            @if ($potpot->or_number)
                                <span class="inline-block rounded-full bg-slate-600 text-white text-xs font-mono px-2.5 py-1">{{ $potpot->or_number }}</span>
                            @endif
                            @if ($potpot->contact_number)
                                <span class="inline-block rounded-full bg-emerald-600 text-white text-xs font-mono px-2.5 py-1">{{ $potpot->contact_number }}</span>
                            @endif
                        </div>
                        <div class="flex items-center gap-1 mt-1.5">
                            <span class="inline-block rounded-full bg-green-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $potpot->date_registered->format('M-d-y') }}</span>
                            <span class="inline-block rounded-full bg-red-600 text-white text-xs font-semibold px-2.5 py-1 whitespace-nowrap">{{ $potpot->date_expired->format('M-d-y') }}</span>
                        </div>
                        @if ($potpot->remarks)
                            <p class="text-gray-500 text-xs mt-1.5">{{ $potpot->remarks }}</p>
                        @endif
                    </div>
                @empty
                    <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                        No potpots registered yet.
                    </div>
                @endforelse
            </div>

            <div class="lg:hidden mt-4">
                @unless ($potpots->isEmpty())
                    {{ $potpots->links() }}
                @endunless
            </div>
        </div>
    </div>
</div>
@endsection