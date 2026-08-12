<div class="w-full sm:w-auto">
    <select name="toda" form="tricycle-filter-form" onchange="debouncedFetchFilter()"
        class="w-full sm:w-36 rounded-full border-2 border-gray-300 bg-white text-gray-900 font-medium px-3 py-2 text-xs sm:text-sm focus:outline-none focus:border-red-600 focus:ring-2 focus:ring-red-600">
        <option value="" @selected(request('toda') === null || request('toda') === '')>All TODA</option>
        @foreach (\App\Models\Tricycle::TODA_OPTIONS as $value => $label)
            <option value="{{ $value }}" @selected(request('toda') === $value)>{{ $label }}</option>
        @endforeach
    </select>
</div>