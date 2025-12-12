@extends('layouts.public')

@section('title', 'Application Submitted Successfully')

@section('content')
<div class="text-center max-w-2xl mx-auto">
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 p-8">
        <!-- Success Icon -->
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 dark:bg-green-900/50 mb-6">
            <svg class="h-8 w-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <!-- Success Message -->
        <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">Application Submitted Successfully!</h2>
        
        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4 mb-6">
            <p class="text-green-800 dark:text-green-200 font-medium mb-2">Thank you, {{ $applicant->name }}!</p>
            <p class="text-green-700 dark:text-green-300 text-sm">Your application has been received and is being processed.</p>
        </div>

        <!-- Application Details -->
        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 mb-6 text-left">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Application Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Application ID:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ str_pad($applicant->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Submitted:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $applicant->submitted_at->format('M d, Y g:i A') }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Email:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $applicant->email }}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700 dark:text-gray-300">Documents Uploaded:</span>
                    <span class="text-gray-900 dark:text-gray-100">{{ $applicant->uploads()->count() }} files</span>
                </div>
            </div>
        </div>

        <!-- What's Next -->
        <div class="text-left mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-3">What happens next?</h3>
            <ul class="text-sm text-gray-600 dark:text-gray-300 space-y-2">
                <li class="flex items-start">
                    <span class="flex-shrink-0 h-5 w-5 text-blue-500 mr-3">1.</span>
                    <span>Our team will review your application and uploaded documents</span>
                </li>
                <li class="flex items-start">
                    <span class="flex-shrink-0 h-5 w-5 text-blue-500 mr-3">2.</span>
                    <span>You will receive an email confirmation shortly</span>
                </li>
                <li class="flex items-start">
                    <span class="flex-shrink-0 h-5 w-5 text-blue-500 mr-3">3.</span>
                    <span>Once verified, your certificate will be generated and sent via email/WhatsApp</span>
                </li>
            </ul>
        </div>

        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            @if($applicant->edit_count < 1)
            <a href="{{ route('apply.show', $applicant->token) }}" 
               class="bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-xl transition duration-150">
                View/Edit Application
            </a>
            @endif
            <a href="{{ route('apply.create') }}" 
               class="bg-gray-300 hover:bg-gray-400 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 font-medium py-3 px-6 rounded-xl transition duration-150">
                Submit New Application
            </a>
        </div>

        <!-- Contact Info -->
        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-600 text-sm text-gray-600 dark:text-gray-400">
            <p>Need help? Contact us at: 
                <a href="mailto:support@certificate-system.com" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">support@certificate-system.com</a>
            </p>
        </div>
    </div>
</div>
@endsection
