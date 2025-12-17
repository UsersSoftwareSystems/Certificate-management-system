@extends('layouts.public')

@section('title', 'Certificate Application Form')

@section('content')
<div x-data="applicationForm()" class="space-y-8">
    <!-- Progress Header -->
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 p-6">
        <div class="text-center">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-blue-800 dark:from-blue-400 dark:to-blue-600 bg-clip-text text-transparent mb-2">
                Certificate Application Form
            </h1>
            <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Submit your educational certificates for verification and receive your official certificate
            </p>
            
            @if(isset($applicant) && $applicant->exists)
                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/50 border border-blue-200 dark:border-blue-700 rounded-xl">
                    <div class="flex items-center justify-center space-x-2">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-blue-800 dark:text-blue-200 font-medium">Editing application for: {{ $applicant->name }}</span>
                    </div>
                    <p class="text-sm text-blue-600 dark:text-blue-300 mt-1">You can update your information and re-upload documents if needed.</p>
                </div>
            @endif
        </div>
    </div>

    <form action="{{ route('apply.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        @if($errors->any())
            <div class="bg-red-50 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative" role="alert">
                <strong class="font-bold">Error!</strong>
                <span class="block sm:inline">Please fix the following errors:</span>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Personal Information Section -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Personal Information</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Full Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" 
                           value="{{ old('name', isset($applicant) ? $applicant->name : '') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white/50 dark:bg-gray-700/50 dark:text-gray-100"
                           placeholder="Enter your full name" required>
                    @error('name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email', isset($applicant) ? $applicant->email : '') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white/50 dark:bg-gray-700/50 dark:text-gray-100"
                           placeholder="Enter your email address" required>
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number with Country Code -->
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Phone Number <span class="text-red-500">*</span>
                    </label>
                    <div class="flex space-x-2">
                        <!-- Country Code Dropdown -->
                        <div class="w-1/3">
                            <select name="country_code" id="country_code"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white/50 dark:bg-gray-700/50 dark:text-gray-100" required>
                                <option value="" disabled selected>Code</option>
                                <option value="+1" {{ old('country_code', '+91') === '+1' ? 'selected' : '' }}>+1 (US/CA)</option>
                                <option value="+44" {{ old('country_code', '+91') === '+44' ? 'selected' : '' }}>+44 (UK)</option>
                                <option value="+61" {{ old('country_code', '+91') === '+61' ? 'selected' : '' }}>+61 (AU)</option>
                                <option value="+91" {{ old('country_code', '+91') === '+91' ? 'selected' : '' }}>+91 (IN)</option>
                                <option value="+971" {{ old('country_code', '+91') === '+971' ? 'selected' : '' }}>+971 (UAE)</option>
                            </select>
                            @error('country_code')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Phone Number Input -->
                        <div class="flex-1">
                            <input type="tel" id="phone" name="phone" 
                                value="{{ old('phone', isset($applicant) ? $applicant->phone : '') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white/50 dark:bg-gray-700/50 dark:text-gray-100"
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

                <!-- Gender -->
                <div class="space-y-2">
                    <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Gender <span class="text-red-500">*</span>
                    </label>
                    <select id="gender" name="gender" 
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white/80 dark:bg-gray-700/50 dark:text-gray-100"
                            required>
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender', isset($applicant) ? $applicant->gender : '') === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', isset($applicant) ? $applicant->gender : '') === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', isset($applicant) ? $applicant->gender : '') === 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                    <style>
                        select {
                            -webkit-appearance: none;
                            -moz-appearance: none;
                            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='%236B7280'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
                            background-repeat: no-repeat;
                            background-position: right 1rem center;
                            background-size: 1em;
                        }
                        .dark select {
                            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='%239CA3AF'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
                        }
                        select option {
                            background-color: white;
                            color: #1f2937;
                        }
                        .dark select option {
                            background-color: #1f2937;
                            color: #f3f4f6;
                        }
                    </style>
                    @error('gender')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date of Birth -->
                <div class="space-y-2">
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Date of Birth <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="date_of_birth" name="date_of_birth" 
                           value="{{ old('date_of_birth', isset($applicant) && $applicant->date_of_birth ? $applicant->date_of_birth->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white/50 dark:bg-gray-700/50 dark:text-gray-100"
                           required>
                    @error('date_of_birth')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Educational Details -->
                <div class="md:col-span-2 space-y-2">
                    <label for="educational_details" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Additional Educational Information
                    </label>
                    <textarea id="educational_details" name="educational_details" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white/50 dark:bg-gray-700/50 resize-none dark:text-gray-100"
                              placeholder="Any additional information about your education, achievements, etc.">{{ old('educational_details', isset($applicant) ? $applicant->educational_details : '') }}</textarea>
                    @error('educational_details')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Temple Association Information Section -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 p-8">
            <div class="flex items-center space-x-3 mb-6">
                <!-- Using Indigo and explicit style to ensure visibility -->
                <div class="w-8 h-8 rounded-lg flex items-center justify-center bg-indigo-600" style="background-color: #4f46e5;">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Temple Association Information</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Temple Address -->
                <div class="md:col-span-2 space-y-2">
                    <label for="temple_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Address of Jain Temple (Associated With) <span class="text-red-500">*</span>
                    </label>
                    <textarea id="temple_address" name="temple_address" rows="2"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-white/50 dark:bg-gray-700/50 resize-none dark:text-gray-100"
                              placeholder="Enter the address of the Jain Temple you are associated with" required>{{ old('temple_address', isset($applicant) ? $applicant->temple_address : '') }}</textarea>
                    @error('temple_address')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trustee Name -->
                <div class="space-y-2">
                    <label for="trustee_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Name of Trustee <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="trustee_name" name="trustee_name" 
                           value="{{ old('trustee_name', isset($applicant) ? $applicant->trustee_name : '') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-white/50 dark:bg-gray-700/50 dark:text-gray-100"
                           placeholder="Trustee Name" required>
                    @error('trustee_name')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trustee Designation -->
                <div class="space-y-2">
                    <label for="trustee_designation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Trustee Designation <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="trustee_designation" name="trustee_designation" 
                           value="{{ old('trustee_designation', isset($applicant) ? $applicant->trustee_designation : '') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-white/50 dark:bg-gray-700/50 dark:text-gray-100"
                           placeholder="e.g. Secretary, President" required>
                    @error('trustee_designation')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Trustee Mobile -->
                <div class="space-y-2">
                    <label for="trustee_mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Trustee Mobile Number <span class="text-red-500">*</span>
                    </label>
                    <div class="flex space-x-2">
                        <!-- Country Code Dropdown -->
                        <div class="w-1/3">
                            <select name="trustee_country_code" id="trustee_country_code"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-white/50 dark:bg-gray-700/50 dark:text-gray-100" required>
                                <option value="" disabled selected>Code</option>
                                <option value="+1" {{ old('trustee_country_code', '+91') === '+1' ? 'selected' : '' }}>+1 (US/CA)</option>
                                <option value="+44" {{ old('trustee_country_code', '+91') === '+44' ? 'selected' : '' }}>+44 (UK)</option>
                                <option value="+61" {{ old('trustee_country_code', '+91') === '+61' ? 'selected' : '' }}>+61 (AU)</option>
                                <option value="+91" {{ old('trustee_country_code', '+91') === '+91' ? 'selected' : '' }}>+91 (IN)</option>
                                <option value="+971" {{ old('trustee_country_code', '+91') === '+971' ? 'selected' : '' }}>+971 (UAE)</option>
                            </select>
                            @error('trustee_country_code')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Mobile Number Input -->
                        <div class="flex-1">
                            <input type="tel" id="trustee_mobile" name="trustee_mobile" 
                                value="{{ old('trustee_mobile', isset($applicant) ? $applicant->trustee_mobile : '') }}"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-white/50 dark:bg-gray-700/50 dark:text-gray-100"
                                placeholder="Trustee Mobile Number"
                                pattern="[0-9]{10}"
                                title="Please enter a valid 10-digit phone number"
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" 
                                required>
                            @error('trustee_mobile')
                                <p class="text-red-500 text-sm">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Trustee Email -->
                <div class="space-y-2">
                    <label for="trustee_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Trustee Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="trustee_email" name="trustee_email" 
                           value="{{ old('trustee_email', isset($applicant) ? $applicant->trustee_email : '') }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors bg-white/50 dark:bg-gray-700/50 dark:text-gray-100"
                           placeholder="trustee@example.com" required>
                    @error('trustee_email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Document Upload Section -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 p-8">
            <div class="flex items-center space-x-3 mb-6">
                <div class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-emerald-600 dark:from-emerald-600 dark:to-emerald-700 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Document Uploads</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Upload your educational certificates (PDF, JPG, PNG format, max 5MB each)</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $uploadTypes = [
                        'tenth_certificate' => '10th Certificate',
                        'twelfth_certificate' => '12th Certificate',
                        'graduation_certificate' => 'Graduation Certificate',
                        'masters_certificate' => 'Master\'s Certificate',
                        'sports_certificate' => 'Sports Achievement Certificate',
                        'extraordinary_certificate' => 'Extraordinary Achievement / Other',
                    ];
                @endphp

                @foreach($uploadTypes as $key => $label)
                <div x-data="fileUpload('{{ $key }}', {{ isset($applicant) && $applicant->uploads ? json_encode($applicant->uploads->where('type', str_replace('_certificate', '', $key))->map(function($u) { return ['id' => $u->id, 'name' => $u->original_filename]; })->values()) : '[]' }})" class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ $label }}</label>
                    <div @dragover.prevent @drop.prevent="handleDrop($event)" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-6 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all" :class="{ 'border-blue-400': isDragging }">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="mt-4">
                                <input type="file" name="{{ $key }}[]" @change="handleFileSelect($event)" multiple accept=".pdf,.jpg,.jpeg,.png" class="hidden" id="{{ $key }}">
                                <label for="{{ $key }}" class="cursor-pointer text-sm text-blue-600 dark:text-blue-400 font-medium hover:underline">Upload files</label>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">or drag and drop</p>
                        </div>
                    </div>
                    </div>
                    
                    <!-- Existing Files -->
                    <div x-show="existingFiles.length > 0" class="space-y-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Existing:</p>
                        <template x-for="(file, index) in existingFiles" :key="'existing-' + file.id">
                            <div class="flex items-center justify-between p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg border border-blue-100 dark:border-blue-800">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 011.414.414l5.586 5.586a1 1 0 01.414 1.414V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300" x-text="file.name"></span>
                                </div>
                                <button type="button" @click.prevent="removeExistingFile(index)" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 dark:hover:bg-red-900/50 transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6.293 6.293a1 1 0 011.414 0L10 8.586l2.293-2.293a1 1 0 111.414 1.414L11.414 10l2.293 2.293a1 1 0 01-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 10 6.293 7.707a1 1 0 010-1.414z"></path></svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <!-- New Files -->
                    <div x-show="files.length > 0" class="space-y-2">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">New:</p>
                        <template x-for="(file, index) in files" :key="index">
                            <div class="flex items-center justify-between p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center space-x-2">
                                    <img :src="file.preview" class="w-8 h-8 rounded-md object-cover">
                                    <span class="text-sm text-gray-700 dark:text-gray-300" x-text="file.name"></span>
                                </div>
                                <button type="button" @click.prevent="removeFile(index)" class="text-red-500 hover:text-red-700">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M6.293 6.293a1 1 0 011.414 0L10 8.586l2.293-2.293a1 1 0 111.414 1.414L11.414 10l2.293 2.293a1 1 0 01-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 01-1.414-1.414L8.586 10 6.293 7.707a1 1 0 010-1.414z"></path></svg>
                                </button>
                            </div>
                        </template>
                    </div>
                    @error($key)
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
                @endforeach
            </div>
        </div>

        <!-- Submit Section -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl border border-gray-200/50 dark:border-gray-700/50 p-8">
            <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0">
                <div class="flex items-center space-x-2 text-sm text-gray-600 dark:text-gray-300">
                    <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Fields marked with * are required</span>
                </div>
                <button type="submit" 
                        class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 dark:from-blue-500 dark:to-blue-600 dark:hover:from-blue-600 dark:hover:to-blue-700 text-white font-semibold py-3 px-8 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform hover:scale-105">
                    <div class="flex items-center space-x-2">
                        <span>{{ isset($applicant) && $applicant->exists ? 'Update Application' : 'Submit Application' }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
// Format phone number with country code for display
function formatPhoneNumber(phone) {
    if (!phone) return '';
    // Remove any non-digit characters
    const cleaned = ('' + phone).replace(/\D/g, '');
    // Format as (XXX) XXX-XXXX
    const match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
    if (match) {
        return '(' + match[1] + ') ' + match[2] + '-' + match[3];
    }
    return phone;
}

// Initialize the application form
function applicationForm() {
    return {
        // Form functionality
    }
}


function fileUpload(name, existingFiles = []) {
    return {
        files: [],
        existingFiles: existingFiles,
        isDragging: false,
        
        init() {
            // No need to init files, existingFiles are separate
        },

        handleFileSelect(event) {
            this.addFiles(event.target.files);
        },
        handleDrop(event) {
            this.isDragging = false;
            this.addFiles(event.dataTransfer.files);
        },
        addFiles(fileList) {
            for (let i = 0; i < fileList.length; i++) {
                const file = fileList[i];
                if (file.size > 5242880) {
                    alert('File ' + file.name + ' exceeds 5MB limit.');
                    continue;
                }
                file.preview = URL.createObjectURL(file);
                this.files.push(file);
            }
        },
        removeFile(index) {
            this.files.splice(index, 1);
        },
        async removeExistingFile(index) {
            const file = this.existingFiles[index];
            if (!confirm('Are you sure you want to delete this file?')) return;

            try {
                const response = await fetch(`/apply/upload/${file.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                if (response.ok) {
                    this.existingFiles.splice(index, 1);
                } else {
                    const data = await response.json();
                    alert(data.message || 'Failed to delete file.');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while deleting the file.');
            }
        }
    }
}
</script>
@endsection
