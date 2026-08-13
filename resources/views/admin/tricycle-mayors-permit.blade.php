@extends('layouts.app')

@section('title', "Mayor's Permit - Tricycle")

@section('content')
<div class="lg:flex group/layout">
    <x-sidebar active="tricycle-mayors-permit" />
    <div class="flex-1 min-w-0 lg:ml-56 lg:group-has-[#sidebar-collapse:checked]/layout:ml-16 transition-all duration-300 ease-in-out">
        <x-topbar title="Mayor's Permit - Tricycle" />

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

            <x-tricycle-mayors-permit-toolbar />

            <x-tricycle-mayors-permit-create-modal :tricycles="$tricycles" />

            <!-- Desktop table -->
            <div class="hidden lg:block rounded-2xl border border-gray-200 overflow-x-auto">
                <table class="w-full text-sm border-collapse table-fixed">
                    <colgroup>
                        <col class="w-[10%]">
                        <col class="w-[16%]">
                        <col class="w-[16%]">
                        <col class="w-[16%]">
                        <col class="w-[10%]">
                        <col class="w-[14%]">
                        <col class="w-[18%]">
                    </colgroup>
                    <thead class="bg-gray-50 text-left text-gray-900">
                        <tr class="divide-x divide-gray-300 border-b-2 border-gray-300">
                            <th class="px-6 py-3 font-bold">Status</th>
                            <th class="px-6 py-3 font-bold">Control No.</th>
                            <th class="px-6 py-3 font-bold">Tricycle / Owner</th>
                            <th class="px-6 py-3 font-bold">Business / Operation</th>
                            <th class="px-6 py-3 font-bold">Amount Paid</th>
                            <th class="px-6 py-3 font-bold">Issue / Expiry</th>
                            <th class="px-6 py-3 font-bold">Mayor / Issued At / Quarter</th>
                        </tr>
                    </thead>
                    <tbody id="tricycle-mayors-permit-tbody-desktop" class="divide-y divide-gray-300">
                        @forelse ($permits as $permit)
                            <x-tricycle-mayors-permit-table-row :permit="$permit" />
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    No permits issued yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="tricycle-mayors-permit-pagination-desktop" class="mt-4">
                {{ $permits->links() }}
            </div>

            <!-- Mobile stacked cards -->
            <div id="tricycle-mayors-permit-cards-mobile" class="lg:hidden space-y-3">
                @forelse ($permits as $permit)
                    <x-tricycle-mayors-permit-mobile-card :permit="$permit" />
                @empty
                    <div class="rounded-2xl border border-gray-200 p-8 text-center text-gray-500 text-sm">
                        No permits issued yet.
                    </div>
                @endforelse
            </div>

            <div id="tricycle-mayors-permit-pagination-mobile" class="lg:hidden mt-4">
                {{ $permits->links() }}
            </div>
        </div>
    </div>
</div>
@endsection