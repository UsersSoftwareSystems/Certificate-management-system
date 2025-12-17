<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Applicant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'country_code',
        'phone',
        'gender',
        'date_of_birth',
        'token',
        'status',
        'educational_details',
        'notes',
        'submitted_at',
        'verification_started_at',
        'verification_started_by',
        'verification_completed_at',
        'verification_completed_by',
        'verification_notes',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'temple_address',
        'trustee_name',
        'trustee_country_code',
        'trustee_mobile',
        'trustee_email',
        'trustee_designation',
        'trustee_status',
        'trustee_responded_at',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'educational_details' => 'array',
        'submitted_at' => 'datetime',
        'verification_started_at' => 'datetime',
        'verification_completed_at' => 'datetime',
        'rejected_at' => 'datetime',
        'trustee_responded_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Delete related uploads when an applicant is deleted
        static::deleting(function($applicant) {
            // Get all uploads for this applicant
            $uploads = $applicant->uploads()->withTrashed()->get();
            
            // If not force deleting (i.e., soft delete)
            if (method_exists($applicant, 'isForceDeleting') && !$applicant->isForceDeleting()) {
                // Just soft delete the uploads
                $applicant->uploads()->delete();
            } else {
                // Force delete the uploads and their files
                foreach ($uploads as $upload) {
                    // This will trigger the deleting event in the Upload model
                    $upload->forceDelete();
                }
            }
        });
        
        // Restore related uploads when an applicant is restored
        static::restoring(function($applicant) {
            $applicant->uploads()->onlyTrashed()->restore();
        });
        
        static::creating(function ($model) {
            if (empty($model->token)) {
                $model->token = Str::random(64);
            }
            if (empty($model->submitted_at)) {
                $model->submitted_at = now();
            }
        });
        
        // Ensure all file deletions are handled in a transaction
        static::deleted(function($applicant) {
            if ($applicant->isForceDeleting()) {
                // Log the deletion for audit
                \App\Models\AuditLog::create([
                    'user_id' => auth()->id() ?? null,
                    'action' => 'applicant_deleted',
                    'target_type' => get_class($applicant),
                    'target_id' => $applicant->id,
                    'metadata' => [
                        'name' => $applicant->name,
                        'email' => $applicant->email,
                        'uploads_deleted' => $applicant->uploads()->count(),
                    ],
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        });
    }

    /**
     * Get all applications for the applicant.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
    
    /**
     * Get all uploads for the applicant.
     */
    public function uploads(): HasMany
    {
        return $this->hasMany(Upload::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function latestCertificate(): HasOne
    {
        return $this->hasOne(Certificate::class)->latest();
    }

    public function getUploadByType(string $type): ?Upload
    {
        return $this->uploads()->where('type', $type)->first();
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function hasCertificate(): bool
    {
        return $this->certificates()->exists();
    }

    public function getVerifiedUploadsCount(): int
    {
        return $this->uploads()->where('verification_status', 'verified')->count();
    }

    public function getTotalUploadsCount(): int
    {
        return $this->uploads()->count();
    }

    public function getPublicUrl(): string
    {
        return route('apply.show', $this->token);
    }
}
