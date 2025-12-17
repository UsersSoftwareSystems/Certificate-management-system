@extends('layouts.admin')
@section('title', 'Edit Application')
@section('page-title', 'Edit Application')
@section('page-description', 'Update applicant details')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.applicants.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
        </a>
    </div>
    
    <form method="POST" action="{{ route('admin.applicants.update', $applicant) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
            <input type="text" name="name" value="{{ old('name', $applicant->name) }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $applicant->email) }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
            <div class="flex space-x-2">
                <!-- Country Code Dropdown -->
                <div class="w-1/3">
                    <select name="country_code" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white dark:bg-gray-700 dark:text-gray-100 h-10" required>
                        <option value="" disabled>Code</option>
                        <option value="+1" {{ old('country_code', $applicant->country_code ?? '+91') === '+1' ? 'selected' : '' }}>+1 (US/CA)</option>
                        <option value="+44" {{ old('country_code', $applicant->country_code ?? '+91') === '+44' ? 'selected' : '' }}>+44 (UK)</option>
                        <option value="+61" {{ old('country_code', $applicant->country_code ?? '+91') === '+61' ? 'selected' : '' }}>+61 (AU)</option>
                        <option value="+91" {{ old('country_code', $applicant->country_code ?? '+91') === '+91' ? 'selected' : '' }}>+91 (IN)</option>
                        <option value="+971" {{ old('country_code', $applicant->country_code ?? '+91') === '+971' ? 'selected' : '' }}>+971 (UAE)</option>
                    </select>
                    @error('country_code')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Phone Number Input -->
                <div class="flex-1">
                    <input type="tel" name="phone" 
                        value="{{ old('phone', $applicant->phone) }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white dark:bg-gray-700 dark:text-gray-100 h-10"
                        placeholder="1234567890" 
                        pattern="[0-9]{10}"
                        title="Please enter a valid 10-digit phone number"
                        inputmode="numeric"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)"
                        required>
                    @error('phone')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Temple Association Information -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
            <h3 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Temple Association</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Temple Address</label>
                    <textarea name="temple_address" rows="2" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">{{ old('temple_address', $applicant->temple_address) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trustee Name</label>
                    <input type="text" name="trustee_name" value="{{ old('trustee_name', $applicant->trustee_name) }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trustee Designation</label>
                    <input type="text" name="trustee_designation" value="{{ old('trustee_designation', $applicant->trustee_designation) }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trustee Mobile</label>
                    <div class="flex space-x-2">
                        <div class="w-1/3">
                            <select name="trustee_country_code" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 h-10">
                                <option value="+1" @selected(old('trustee_country_code', $applicant->trustee_country_code) === '+1')>+1 (US/CA)</option>
                                <option value="+44" @selected(old('trustee_country_code', $applicant->trustee_country_code) === '+44')>+44 (UK)</option>
                                <option value="+61" @selected(old('trustee_country_code', $applicant->trustee_country_code) === '+61')>+61 (AU)</option>
                                <option value="+91" @selected(old('trustee_country_code', $applicant->trustee_country_code) === '+91')>+91 (IN)</option>
                                <option value="+971" @selected(old('trustee_country_code', $applicant->trustee_country_code) === '+971')>+971 (UAE)</option>
                            </select>
                        </div>
                        <div class="flex-1">
                            <input type="tel" name="trustee_mobile" value="{{ old('trustee_mobile', $applicant->trustee_mobile) }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 h-10">
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trustee Email</label>
                    <input type="email" name="trustee_email" value="{{ old('trustee_email', $applicant->trustee_email) }}" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                </div>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
            <select name="status" id="status_select" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800">
                <option value="pending" @selected(old('status', $applicant->status) === 'pending')>Pending</option>
                <option value="verified" @selected(old('status', $applicant->status) === 'verified')>Verified</option>
                <option value="rejected" @selected(old('status', $applicant->status) === 'rejected')>Rejected</option>
            </select>
        </div>

        <!-- Conditional Fields -->
        <div id="rejection_reason_container" class="hidden">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rejection Reason</label>
            <textarea name="rejection_reason" rows="3" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800" placeholder="Please provide a reason for rejection...">{{ old('rejection_reason', $applicant->rejection_reason) }}</textarea>
        </div>

        <div id="verification_notes_container" class="hidden">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Verification Notes</label>
            <textarea name="verification_notes" rows="3" class="block w-full px-4 py-2 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800" placeholder="Internal notes about verification...">{{ old('verification_notes', $applicant->verification_notes) }}</textarea>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const statusSelect = document.getElementById('status_select');
                const rejectionContainer = document.getElementById('rejection_reason_container');
                const verificationContainer = document.getElementById('verification_notes_container');

                function toggleFields() {
                    const status = statusSelect.value;
                    
                    if (status === 'rejected') {
                        rejectionContainer.classList.remove('hidden');
                        verificationContainer.classList.add('hidden');
                    } else if (status === 'verified') {
                        rejectionContainer.classList.add('hidden');
                        verificationContainer.classList.remove('hidden');
                    } else {
                        rejectionContainer.classList.add('hidden');
                        verificationContainer.classList.add('hidden');
                    }
                }

                statusSelect.addEventListener('change', toggleFields);
                toggleFields(); // Initial run
            });
        </script>

        <div class="pt-4 mt-6 border-t border-gray-200 dark:border-gray-700">
            <h3 class="text-md font-semibold card-text-primary mb-3">Attachments & Verification</h3>
            @if($applicant->uploads->isEmpty())
                <p class="text-sm table-text-muted">No attachments uploaded.</p>
            @else
                <div class="space-y-4">
                    @foreach($applicant->uploads as $upload)
                        <div class="p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium table-text">{{ $upload->getTypeLabel() }} — {{ $upload->original_filename }}</div>
                                    <div class="text-xs table-text-muted">#U{{ str_pad($upload->id, 4, '0', STR_PAD_LEFT) }} · {{ $upload->mime_type }} · {{ $upload->getFileSizeFormatted() }}</div>
                                </div>
                                <a href="{{ route('admin.uploads.view', $upload) }}" target="_blank" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-medium">View</a>
                            </div>


                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end gap-2">
            <a href="{{ route('admin.applicants.show', $applicant) }}" class="px-4 py-2 text-sm rounded-lg border border-gray-200 dark:border-gray-700">Cancel</a>
            <button type="submit" class="px-4 py-2 text-sm rounded-lg btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection


