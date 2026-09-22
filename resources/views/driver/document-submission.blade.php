@extends('layouts.app')

@section('title', 'Submit Your Documents')

@section('content')
<section class="min-h-screen flex items-center justify-center bg-gray-100 px-4 py-10">
    <div class="w-full max-w-xl bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
        <h1 class="text-xl font-semibold text-gray-900">Tricycle Document Submission</h1>
        <p class="text-sm text-gray-500 mt-1">Upload the required documents below. The tricycle admin will review your submission.</p>

        @if (session('success'))
            <div class="mt-4 rounded-lg bg-green-50 text-green-700 text-sm px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mt-4 rounded-lg bg-red-50 text-red-600 text-sm px-4 py-3">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('driver.documents.store') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Driver Name</label>
                <input type="text" name="driver_name" value="{{ old('driver_name') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                <input type="text" name="contact_number" value="{{ old('contact_number') }}" required
                    class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Body Number (if known)</label>
                    <input type="text" name="body_number" value="{{ old('body_number') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Plate No. (if known)</label>
                    <input type="text" name="plate_no" value="{{ old('plate_no') }}"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-red-600">
                </div>
            </div>

            <hr class="border-gray-100 my-2">

            @foreach ([
                'endorsement_letter' => 'Endorsement Letter',
                'toda_certificate' => 'TODA Certificate',
                'police_clearance' => 'Police Clearance',
                'or_cr' => 'OR/CR Photocopy',
                'drivers_license' => "Driver's License Photocopy",
            ] as $field => $label)
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                    <input type="file" name="{{ $field }}" accept=".pdf,.jpg,.jpeg,.png" required
                        class="w-full text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-600 hover:file:bg-red-100">
                </div>
            @endforeach

            <button type="submit"
                class="w-full rounded-full bg-red-600 text-white px-6 py-2.5 text-sm font-semibold hover:bg-red-700 transition-colors mt-2">
                Submit Documents
            </button>
        </form>
    </div>
</section>
@endsection