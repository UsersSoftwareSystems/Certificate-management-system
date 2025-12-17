@extends('layouts.admin')

@section('title', 'View Applicant')
@section('page-title', 'Applicant Details')
@section('page-description', 'View applicant information and uploaded documents')

@section('content')
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="mb-6">
        <a href="{{ route('admin.applicants.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-medium text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to List
        </a>
    </div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $applicant->name }}</h2>
        <p class="text-gray-600 dark:text-gray-400">Application ID: {{ str_pad($applicant->id, 6, '0', STR_PAD_LEFT) }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Personal Information
            </h3>
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                <div class="mb-6 md:mb-0">
                    <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Email</dt>
                    <dd class="text-base text-gray-900 dark:text-white font-medium break-all">{{ $applicant->email }}</dd>
                </div>
                <div class="mb-6 md:mb-0">
                    <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Phone</dt>
                    <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->phone }}</dd>
                </div>
                <div class="mb-6 md:mb-0">
                    <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Gender</dt>
                    <dd class="text-base text-gray-900 dark:text-white font-medium">{{ ucfirst($applicant->gender) }}</dd>
                </div>
                <div class="mb-6 md:mb-0">
                    <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Date of Birth</dt>
                    <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->date_of_birth ? $applicant->date_of_birth->format('M d, Y') : 'Not provided' }}</dd>
                </div>
                <div class="mb-6 md:mb-0">
                    <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Current Status</dt>
                    <dd class="mt-1">
                        @if($applicant->status === 'pending')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300">
                                <svg class="w-2 h-2 mr-1.5 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                Pending Review
                            </span>
                        @elseif($applicant->status === 'verified')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                <svg class="w-2 h-2 mr-1.5 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                Verified
                            </span>
                        @elseif($applicant->status === 'rejected')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                                <svg class="w-2 h-2 mr-1.5 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                Rejected
                            </span>
                        @elseif($applicant->status === 'rejected')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                <svg class="w-2 h-2 mr-1.5 fill-current" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3" /></svg>
                                Rejected
                            </span>
                        @else
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                {{ ucfirst($applicant->status) }} 
                             </span>
                        @endif
                    </dd>
                </div>
                <div class="mb-6 md:mb-0">
                    <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Submitted On</dt>
                    <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->submitted_at ? $applicant->submitted_at->format('M d, Y g:i A') : 'Not submitted' }}</dd>
                </div>
            </dl>
        </div>

            <!-- Trustee Association Information -->
            <div class="border-t border-gray-100 dark:border-gray-700 p-6 bg-gray-50 dark:bg-gray-800/50">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                         <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Temple Association
                    </h3>
                     <!-- Trustee Status Badge -->
                    @php
                        $trusteeStatusColors = [
                            'pending' => 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400',
                            'requested' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300',
                            'approved' => 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300',
                            'rejected' => 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300',
                        ];
                    @endphp
                    <div class="flex items-center">
                        <span class="mr-2 text-sm text-gray-500 dark:text-gray-400">Trustee Verification:</span>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $trusteeStatusColors[$applicant->trustee_status] ?? 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($applicant->trustee_status) }}
                        </span>
                    </div>
                </div>
                
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 mb-6">
                    <div class="md:col-span-2 mb-6 md:mb-0">
                        <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Address of Jain Temple</dt>
                        <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->temple_address }}</dd>
                    </div>
                    <div class="mb-6 md:mb-0">
                        <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Name of Trustee</dt>
                        <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->trustee_name }}</dd>
                    </div>
                    <div class="mb-6 md:mb-0">
                        <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Trustee Designation</dt>
                        <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->trustee_designation }}</dd>
                    </div>
                    <div class="mb-6 md:mb-0">
                        <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Trustee Mobile</dt>
                        <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->trustee_mobile ?: 'Not provided' }}</dd>
                    </div>
                    <div class="mb-6 md:mb-0">
                        <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Trustee Email</dt>
                        <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->trustee_email }}</dd>
                    </div>
                </dl>

                <!-- Trustee Verification Actions Card -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-sm">
                    <div class="flex-1">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">Verification Status</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            @if($applicant->trustee_status === 'pending')
                                Verification email has not been sent yet.
                            @elseif($applicant->trustee_status === 'requested')
                                Email sent. Waiting for trustee response.
                            @elseif($applicant->trustee_status === 'approved')
                                Trustee has approved this application.
                            @elseif($applicant->trustee_status === 'rejected')
                                Trustee has rejected this application.
                            @endif
                        </p>
                         @if($applicant->trustee_responded_at)
                             <p class="text-xs text-gray-400 mt-1">
                                Last response: {{ $applicant->trustee_responded_at->format('M d, Y h:i A') }}
                            </p>
                        @endif
                    </div>
                    
                    @if($applicant->trustee_status !== 'approved' && $applicant->trustee_status !== 'rejected')
                        <form action="{{ route('admin.applicants.send-trustee-verification', $applicant->id) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm dark:focus:ring-offset-gray-900">
                                <svg class="h-4 w-4 mr-2 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ $applicant->trustee_status === 'requested' ? 'Resend Email' : 'Send Verification Email' }}
                            </button>
                        </form>
                    @elseif($applicant->trustee_status === 'approved')
                        <div class="flex items-center text-green-600 dark:text-green-400 font-medium text-sm">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Verified
                        </div>
                    @elseif($applicant->trustee_status === 'rejected')
                         <div class="flex items-center text-red-600 dark:text-red-400 font-medium text-sm">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Rejected
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Uploaded Documents -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Uploaded Documents</h3>
            @if($applicant->uploads->count() > 0)
                <div class="space-y-3">
                    @foreach($applicant->uploads as $upload)
                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $upload->getTypeLabel() }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $upload->original_filename }}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($upload->verification_status === 'pending')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-400">
                                        Pending
                                    </span>
                                @elseif($upload->verification_status === 'verified')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-400">
                                        Verified
                                    </span>
                                @elseif($upload->verification_status === 'rejected')
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-400">
                                        Rejected
                                    </span>
                                @endif
                                <a href="{{ route('admin.uploads.view', $upload) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-blue-50 hover:bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 dark:hover:bg-blue-800/50 text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 dark:text-gray-400 text-sm">No documents uploaded yet.</p>
            @endif
        </div>
    </div>

    <!-- Educational Details -->
    @if($applicant->educational_details)
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Educational Details</h3>
            <p class="text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                {{ $applicant->educational_details }}
            </p>
        </div>
    @endif

    <!-- Action Buttons and Status Section -->
    <div class="mt-6 bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 space-y-6">
        <!-- Current Status -->
        <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Application Status</h3>
            <div class="flex items-center">
                @if($applicant->status === 'pending')
                    <div class="flex items-center">
                        <div class="legend-dot-pending"></div>
                        <span class="text-sm font-medium card-text-primary">Pending Review</span>
                    </div>
                @elseif($applicant->status === 'in_verification')
                    <div class="flex items-center">
                        <div class="legend-dot-pending"></div>
                        <span class="text-sm font-medium card-text-primary">Verification in Progress</span>
                    </div>
                @elseif($applicant->status === 'verified')
                    <div class="flex items-center">
                        <div class="legend-dot-verified"></div>
                        <span class="text-sm font-medium card-text-primary">Verified</span>
                    </div>
                @else
                    <div class="flex items-center">
                        <div class="legend-dot-rejected"></div>
                        <span class="text-sm font-medium card-text-primary">Rejected</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap justify-between items-center w-full gap-4">
            @if($applicant->status === 'pending')
            <div class="flex items-center space-x-4">
                @if($applicant->trustee_status === 'approved')
                    <form action="{{ route('admin.applicants.start-verification', $applicant) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Start Verification
                        </button>
                    </form>
                @else
                    <div class="group relative inline-block">
                        <button type="button" disabled class="inline-flex items-center px-4 py-2 border border-blue-200 text-sm font-medium rounded-md shadow-sm text-blue-300 bg-blue-50 cursor-not-allowed dark:bg-gray-800 dark:border-gray-600 dark:text-gray-500">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                            Start Verification
                        </button>
                        <div class="hidden group-hover:block absolute z-10 bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-1 bg-gray-900 text-white text-xs rounded whitespace-nowrap shadow-lg">
                            Trustee approval required first
                        </div>
                    </div>
                @endif
            @endif

            @if($applicant->status === 'in_verification')
                <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                Review all documents before verifying or rejecting the application.
                            </p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('admin.applicants.complete-verification', $applicant) }}" method="POST" class="w-full">
                    @csrf
                    <div class="space-y-4">
                        <div class="w-full">
                            <label for="verification_notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Verification Notes (Optional)</label>
                            <input type="text" 
                                   id="verification_notes" 
                                   name="verification_notes" 
                                   placeholder="Add any notes about this verification" 
                                   class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors">
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 flex-1 sm:flex-none">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Approve Application
                            </button>
                            
                            <button type="button" 
                                    x-data="" 
                                    x-on:click="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'reject-modal' }))"
                                    class="inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 flex-1 sm:flex-none">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject Application
                            </button>
                        </div>
                    </div>
                </form>
            @endif

            @if($applicant->status === 'verified')
                <div class="flex items-center justify-between w-full gap-4">
                    @if(!$applicant->hasCertificate())
                        <form action="{{ route('admin.applicants.generate-certificate', $applicant) }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <div class="relative w-72">
                                <select name="template_id" id="template_id" 
                                    class="block w-full h-10 pl-3 pr-10 py-2 text-base border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 appearance-none" 
                                    required>
                                    <option value="">Select Certificate Template</option>
                                    @foreach(\App\Models\CertificateTemplate::where('active', true)->get() as $template)
                                        <option value="{{ $template->id }}">{{ $template->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-300">
                                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <button type="submit" class="generate-certificate-btn">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Generate Certificate
                            </button>
                        </form>
                    @else
                        <div class="flex items-center gap-2 text-green-600 dark:text-green-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Certificate has been generated</span>
                        </div>
                    @endif

                    @if(auth()->user()->hasAnyRole(['Super Admin','Certificate Issuer']))
                        <div class="flex items-center space-x-2">
                            <!-- New Action Buttons -->
                            <form action="{{ route('admin.applicants.send-email', $applicant) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" title="Send Email">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Email
                                </button>
                            </form>
                            
                            <button type="button" 
                                    x-on:click="$dispatch('open-whatsapp-modal', { message: 'Hello ' + '{{ addslashes($applicant->name) }}' })"
                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-green-700 dark:text-green-400 bg-green-100 dark:bg-green-900/30 border border-green-200 dark:border-green-700 rounded-lg hover:bg-green-50 dark:hover:bg-green-800/50 transition-colors"
                                    title="Send WhatsApp">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.498 14.382v-.001c-.301-.15-1.767-.867-2.143-.967-.273-.073-.473-.107-.673.107-.2.214-.77.834-.95.992-.172.15-.344.167-.624.05-.3-.1-1.263-.465-2.406-1.485-.888-.795-1.484-1.77-1.66-2.07-.174-.3-.019-.463.13-.612.136-.136.3-.357.456-.582.147-.21.194-.362.29-.6.1-.24.05-.45-.05-.63-.1-.18-.673-1.62-.922-2.22-.24-.58-.487-.51-.672-.52-.172-.01-.37-.01-.57-.01-.2 0-.52.08-.8.37-.3.31-1.15 1.12-1.15 2.73s1.17 3.17 1.33 3.39c.17.23 2.29 3.5 5.56 4.87.79.33 1.41.53 1.89.68.79.24 1.5.21 2.07.13.64-.1 1.97-.8 2.25-1.57.26-.7.26-1.3.18-1.43-.07-.13-.27-.2-.57-.32"/>
                                </svg>
                                WhatsApp
                            </button>
                            
                            <form action="{{ route('admin.applicants.destroy', $applicant) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors" onclick="return confirm('Are you sure you want to delete this applicant?')" title="Delete">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                <!-- Loading Indicator -->
                <div x-show="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm w-full mx-4">
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-lg font-medium text-gray-900 dark:text-white">Generating Certificate...</span>
                        </div>
                    </div>
                </div>
                
                <!-- Success Message -->
                <div x-show="success" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center" style="display: none;">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Certificate generated successfully!
                </div>
                
                <!-- Error Message -->
                <div x-show="error" x-text="error" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="fixed bottom-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center" style="display: none;">
                </div>
            @endif


        </div>

        <!-- Verification Notes -->
            @if($applicant->verification_notes)
            <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <h4 class="text-lg font-semibold text-blue-800 dark:text-blue-300 mb-2">Verification Notes</h4>
                <p class="text-sm text-blue-700 dark:text-blue-400">{{ $applicant->verification_notes }}</p>
            </div>
        @endif

        <!-- Rejection Reason (if rejected) -->
        @if($applicant->status === 'rejected' && $applicant->rejection_reason)
            <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                <h4 class="text-lg font-semibold text-red-800 dark:text-red-300 mb-2">Rejection Reason</h4>
                <p class="text-sm text-red-700 dark:text-red-400">{{ $applicant->rejection_reason }}</p>
            </div>
        @endif
    </div>

    <!-- Success Notification -->
    <div x-show="success" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed bottom-4 right-4 z-50 max-w-sm w-full"
         x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 3000)">
        <div class="rounded-lg bg-green-50 dark:bg-green-900/30 p-4 shadow-lg border border-green-200 dark:border-green-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200" x-text="success"></p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false" type="button" class="inline-flex rounded-md p-1.5 text-green-500 hover:bg-green-100 dark:hover:bg-green-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 dark:focus:ring-offset-green-900/30 focus:ring-green-600">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error Notification -->
    <div x-show="error" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="fixed bottom-4 right-4 z-50 max-w-sm w-full"
         x-data="{ show: true }"
         x-show="show"
         x-init="setTimeout(() => show = false, 5000)">
        <div class="rounded-lg bg-red-50 dark:bg-red-900/30 p-4 shadow-lg border border-red-200 dark:border-red-800">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200" x-text="error"></p>
                </div>
                <div class="ml-auto pl-3">
                    <div class="-mx-1.5 -my-1.5">
                        <button @click="show = false" type="button" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 dark:focus:ring-offset-red-900/30 focus:ring-red-600">
                            <span class="sr-only">Dismiss</span>
                            <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Application Modal -->
    <div x-data="{ show: false, rejectionReason: '' }" 
         x-show="show" 
         x-on:open-modal.window="if ($event.detail === 'reject-modal') { show = true; $nextTick(() => $refs.rejectionReason.focus()) }"
         x-on:keydown.escape.window="show = false"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <!-- Overlay -->
        <div x-show="show" 
             x-transition:enter="ease-out duration-300" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
             aria-hidden="true"></div>

        <!-- Modal Container -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <!-- Modal Panel -->
            <div x-show="show" 
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-xl transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-8 text-left shadow-xl transition-all">
                
                <!-- Header -->
                <div class="flex items-start">
                    <div class="shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100 dark:bg-red-900/30">
                        <svg class="h-5 w-5 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-1">Reject Application</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Please provide a reason for rejection that will be shared with the applicant.
                        </p>
                    </div>
                </div>

                <!-- Form -->
                <form action="{{ route('admin.applicants.reject', $applicant) }}" method="POST" class="mt-8">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="rejection_reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Reason for Rejection <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 relative">
                                <textarea x-ref="rejectionReason"
                                          id="rejection_reason"
                                          name="rejection_reason"
                                          rows="5"
                                          required
                                          x-model="rejectionReason"
                                          class="block w-full rounded-lg border-gray-300 dark:border-gray-600 shadow-sm focus:border-red-500 focus:ring-red-500 text-base dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 p-3 border"
                                          placeholder="Please provide specific details about why this application is being rejected..."></textarea>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Be specific and constructive. This feedback will help the applicant understand what needs to be improved.
                            </p>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-8 pt-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                        <button type="button"
                                @click="show = false"
                                class="inline-flex justify-center rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button type="submit"
                                :disabled="!rejectionReason.trim()"
                                :class="{'opacity-75 cursor-not-allowed': !rejectionReason.trim()}"
                                class="inline-flex justify-center rounded-lg border border-transparent bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-75 disabled:cursor-not-allowed">
                            Reject Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- WhatsApp Modal Component -->
    <x-whatsapp-modal :applicant="$applicant" />
@endsection

@push('scripts')
<script>
    // Listen for the open-whatsapp-modal event
    document.addEventListener('open-whatsapp-modal', function(event) {
        const modal = document.querySelector('[x-data]');
        if (modal && modal.__x.$data) {
            modal.__x.$data.show = true;
            if (event.detail && event.detail.message) {
                modal.__x.$data.message = event.detail.message;
            }
        }
    });
</script>
@endpush

