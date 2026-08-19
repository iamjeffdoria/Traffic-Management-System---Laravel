@props(['franchise', 'tricycles'])

<div id="edit-franchise-modal-{{ $franchise->id }}" class="hidden fixed inset-0 z-50 items-center justify-center px-4 py-6 overflow-y-auto">
    <div onclick="closeModal('edit-franchise-modal-{{ $franchise->id }}')" class="absolute inset-0 bg-black/50"></div>

    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
        <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-gray-100 shrink-0">
            <h3 class="font-semibold text-gray-900 text-lg">Edit Franchise</h3>
            <button type="button" onclick="closeModal('edit-franchise-modal-{{ $franchise->id }}')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form id="franchise-edit-form-{{ $franchise->id }}" data-franchise-form method="POST" action="{{ route('tricycle.franchise.update', $franchise) }}" class="grid sm:grid-cols-2 gap-4 px-6 py-5 overflow-y-auto">
            @csrf
            @method('PUT')

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tricycle (Body No.)</label>
                <select name="tricycle_id" required onchange="syncFranchiseTricycleFields(this)"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    @foreach ($tricycles as $tricycle)
                        <option value="{{ $tricycle->id }}"
                            data-name="{{ $tricycle->name }}"
                            data-plate="{{ $tricycle->plate_no }}"
                            data-motor="{{ $tricycle->engine_motor_no }}"
                            data-chassis="{{ $tricycle->chassis_no }}"
                            @selected($franchise->tricycle_id === $tricycle->id)>
                            {{ $tricycle->body_number }} — {{ $tricycle->plate_no }} ({{ $tricycle->name }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                <input type="text" name="name" data-franchise-name-display value="{{ $franchise->name }}" readonly required
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valid Until</label>
                <input type="date" name="valid_until" value="{{ $franchise->valid_until->format('Y-m-d') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Plate No.</label>
                <input type="text" name="plate_no" data-franchise-plate-display value="{{ $franchise->plate_no }}" readonly required
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Denomination</label>
                <input type="text" name="denomination" value="{{ $franchise->denomination }}"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                    <option value="New" @selected($franchise->status === 'New')>New</option>
                    <option value="Renewed" @selected($franchise->status === 'Renewed')>Renewed</option>
                    <option value="Expired" @selected($franchise->status === 'Expired')>Expired</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Authorized No.</label>
                <input type="text" name="authorized_no" value="{{ $franchise->authorized_no }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Motor No.</label>
                <input type="text" name="motor_no" data-franchise-motor-display value="{{ $franchise->motor_no }}" readonly required
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Chassis No.</label>
                <input type="text" name="chassis_no" data-franchise-chassis-display value="{{ $franchise->chassis_no }}" readonly required
                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-4 py-2.5 text-sm text-gray-600">
            </div>

            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Authorized Route</label>
                <textarea name="authorized_route" rows="2" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">{{ $franchise->authorized_route }}</textarea>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                <textarea name="purpose" rows="2"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">{{ $franchise->purpose }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Official Receipt No.</label>
                <input type="text" name="official_receipt_no" value="{{ $franchise->official_receipt_no }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount Paid</label>
                <input type="number" step="0.01" name="amount_paid" value="{{ $franchise->amount_paid }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ $franchise->date->format('Y-m-d') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Municipal Treasurer</label>
                <input type="text" name="municipal_treasurer" value="{{ $franchise->municipal_treasurer }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
        </form>

        <div class="flex gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
            <button type="submit" form="franchise-edit-form-{{ $franchise->id }}"
                class="flex-1 rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors">
                Save Changes
            </button>
            <button type="button" onclick="closeModal('edit-franchise-modal-{{ $franchise->id }}')"
                class="flex-1 rounded-full border border-gray-300 text-gray-700 px-6 py-2.5 text-sm font-semibold hover:bg-gray-50 transition-colors">
                Cancel
            </button>
        </div>
    </div>
</div>