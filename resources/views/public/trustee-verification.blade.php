@extends('layouts.public')

@section('title', 'Verify Application')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <!-- Header -->
        <!-- Header -->
        <div class="px-8 py-10 bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 text-center">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Trustee Verification Request</h1>
            <p class="text-gray-600 dark:text-gray-400">Please review the details below carefully.</p>
        </div>

        <div class="p-8">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 relative" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6 relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if($alreadyResponded && !session('success'))
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-6 rounded-lg text-center">
                    <svg class="w-12 h-12 text-yellow-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-bold">This request has already been processed.</h3>
                    <p class="mt-2 text-sm">Action taken: 
                        <strong class="uppercase">{{ $applicant->trustee_status }}</strong>
                        on {{ $applicant->trustee_responded_at ? $applicant->trustee_responded_at->format('M d, Y') : 'Unknown' }}
                    </p>
                </div>
            @else
                <!-- Applicant Details -->
                <div class="mb-10">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center border-b border-gray-100 dark:border-gray-700 pb-2">
                        <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Applicant Information
                    </h3>
                    <dl class="flex flex-col space-y-6">
                        <div>
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Full Name</dt>
                            <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Date of Birth</dt>
                            <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->date_of_birth->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Email</dt>
                            <dd class="text-base text-gray-900 dark:text-white font-medium break-all">{{ $applicant->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Phone</dt>
                            <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->phone }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="mb-8">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center border-b border-gray-100 dark:border-gray-700 pb-2">
                        <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Temple Association Claim
                    </h3>
                    <dl class="flex flex-col space-y-6">
                        <div>
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Temple Address Cited</dt>
                            <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->temple_address }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Trustee Cited (You)</dt>
                            <dd class="text-base text-gray-900 dark:text-white font-medium">{{ $applicant->trustee_name }} <span class="text-gray-500 font-normal">({{ $applicant->trustee_designation }})</span></dd>
                        </div>
                    </dl>
                </div>

                @if(!session('success'))
                    <!-- Action Form -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8 mt-8">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 text-center">Do you verify this information?</h3>
                        
                        <form action="{{ route('apply.trustee.verify.update', $applicant->token) }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <!-- Comment Field (Hidden by default, shown for rejection) -->
                            <div x-data="{ showReason: false }">
                                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                    <button type="submit" name="action" value="approve" 
                                            class="w-full sm:w-auto px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transform transition hover:scale-105">
                                        Yes, Approve
                                    </button>
                                    
                                    <button type="button" @click="showReason = !showReason"
                                            class="w-full sm:w-auto px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl shadow-lg transform transition hover:scale-105">
                                        No, Reject
                                    </button>
                                </div>

                                <div x-show="showReason" x-transition class="mt-6 max-w-lg mx-auto">
                                    <label for="comments" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Reason for Rejection (Required)
                                    </label>
                                    <textarea id="comments" name="comments" rows="3" 
                                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-red-500 focus:border-red-500"
                                              placeholder="Please briefly explain why you are rejecting this request..."></textarea>
                                    
                                    <button type="submit" name="action" value="reject"
                                            class="mt-4 w-full px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg">
                                        Confirm Rejection
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            @endif
        </div>
        <div class="bg-gray-50 dark:bg-gray-700/50 px-8 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
            &copy; {{ date('Y') }} Certificate Management System. All rights reserved.
        </div>
    </div>
</div>
@endsection
