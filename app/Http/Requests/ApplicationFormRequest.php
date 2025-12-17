<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'country_code' => ['required', 'string', 'max:5'],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'gender' => ['required', 'in:male,female,other'],
            'date_of_birth' => ['required', 'date', 'before:today'],
            'educational_details' => ['nullable', 'string', 'max:5000'],
            'tenth_certificate.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'twelfth_certificate.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'graduation_certificate.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'masters_certificate.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'sports_certificate.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'extraordinary_certificate.*' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'tenth_certificate' => ['nullable', 'array'],
            'twelfth_certificate' => ['nullable', 'array'],
            'graduation_certificate' => ['nullable', 'array'],
            'masters_certificate' => ['nullable', 'array'],
            'sports_certificate' => ['nullable', 'array'],
            'extraordinary_certificate' => ['nullable', 'array'],
            'temple_address' => ['required', 'string', 'max:1000'],
            'trustee_name' => ['required', 'string', 'max:255'],
            'trustee_country_code' => ['required', 'string', 'max:5'],
            'trustee_mobile' => ['required', 'string', 'max:20'],
            'trustee_email' => ['required', 'email', 'max:255'],
            'trustee_designation' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'email.required' => 'Please enter a valid email address.',
            'country_code.required' => 'Please select a country code.',
            'phone.required' => 'Please enter your phone number.',
            'phone.regex' => 'Phone number must be exactly 10 digits.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            '*.mimes' => 'File must be a PDF, JPG, JPEG, or PNG.',
            '*.max' => 'File size cannot exceed 5MB.',
        ];
    }
}
